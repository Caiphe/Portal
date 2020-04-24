<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class App extends Model {
	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = [];
	protected $primaryKey = "aid";
	public $incrementing = false;

	public function setNameAttribute($value) {
		$this->attributes['name'] = $value;
		$this->attributes['slug'] = Str::slug($value);
	}

	public function products() {
		return $this->belongsToMany(Product::class, "app_product", "app_aid", "product_pid");
	}

	public function developer() {
		return $this->hasOne(User::class, "developer_id", "developer_id");
	}
}
