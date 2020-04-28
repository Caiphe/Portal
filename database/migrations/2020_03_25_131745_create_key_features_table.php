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

		Schema::dropIfExists('key_features');
	}
}
