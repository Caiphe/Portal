<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('products', function (Blueprint $table) {
			$table->primary('pid');
			$table->string('pid')->index();
			$table->string('name');
			$table->string('slug');
			$table->string('display_name')->nullable();
			$table->text('description')->nullable();
			$table->string('environments')->nullable();
			$table->string('group')->nullable();
			$table->string('category')->nullable();
			$table->string('category_slug')->nullable();
			$table->string('access')->nullable();
			$table->string('locations')->nullable();
			$table->string('swagger')->nullable();
			$table->json('attributes')->nullable();
			$table->timestamps();
		});

		Schema::create('content_product', function (Blueprint $table) {
			$table->primary(['content_id', 'product_pid']);

			$table->unsignedBigInteger('content_id');
			$table->string('product_pid');

			$table->foreign('content_id')
				->references('id')
				->on('contents')
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
		Schema::table('content_product', function (Blueprint $table) {
			$table->dropForeign('content_product_content_id_foreign');
			$table->dropForeign('content_product_product_pid_foreign');
		});

		Schema::dropIfExists('products');
	}
}
