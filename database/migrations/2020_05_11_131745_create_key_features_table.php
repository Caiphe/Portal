<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeyFeaturesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('key_features', function (Blueprint $table) {
			$table->id();
			$table->string('title');
			$table->string('description');
			$table->timestamps();
		});

		Schema::create('key_feature_product', function (Blueprint $table) {
			$table->primary(['key_feature_id', 'product_pid']);

			$table->unsignedBigInteger('key_feature_id');
			$table->string('product_pid');

			$table->foreign('key_feature_id')
				->references('id')
				->on('key_features')
				->onDelete('cascade');

			$table->foreign('product_pid')
				->references('pid')
				->on('products')
				->onDelete('cascade');
		});

		Schema::create('bundle_key_feature', function (Blueprint $table) {
			$table->primary(['bundle_bid', 'key_feature_id']);

			$table->string('bundle_bid');
			$table->unsignedBigInteger('key_feature_id');

			$table->foreign('bundle_bid')
				->references('bid')
				->on('bundles')
				->onDelete('cascade');

			$table->foreign('key_feature_id')
				->references('id')
				->on('key_features')
				->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('key_feature_product', function (Blueprint $table) {
			$table->dropForeign('key_feature_product_key_feature_id_foreign');
			$table->dropForeign('key_feature_product_product_pid_foreign');
		});

		// Schema::table('bundle_key_feature', function (Blueprint $table) {
		// 	$table->dropForeign('bundle_key_feature_key_feature_id_foreign');
		// 	$table->dropForeign('bundle_key_feature_bundle_bid_foreign');
		// });

		// Schema::dropIfExists('key_feature_product');
		// Schema::dropIfExists('bundle_key_feature');
		Schema::dropIfExists('key_features');
	}
}
