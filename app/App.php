<?php

namespace App;

use App\Country;
use App\Services\ApigeeService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class App extends Model
{

    use SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $primaryKey = "aid";
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'aid' => 'string',
        'attributes' => 'array',
        'credentials' => 'array',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function setCredentialsAttribute($credentials)
    {
        $sandboxedProducts = Product::where('environments', 'like', 'sandbox')->pluck('name')->toArray();
        $apigeeCredentials = ApigeeService::sortCredentials($credentials);
        $creds = [];
        $credentials = [
            'prod' => [],
            'sandbox' => [],
        ];

        for ($i = 0; $i < count($apigeeCredentials); $i++) {
            if ($apigeeCredentials[$i]['status'] === 'revoked') continue;

            $apigeeCredentials[$i]['apiProducts'] = array_map(fn ($apiProduct) => $apiProduct['apiproduct'], $apigeeCredentials[$i]['apiProducts']);
            $apigeeCredentials[$i]['consumerKey'] = $this->redact($apigeeCredentials[$i]['consumerKey']);
            $apigeeCredentials[$i]['consumerSecret'] = $this->redact($apigeeCredentials[$i]['consumerSecret']);
            $apigeeCredentials[$i]['environment'] = count(array_intersect($sandboxedProducts, $apigeeCredentials[$i]['apiProducts'])) > 0 ? 'sandbox' : 'prod';
            $credentials[$apigeeCredentials[$i]['environment']][] = $apigeeCredentials[$i];
        }


        if (count($credentials['sandbox']) > 0) {
            $creds[] = end($credentials['sandbox']) ?: [];
        }

        if (count($credentials['prod']) > 0) {
            $creds[] = end($credentials['prod']) ?: [];
        }

        $this->attributes['credentials'] = json_encode($creds);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->whereStatus($status);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, "app_product", "app_aid", "product_pid")->withPivot('status', 'actioned_at', 'status_note');
    }

    public function scopeByUserEmail($query, $email)
    {
        return $query->whereHas('developer', function ($q) use ($email) {
            $q->whereEmail($email);
        });
    }

    public function developer()
    {
        return $this->hasOne(User::class, "developer_id", "developer_id") ?? '';
    }

    public function country()
    {
        return $this->hasOne(Country::class, "code", "country_code");
    }

    public function team()
    {
        return $this->hasOne(Team::class, "id", "team_id");
    }

    /**
     * Redact parts of the keys
     *
     * @param      string  $key    The key
     *
     * @return     string  The redacted key
     */
    public function redact(string $key): string
    {
        $len = strlen($key) - 8;
        return substr($key, 0, 4) . implode('', array_fill(0, $len, 'X')) . substr($key, -4);
    }

    /**
     * Gets the products by credentials. Checks if there are sandbox products.
     *
     * @return     arrray  The products by credentials seperated by sandbox and prod environments.
     */
    public function getProductsByCredentials(): array
    {
        $credentials = $this->credentials;
        $firstProducts = [
            'credentials' => $credentials[0],
            'products' => $credentials[0]['apiProducts'],
        ];
        $lastProducts = [
            'credentials' => [],
            'products' => [],
        ];

        $isFirstProductSandbox = $firstProducts['credentials']['environment'] === 'sandbox';

        if (count($credentials) > 1) {
            $lastProducts['credentials'] = end($credentials);
            $lastProducts['products'] = $this->products->filter(fn ($prod) => in_array($prod->name, $lastProducts['credentials']['apiProducts']));
        }

        $firstProducts['products'] = $this->products->filter(fn ($prod) => in_array($prod->name, $firstProducts['products']));

        if (!$isFirstProductSandbox) {
            $lastProducts = $firstProducts;
            $firstProducts = [];
        }

        return [$firstProducts, $lastProducts];
    }

    public function getNotesAttribute()
    {
        $notes = $this['attributes']['Notes'] ?? 'No notes at the moment';
        if ($notes === 'No notes at the moment') return $notes;

        $notes = str_replace("\n", "<br>", $notes);
        $notes = preg_replace('/\b(\d\d [a-zA-Z]+ \d\d\d\d)\b/', '<strong>$1</strong>', $notes);
        $notes = preg_replace('/\b(Notes)\b/', '<strong>$1</strong>', $notes);

        return $notes;
    }

    public function getProductStatusAttribute()
    {
        $pending = $this->products->filter(fn ($prod) => $prod->pivot->status === 'pending')->count();

        if ($pending > 0) {
            return ['status' => 'pending', 'label' => $pending . ' Pending products', 'pending' => $pending];
        }

        return ['status' => $this->status, 'label' => ucfirst($this->status), 'pending' => 0];
    }

    public function getEmails(): array
    {
        if (!is_null($this->team_id)) {
            return $this->team->users->pluck('email')->toArray();
        }

        return [$this->developer->email];
    }

    public function getEntity(): Model
    {
        if (!is_null($this->team_id)) {
            return $this->team;
        }

        return $this->developer;
    }

    public function getCustomAttributesAttribute()
    {
        return $this->filterCustomAttributes($this->attributes['attributes']);
    }

    public function filterCustomAttributes($attributes){
        if(is_string($attributes)){
            $attributes = json_decode($attributes, true);
        }
        return array_diff_ukey(
            $attributes, 
            array_flip(['Location', 'Country', 'TeamName', 'Description', 'DisplayName', 'Notes']), 
            fn($a, $b) => strtolower($a) <=> strtolower($b)
        );
    }
}
