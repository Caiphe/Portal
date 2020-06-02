<?php

namespace App;

use App\App;
use App\Content;
use App\Category;
use App\KeyFeature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model {
	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = [];
	protected $primaryKey = "pid";
	public $incrementing = false;

	public function setNameAttribute($value) {
		$this->attributes['name'] = $value;
		$this->attributes['slug'] = Str::slug($value);
	}

	public function setCategoryAttribute($value) {
		$this->attributes['category'] = $value;
		$this->attributes['category_slug'] = Str::slug($value);
	}

	public function scopeHasSwagger($query) {
		return $query->whereNotNull('swagger');
	}

	public function scopeIsPublic($query) {
		return $query->hasSwagger()->whereAccess("public");
	}

	public function scopeHasCategory($query, $category)
	{
		return $query->isPublic()->where('category_slug', $category);
	}

	public function scopeIsPublicWithInternal($query) {
		return $query->hasSwagger()->where(function ($query) {
			$query->where('access', 'public')
				->orWhere('access', 'internal');
		});
	}

	public function scopeBasedOnUser($query, $user) {
		if ($user && $user->hasPermissionTo('view_internal_products')) {
			return $query->isPublicWithInternal();
		}

		return $query->isPublic();
	}

	public function scopeGetEnvironment($query, $environment) {
		return $query
			->isPublic()
			->hasSwagger()
			->whereRaw("find_in_set('$environment',environments)");
	}

	public function content() {
		return $this->belongsToMany(Content::class, "content_product", "product_pid");
	}

	public function apps() {
		return $this->belongsToMany(App::class, "app_product", "product_pid", "app_aid")->withPivot('status');
	}

	public function keyFeatures() {
		return $this->belongsToMany(KeyFeature::class, "key_feature_product", "product_pid", "key_feature_id");
	}

	public function category()
	{
		return $this->belongsTo(Category::class);
	}
}
