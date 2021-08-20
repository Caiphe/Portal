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

        
        if(count($credentials['sandbox']) > 0){
            $creds[] = end($credentials['sandbox']) ?: [];
        }
        
        if(count($credentials['prod']) > 0){
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
        return $this->hasOne(User::class, "developer_id", "developer_id");
    }

    public function country()
    {
        return $this->hasOne(Country::class, "code", "country_code");
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
}
