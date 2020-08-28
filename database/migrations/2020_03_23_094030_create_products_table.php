<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products', function (Blueprint $table) {
			$table->primary('pid');
			$table->string('pid')->index();
			$table->string('name');
			$table->string('slug');
			$table->string('display_name')->nullable();
			$table->text('description')->nullable();
			$table->string('environments')->nullable();
			$table->string('group')->default('MTN');
            $table->string('category_cid')->default('misc');
			$table->string('access')->nullable();
			$table->string('locations')->nullable();
			$table->string('swagger')->nullable();
			$table->json('attributes')->nullable();
			$table->timestamps();
			$table->softDeletes('deleted_at', 0);

			$table->foreign('category_cid')
                ->references('cid')
                ->on('categories')
                ->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('products', function (Blueprint $table) {
			$table->dropForeign(['category_cid']);
		});

		Schema::dropIfExists('products');
	}
}
