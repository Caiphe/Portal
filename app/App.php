<?php

namespace App;

use App\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class App extends Model {
	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = [];
	protected $primaryKey = "aid";
	public $incrementing = false;

	/**
	 * The attributes that should be cast.
	 *
	 * @var array
	 */
	protected $casts = [
		'attributes' => 'array',
		'credentials' => 'array',
	];

	public function setNameAttribute($value) {
		$this->attributes['name'] = $value;
		$this->attributes['slug'] = Str::slug($value);
	}

	public function scopeByStatus($query, $status) {
		return $query->whereStatus($status);
	}

	public function products() {
		return $this->belongsToMany(Product::class, "app_product", "app_aid", "product_pid")->withPivot('status');
	}

	public function scopeByUserEmail($query, $email) {
		return $query->whereHas('developer', function ($q) use ($email) {
			$q->whereEmail($email);
		});
	}

	public function developer() {
		return $this->hasOne(User::class, "developer_id", "developer_id");
	}

	public function country() {
		return $this->hasOne(Country::class, "id", "country_id");
	}
}
