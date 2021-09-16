<?php

namespace App\Services;

use App\App;
use App\Country;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

/**
 * This is a helper service to connect to Apigee.
 * It has the base connections through GET, POST, PUT and Delete but has
 * some helper functions on top of it for convenience.
 */
class ApigeeService
{
    public static function get(string $url)
    {
        return self::HttpWithBasicAuth()->get(config('apigee.base') . $url)->json();
    }

    public static function post(string $url, array $data, array $headers = [])
    {
        $h = array_merge([
            'Content-Type' => 'application/json'
        ], $headers);

        return self::HttpWithBasicAuth()
            ->withHeaders($h)
            ->post(config('apigee.base') . $url, $data);
    }

    public static function put(string $url, array $data)
    {
        return self::HttpWithBasicAuth()->put(config('apigee.base') . $url, $data);
    }

    public static function delete(string $url)
    {
        return self::HttpWithBasicAuth()->delete(config('apigee.base') . $url);
    }

    public static function createApp(array $data, User $user = null)
    {
        $user ??= auth()->user();
        return self::post("developers/{$user->email}/apps", $data);
    }

    public static function updateApp(array $data)
    {
        $user = auth()->user();
        $name = $data['name'];
        $key = $data['key'];
        $apiProducts = $data['apiProducts'];
        $originalProducts = $data['originalProducts'];
        $removedProducts = array_diff($originalProducts, $apiProducts);

        self::post("developers/{$user->email}/apps/{$name}/keys/{$key}", ["apiProducts" => $apiProducts]);

        foreach ($removedProducts as $product) {
            self::delete("developers/{$user->email}/apps/{$name}/keys/{$key}/apiproducts/{$product}");
        }

        $updatedDetails = self::put("developers/{$user->email}/apps/{$name}", [
            "name" => $name,
            "attributes" => $data['attributes'],
            "callbackUrl" => $data['callbackUrl'],
        ]);

        return $updatedDetails;
    }

    public static function updateAppWithNewCredentials(array $data, $user = null): Response
    {
        $user ??= auth()->user();
        $name = $data['name'];

        return self::put("developers/{$user->email}/apps/{$name}", [
            "name" => $name,
            "attributes" => $data['attributes'],
            "callbackUrl" => $data['callbackUrl'],
            "apiProducts" => $data['apiProducts']
        ]);
    }

    public static function getCredentials(App $app, $type, $respondWith = 'jsonResponse')
    {
        $credentials = self::get('apps/' . $app->aid)['credentials'];
        $credentials = self::sortCredentials($credentials);
        $sandboxedProducts = $app->products->filter(function ($prod) {
            $envArr = explode(',', $prod->environments);
            return in_array('sandbox', $envArr) && !in_array('prod', $envArr);
        })->pluck('name')->toArray();
        $typeAndEnvironment = explode('-', $type);
        $creds = [
            'sandbox' => [],
            'production' => [],
        ];

        foreach ($credentials as $credential) {
            if (count(array_intersect($sandboxedProducts, array_column($credential['apiProducts'], 'apiproduct'))) > 0) {
                $creds['sandbox'] = $credential;
            } else {
                $creds['production'] = $credential;
            }
        }

        if ($typeAndEnvironment[1] === 'production') {
            $credentials =  $creds['production'][$typeAndEnvironment[0]] ?? '';
        } else if ($typeAndEnvironment[1] === 'sandbox') {
            $credentials =  $creds['sandbox'][$typeAndEnvironment[0]] ?? '';
        } else if ($type !== 'all') {
            $credentials = $credentials[0][$typeAndEnvironment[0]] ?? '';
        }

        if ($respondWith === 'string') {
            return $credentials;
        }

        return response()->json([
            'credentials' => $credentials
        ]);
    }

    /**
     * Revoke credentials and then add a set of new credentials
     *
     * @param      \App\User                         $user   The user
     * @param      \App\App                          $app    The application
     * @param      string                            $key    The key
     *
     * @return     \Illuminate\Http\Client\Response  The client response
     */
    public static function renewCredentials(User $user, App $app, string $key)
    {
        $apiProducts = [];
        $attributes = [];
        $redactedKey = $app->redact($key);

        foreach ($app->credentials as $credential) {
            if ($credential['consumerKey'] === $redactedKey) {
                $apiProducts = $credential['apiProducts'];
                break;
            }
        }

        $appProducts = $app->products->filter(fn ($prod) => in_array($prod->name, $apiProducts));

        foreach ($app->attributes as $name => $value) {
            $attributes[] = [
                'name' => $name,
                'value' => $value,
            ];
        }

        $updatedApp = self::updateAppWithNewCredentials([
            'name' => $app->name,
            'attributes' => $attributes,
            'callbackUrl' => $app->callbackUrl ?? '',
            'apiProducts' => $apiProducts,
        ], $user);

        if ($updatedApp->status() !== 200) return $updatedApp;

        $updatedCredentials = self::sortCredentials($updatedApp['credentials']);
        $updatedCredentials = end($updatedCredentials)['consumerKey'];
        $statusLookup = [
            'approved' => 'approve',
            'revoked' => 'revoke'
        ];

        foreach ($appProducts as $prod) {
            if ($prod->pivot->status === 'pending') continue;

            self::updateProductStatus($user->email, $app->name, $updatedCredentials, $prod->name, $statusLookup[$prod->pivot->status]);
        }

        self::revokeCredentials($user, $app, $key);

        return $updatedApp;
    }


    /**
     * Revoke credentials
     *
     * @param      \App\User                         $user   The user
     * @param      \App\App                          $app    The application
     * @param      string                            $key    The key
     *
     * @return     \Illuminate\Http\Client\Response  The client response
     */
    public static function revokeCredentials(User $user, App $app, string $key)
    {
        return self::post("developers/{$user->email}/apps/{$app->name}/keys/{$key}?action=revoke", [], ['Content-Type' => 'application/octet-stream']);
    }

    public static function getAppAttributes(array $attributes)
    {
        $a = [];
        foreach ($attributes as $attribute) {
            $key = Str::studly($attribute['name']);
            if (!isset($attribute['value'])) {
                $attribute['value'] = '';
            }
            $value = $key === 'Group' ? Str::studly($attribute['value']) : $attribute['value'];
            $a[$key] = $value;
        }
        return $a;
    }

    public static function getOrgApps(string $status = 'approved', int $rows = 10)
    {
        $apps = [];
        $appRows = [];
        $startKey = '';
        $lastAppId = '';

        if ($rows > 0) {
            return self::getOrgAppsRows($status, $rows);
        }

        while (true) {
            if (count($apps) !== 0 && $lastAppId === end($apps)['appId']) break;

            $lastAppId = count($apps) === 0 ? '' : end($apps)['appId'];
            $startKey = empty($lastAppId) ? '' : '&startKey=' . $lastAppId;

            $appRows =  self::getOrgAppsRows($status, 300, $startKey);

            if (count($appRows) === 0) break;

            $apps = array_merge($apps, $appRows);
        }

        return $apps;
    }

    public static function getOrgAppsRows(string $status = 'approved', int $rows = 10, string $startKey = ''): array
    {
        $status = $status === "all" ? "" : "&status={$status}";
        $apps = self::get("/apps?expand=true&rows={$rows}{$status}{$startKey}");
        if (isset($apps['app'])) {
            $apps = $apps['app'];
        }

        if ($startKey !== '') {
            array_shift($apps);
        }

        for ($i = 0; $i < count($apps); $i++) {
            if (!isset($apps[$i]['credentials'])) continue;

            $apps[$i]['credentials'] = self::sortCredentials($apps[$i]['credentials']);
        }

        return $apps;
    }

    public static function getDeveloperDetails(string $developer_id)
    {
        $developer = self::get("/developers/{$developer_id}?expand=true");

        return [
            'first_name' => $developer['firstName'],
            'last_name' => $developer['lastName'],
            'email' => $developer['email'],
            'developer_id' => $developer['developerId'],
            'created_at' => date('Y-m-d H:i:s', $developer['createdAt'] / 1000),
            'updated_at' => date('Y-m-d H:i:s', $developer['lastModifiedAt'] / 1000),
        ];
    }

    public static function getDeveloperApps($email)
    {
        $apps = self::get("developers/{$email}/apps/?expand=true");

        for ($i = 0; $i < count($apps['app']); $i++) {
            if (!isset($apps['app'][$i]['credentials'])) {
                continue;
            }

            $apps['app'][$i]['credentials'] = self::getLatestCredentials($apps['app'][$i]['credentials']);
        }

        return $apps;
    }

    public static function getDeveloperApp($email, $appName)
    {
        $apps = self::get("developers/{$email}/apps/{$appName}/?expand=true");

        $apps['credentials'] = self::getLatestCredentials($apps['credentials']);

        return $apps;
    }

    public static function getAppCountries(object $products): array
    {
        $productLocations = $products->pluck('locations');
        if ($productLocations->contains('all') || $productLocations->contains(null)) {
            return ['all' => 'All'];
        }

        $countryCodes = $productLocations->all();

        $countryCodesArr = [];
        foreach ($countryCodes as $codes) {
            $countryCodesArr[] = explode(',', $codes);
        }

        if (empty($countryCodesArr)) {
            return ['all' => 'All'];
        } else if (count($countryCodesArr) > 1) {
            $countryCodes = call_user_func_array('array_intersect', $countryCodesArr);
        } else {
            $countryCodes = $countryCodesArr[0];
        }

        if (empty($countryCodes)) {
            return ['mix' => 'Mix'];
        }

        return Country::whereIn('code', $countryCodes)->pluck('name', 'code')->toArray();
    }

    public static function updateProductStatus(string $id, string $app, string $key, string $product, string $action)
    {
        return self::post("developers/{$id}/apps/{$app}/keys/{$key}/apiproducts/{$product}?action={$action}", [], ['Content-Type' => 'application/octet-stream']);
    }

    /**
     * Gets the latest credentials.
     *
     * @param      array  $credentials  The credentials
     *
     * @return     array  The latest credentials.
     */
    public static function getLatestCredentials(array $credentials): array
    {
        usort($credentials, [__CLASS__, "sortByIssuedAtDesc"]);

        for ($i = 0; $i < count($credentials); $i++) {
            if ($credentials[$i]['status'] === 'approved') {
                return $credentials[$i];
            }
        }

        return $credentials[0];
    }

    /**
     * Sorts credentials by issued date
     *
     * @param      array  $credentials  The credentials
     *
     * @return     array  The sorted credentials
     */
    public static function sortCredentials(array $credentials, string $sort = 'Asc'): array
    {
        $creds = [];

        usort($credentials, [__CLASS__, "sortByIssuedAt{$sort}"]);

        foreach ($credentials as $credential) {
            if ($credential['status'] === 'revoked') continue;

            $creds[] = $credential;
        }

        return $creds;
    }

    public static function getAppCredentials($app)
    {
        $credentials = self::get('apps/' . $app->aid)['credentials'];

        usort($credentials, [__CLASS__, "sortByIssuedAtAsc"]);

        return $credentials;
    }

    public static function formatAppCredentials(array $credentials): array
    {
        for ($i = 0; $i < count($credentials); $i++) {
            $credentials[$i]['apiProducts'] = array_map(fn ($apiProduct) => $apiProduct['apiproduct'], $credentials[$i]['apiProducts']);
        }

        return $credentials;
    }

    protected static function HttpWithBasicAuth()
    {
        return Http::withBasicAuth(config('apigee.username'), config('apigee.password'));
    }

    /**
     * Monitisation
     */

    /**
     * @param  string $url
     *
     * @return mixed
     */
    public static function getMint(string $url)
    {
        return self::HttpWithBasicAuth()->get(config('apigee.base_mint') . $url)->json();
    }

    /**
     * @return mixed
     */
    public static function getBundles()
    {
        return self::getMint('monetization-packages');
    }

    /**
     * @return mixed
     */
    public static function getRatePlans()
    {
        return self::getMint('rate-plans');
    }

    /**
     * Helpers
     */

    /**
     * Sort by issued at
     *
     * @param      int   $a      First sort variable
     * @param      int   $b      Second sort variable
     *
     * @return     int   The sort order
     */
    protected static function sortByIssuedAtDesc($a, $b)
    {
        if ($a['issuedAt'] == $b['issuedAt']) {
            return 0;
        }

        return ($a['issuedAt'] > $b['issuedAt']) ? -1 : 1;
    }

    /**
     * Sort by issued at
     *
     * @param      int   $a      First sort variable
     * @param      int   $b      Second sort variable
     *
     * @return     int   The sort order
     */
    protected static function sortByIssuedAtAsc($a, $b)
    {
        if ($a['issuedAt'] == $b['issuedAt']) {
            return 0;
        }

        return ($a['issuedAt'] < $b['issuedAt']) ? -1 : 1;
    }

    public static function pushAppNote(App $app, array $attributes, string $status = 'approved'): Response
    {
        $action = [
            'approved' => 'approve',
            'revoked' => 'revoke'
        ][$status] ?? 'approve';

        self::post("developers/{$app->developer->email}/apps/{$app->name}?action={$action}", [], ['Content-Type' => 'application/octet-stream']);

        return self::put("developers/{$app->developer->email}/apps/{$app->name}", [
            "name" => $app->name,
            "attributes" => $attributes,
            "callbackUrl" => $app->callbackUrl ?? '',
        ]);
    }
}
