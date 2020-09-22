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
			$table->string('display_name');
			$table->string('slug');
			$table->string('callback_url')->nullable();
			$table->json('attributes');
			$table->json('credentials');
			$table->string('developer_id');
			$table->string('status');
			$table->text('description');
			$table->string('country_code')->nullable();
			$table->timestamps();
		});

		Schema::create('app_product', function (Blueprint $table) {
			$table->primary(['app_aid', 'product_pid']);

			$table->string('app_aid');
			$table->string('product_pid');
			$table->string('status')->default('pending');
			$table->unsignedBigInteger('actioned_by')->nullable();

			$table->foreign('app_aid')
				->references('aid')
				->on('apps')
				->onDelete('cascade');

			$table->foreign('product_pid')
				->references('pid')
				->on('products')
				->onDelete('cascade');

			$table->foreign('actioned_by')
				->references('id')
				->on('users')
				->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('app_product', function (Blueprint $table) {
			$table->dropForeign('app_product_app_aid_foreign');
			$table->dropForeign('app_product_product_pid_foreign');
		});

		Schema::dropIfExists('apps');
	}
}
