<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('apps', function (Blueprint $table) {
			$table->primary('aid');
			$table->string('aid')->index();
			$table->string('name');
			$table->string('slug');
			$table->json('attributes');
			$table->string('developer_id');
			$table->string('status');
			$table->timestamps();
		});

		Schema::create('app_product', function (Blueprint $table) {
			$table->primary(['app_aid', 'product_pid']);

			$table->string('app_aid');
			$table->string('product_pid');
			$table->string('status');

			$table->foreign('app_aid')
				->references('aid')
				->on('apps')
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
		Schema::dropIfExists('apps');
	}
}
