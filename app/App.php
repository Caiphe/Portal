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
        $credentials = ApigeeService::sortCredentials($credentials);
        for ($i = 0; $i < count($credentials); $i++) {
            $credentials[$i]['apiProducts'] = array_map(fn ($apiProduct) => $apiProduct['apiproduct'], $credentials[$i]['apiProducts']);
            $credentials[$i]['consumerKey'] = $this->redact($credentials[$i]['consumerKey']);
            $credentials[$i]['consumerSecret'] = $this->redact($credentials[$i]['consumerSecret']);
        }
        $cred = json_encode($credentials);
        $this->attributes['credentials'] = $cred;
    }

    public function scopeByStatus($query, $status)
    {
        return $query->whereStatus($status);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, "app_product", "app_aid", "product_pid")->withPivot('status', 'actioned_at');
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
    protected function redact(string $key): string
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
            'products' => [],
            'hasKyc' => false
        ];
        $isFirstProductSandbox = false;

        foreach ($this->products as $product) {
            if (!in_array($product->name, $credentials[0]['apiProducts'])) continue;

            if (!$isFirstProductSandbox && strpos($product->environments, 'sandbox') !== false) {
                $isFirstProductSandbox = true;
            }

            if (!$firstProducts['hasKyc'] && $product->group !== 'MTN') {
                $firstProducts['hasKyc'] = true;
            }

            $firstProducts['products'][] = $product;
        }
        $lastProducts = $isFirstProductSandbox ? [] : $firstProducts;
        $firstProducts = $isFirstProductSandbox ? $firstProducts : [];

        if (count($credentials) > 1) {
            $hasKyc = false;
            $lastProducts = collect();
            foreach ($this->products as $prod) {
                if (in_array($prod->name, end($credentials)['apiProducts'])) {
                    $lastProducts->push($prod);
                }

                if (!$hasKyc && $prod->group !== 'MTN') {
                    $hasKyc = true;
                }
            }
            $lastProducts = [
                'credentials' => end($credentials),
                'products' => $lastProducts,
                'hasKyc' => $hasKyc
            ];
        }

        if (empty($lastProducts)) {
            $lastProducts = [
                'credentials' => [],
                'products' => [],
                'hasKyc' => false
            ];
        }

        return [$firstProducts, $lastProducts];
    }
}
