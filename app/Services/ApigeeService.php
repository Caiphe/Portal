<?php

namespace App\Services;

use App\App;
use App\Team;
use App\User;
use App\Country;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

/**
 * This is a helper service to connect to Apigee.
 * It has the base connections through GET, POST, PUT and Delete but has
 * some helper functions on top of it for convenience.
 */
class ApigeeService
{
    protected static function HttpWithBasicAuth()
    {
        return Http::withBasicAuth(config('apigee.username'), config('apigee.password'));
    }

    public static function HttpWithToken(string $grantType = 'password')
    {
        if ($grantType === 'refresh_token') {
            Cache::forget('apigeeToken');
        }

        // Cache for 30 days
        $token = Cache::remember('apigeeToken', 2592000, function () use ($grantType) {
            return $grantType === 'refresh_token' ? self::HttpWithTokenRefreshToken() : self::HttpWithTokenPassword();
        });

        return Http::withToken($token['access_token']);
    }

    protected static function HttpWithTokenPassword()
    {
        return Http::withHeaders([
            'Accept' => 'application/json;charset=utf-8',
            'Authorization' => 'Basic ZWRnZWNsaTplZGdlY2xpc2VjcmV0'
        ])
            ->asForm()
            ->post('https://login.apigee.com/oauth/token', [
                'grant_type' => 'password',
                'username' => config('apigee.username'),
                'password' => config('apigee.password')
            ])->json();
    }

    protected static function HttpWithTokenRefreshToken()
    {
        $token = Cache::pull('apigeeToken');

        if (is_null($token)) {
            return self::HttpWithTokenPassword();
        };

        $resp = Http::withHeaders([
            'Accept' => 'application/json;charset=utf-8',
            'Authorization' => 'Basic ZWRnZWNsaTplZGdlY2xpc2VjcmV0'
        ])
            ->asForm()
            ->post('https://login.apigee.com/oauth/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $token['refresh_token']
            ]);

        if ($resp->failed()) {
            return self::HttpWithTokenPassword();
        }

        return $resp->json();
    }

    protected static function encodeUrl(string $url): string
    {
        preg_match('/\?.*/', $url, $get);
        $url = preg_replace('/\?.*/', '', $url);
        $url = explode('/', $url);
        $url = array_map(fn ($arg) => rawurlencode($arg), $url);
        $url = implode('/', $url);
        return $url . ($get[0] ?? '');
    }

    public static function get(string $url)
    {
        $url = self::encodeUrl($url);
        $resp = self::HttpWithToken()->get(config('apigee.base') . $url);

        if ($resp->status() === 401 || $resp->status() === 403) {
            $resp = self::HttpWithToken('refresh_token')->get(config('apigee.base') . $url);
        }

        self::checkForErrors($resp, $url);

        return $resp->json();
    }

    public static function post(string $url, array $data, array $headers = [])
    {
        $url = self::encodeUrl($url);
        $h = array_merge([
            'Content-Type' => 'application/json'
        ], $headers);

        $resp = self::HttpWithToken()
            ->withHeaders($h)
            ->post(config('apigee.base') . $url, $data);

        if ($resp->status() === 401 || $resp->status() === 403) {
            $resp = self::HttpWithToken('refresh_token')
                ->withHeaders($h)
                ->post(config('apigee.base') . $url, $data);
        }

        self::checkForErrors($resp, $url);

        return $resp;
    }

    public static function put(string $url, array $data)
    {
        $url = self::encodeUrl($url);
        $resp = self::HttpWithToken()->put(config('apigee.base') . $url, $data);

        if ($resp->status() === 401 || $resp->status() === 403) {
            $resp = self::HttpWithToken('refresh_token')->put(config('apigee.base') . $url, $data);
        }

        self::checkForErrors($resp, $url);

        return $resp;
    }

    public static function delete(string $url)
    {
        $url = self::encodeUrl($url);
        $resp = self::HttpWithToken()->delete(config('apigee.base') . $url);

        if ($resp->status() === 401 || $resp->status() === 403) {
            $resp = self::HttpWithToken('refresh_token')->delete(config('apigee.base') . $url);
        }

        self::checkForErrors($resp, $url);

        return $resp;
    }

    /**
     * @param  string $url
     *
     * @return mixed
     */
    public static function getMint(string $url)
    {
        $url = self::encodeUrl($url);
        $resp = self::HttpWithToken()->get(config('apigee.base_mint') . $url);

        if ($resp->status() === 401 || $resp->status() === 403) {
            $resp = self::HttpWithToken('refresh_token')->get(config('apigee.base_mint') . $url);
        }

        self::checkForErrors($resp, $url);

        return $resp->json();
    }

    protected static function checkForErrors(Response $resp, string $url): void
    {
        if ($resp->failed()) {
            Log::error('Error with Apigee call.', [
                'url' => $url,
                'response' => $resp->json() ?? 'No response'
            ]);
        };
    }

    public static function createApp(array $data, $user = null, $team = null)
    {
        $user ??= auth()->user();

        if ($team) {
            return self::post("companies/{$team->username}/apps", $data);
        }

        return self::post("developers/{$user->email}/apps", $data);
    }

    public static function updateApp(array $data, Team $team = null)
    {
        $user = auth()->user();
        $name = $data['name'];
        $key = $data['key'];
        $apiProducts = $data['apiProducts'];
        $originalProducts = $data['originalProducts'];
        $removedProducts = array_diff($originalProducts, $apiProducts);
        $accessUrl = $team ? "companies/{$team->username}" : "developers/{$user->email}";

        self::post("{$accessUrl}/apps/{$name}/keys/{$key}", ["apiProducts" => $apiProducts]);

        foreach ($removedProducts as $product) {
            self::delete("{$accessUrl}/apps/{$name}/keys/{$key}/apiproducts/{$product}");
        }

        $updatedDetails = self::put("{$accessUrl}/apps/{$name}", [
            "name" => $name,
            "attributes" => $data['attributes'],
            "callbackUrl" => $data['callbackUrl'],
        ]);

        return $updatedDetails;
    }

    public static function updateAppWithNewCredentials(array $data, $entity = null): Response
    {
        $entity ??= auth()->user();
        $name = $data['name'];

        $accessUrl = $entity instanceof Team ? "companies/{$entity->username}" : "developers/{$entity->email}";

        return self::put("{$accessUrl}/apps/{$name}", [
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
        $typeAndEnvironment = explode('-', $type);

        if (count($credentials) === 1) {
            if ($respondWith === 'string') {
                return $credentials[0][$typeAndEnvironment[0]];
            }

            return response()->json([
                'credentials' => $credentials[0][$typeAndEnvironment[0]]
            ]);
        }

        $sandboxedProducts = $app->products->filter(function ($prod) {
            $envArr = explode(',', $prod->environments);
            return in_array('sandbox', $envArr) && !in_array('prod', $envArr);
        })->pluck('name')->toArray();
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
     * @param      \Illuminate\Database\Eloquent\Model         $entity The user or team
     * @param      \App\App                                   $app    The application
     * @param      string                                     $key    The key
     *
     * @return     \Illuminate\Http\Client\Response  The client response
     */
    public static function renewCredentials(Model $entity , App $app, string $key)
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
        ], $entity);

        if ($updatedApp->status() !== 200) return $updatedApp;

        $updatedCredentials = self::sortCredentials($updatedApp['credentials']);
        $updatedCredentials = end($updatedCredentials)['consumerKey'];
        $statusLookup = [
            'approved' => 'approve',
            'revoked' => 'revoke'
        ];

        foreach ($appProducts as $prod) {
            if ($prod->pivot->status === 'pending') continue;

            self::updateProductStatus($entity->email ?? $entity->username, $app->name, $updatedCredentials, $prod->name, $statusLookup[$prod->pivot->status], $entity instanceof Team);
        }

        self::revokeCredentials($entity, $app, $key);

        return $updatedApp;
    }


    /**
     * Revoke credentials
     *
     * @param      \Illuminate\Database\Eloquent\Model       $entity   The user or team 
     * @param      \App\App                                 $app      The application
     * @param      string                                   $key      The key
     *
     * @return     \Illuminate\Http\Client\Response  The client response
     */
    public static function revokeCredentials(Model $entity, App $app, string $key)
    {
        $accessUrl = $entity instanceof Team ? "companies/{$entity->username}" : "developers/{$entity->email}";

        return self::post("{$accessUrl}/apps/{$app->name}/keys/{$key}?action=revoke", [], ['Content-Type' => 'application/octet-stream']);
    }

    public static function getAppAttributes(array $attributes)
    {
        $a = [];
        
        foreach ($attributes as $attribute) {
            $key = $attribute['name'];
            $value = trim($attribute['value']);

            if (!isset($value)) {
                $attribute['value'] = '';
	    
	   }
            $value = $key === 'Group' ? Str::studly($value) : $value;
      
            $a[$key] = $value;
        }

        return $a;
    }

    public static function getProductAttributes(array $attributes)
    {
        $a = [];

        foreach ($attributes as $attribute) {
            $key = Str::studly($attribute['name']);
            $value = trim($attribute['value']);

            if (!isset($value)) {
                $attribute['value'] = '';
	    
	   }
            $value = $key === 'Group' ? Str::studly($value) : $value;
      
            $a[$key] = $value;
        }

        return $a;
    }

    public static function getApigeeAppAttributes(App $app)
    {
        $attr = self::get('apps/' . $app->aid)['attributes'] ?? [];

        return self::getAppAttributes($attr);
    }

    public static function formatAppAttributes(array $attributes)
    {
        $a = [];
        foreach ($attributes as $attribute) {
            if (!isset($attribute['value'])) {
                $attribute['value'] = '';
            }
	   $value = $attribute['name'] === 'Group' ? Str::studly($attribute['value']) : $attribute['value'];
            $a[$attribute['name']] = $value;
        }
        return $a;
    }

    public static function formatToApigeeAttributes(array $attributes)
    {
        $a = [];
        foreach ($attributes as $name => $value) {
            $a[] = ['name' => $name, 'value' => $value];
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

    public static function updateProductStatus(string $id, string $app, string $key, string $product, string $action, bool $isTeam = false)
    {
        $accessUrl = $isTeam ? "companies/{$id}" : "developers/{$id}";

        return self::post("{$accessUrl}/apps/{$app}/keys/{$key}/apiproducts/{$product}?action={$action}", [], ['Content-Type' => 'application/octet-stream']);
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

    // Companies

    /**
     * Creates a company.
     *
     * @param      \App\Company  $company  The company
     *
     * @return     mixed         The response from the post
     */
    public static function createCompany(array $team, User $user)
    {
        return self::post("companies", [
            "name" => $team['username'],
            "displayName" => $team['name'],
            "attributes" => [
                [
                    "name" => "ADMIN_EMAIL",
                    "value" => $user->email
                ], [
                    "name" => "MINT_DEVELOPER_LEGAL_NAME",
                    "value" => $team['name']
                ], [
                    "name" => "MINT_DEVELOPER_ADDRESS",
                    "value" => "{\"address1\":\"{$team['name']}\",\"city\":\"{$team['name']}\",\"country\":\"{$team['country']}\",\"isPrimary\":true,\"state\":\"{$team['name']}\",\"zip\":\"{$team['name']}\"}"
                ],
                [
                    "name" => "MINT_BILLING_TYPE",
                    "value" => "PREPAID"
                ]
            ]
        ]);
    }

    /**
     * Updates a company.
     *
     * @param      \App\Company  $company  The company
     *
     * @return     mixed         The response from the post
     */
    public static function updateCompany(Team $team, ?User $user = null)
    {
        $user ??= $team->owner ?? $team->users->first(fn ($user) => $user->hasRole('team_admin'));

        if (!$user) {
            return null;
        }

        return self::put("companies/{$team->username}", [
            "name" => $team->username,
            "displayName" => $team->name,
            "attributes" => [
                [
                    "name" => "ADMIN_EMAIL",
                    "value" => $user->email
                ], [
                    "name" => "MINT_DEVELOPER_LEGAL_NAME",
                    "value" => $team->name
                ], [
                    "name" => "MINT_DEVELOPER_ADDRESS",
                    "value" => "{\"address1\":\"{$team['name']}\",\"city\":\"{$team['name']}\",\"country\":\"{$team['country']}\",\"isPrimary\":true,\"state\":\"{$team['state']}\",\"zip\":\"{$team['zip']}\"}"
                ],
                [
                    "name" => "MINT_BILLING_TYPE",
                    "value" => $team->billing_type ?? "PREPAID"
                ]
            ]
        ]);
    }

    /**
     * Delete a company.
  *
     * @param      \App\Company  $company  The company
     * @param      \App\User     $user     The user
     *
     * @return     mixed         The response from the delete

     */
    public function deleteCompany(Team $team, ?User $user = null){
        $user ??= $team->owner ?? $team->users->first(fn ($user) => $user->hasRole('team_admin'));

        if (!$user) {
            return null;
        }

        return self::delete("companies/{$team->name}");
    }

    /**
     * Adds a developer to company.
     *
     * @param      \App\Company  $company  The company
     * @param      \App\User     $user     The user
     * @param      string        $role     The role
     *
     * @return     mixed         The response from the post
     */
    public static function addDeveloperToCompany(Team $team, User $user, string $role)
    {
        return self::post("companies/{$team->username}/developers", [
            "developer" => [
                [
                    "email" => $user->email,
                    "role" => $role
                ]
            ]
        ]);
    }

    /**
     * Adds a developer to company.
     *
     * @param      \App\Company  $company  The company
     * @param      \App\User     $user     The user
     *
     * @return     mixed         The response from the post
     */
    public static function removeDeveloperFromCompany(Team $team, User $user)
    {
        return self::delete("companies/{$team->username}/developers/{$user->email}");
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
        $accessUrl = !is_null($app->team) ? "companies/{$app->team->username}" : "developers/{$app->developer->email}";
        $action = [
            'approved' => 'approve',
            'revoked' => 'revoke'
        ][$status] ?? 'approve';

        self::post("{$accessUrl}/apps/{$app->name}?action={$action}", [], ['Content-Type' => 'application/octet-stream']);

        return self::put("{$accessUrl}/apps/{$app->name}", [
            "name" => $app->name,
            "attributes" => $attributes,
            "callbackUrl" => $app->callbackUrl ?? '',
        ]);
    }
}
