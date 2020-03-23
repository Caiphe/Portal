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
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('display_name')->nullable();
            $table->text('description')->nullable();
            $table->string('environments')->nullable();
            $table->string('group')->nullable();
            $table->string('category')->nullable();
            $table->string('access')->nullable();
            $table->string('locations')->nullable();
            $table->string('swagger')->nullable();
            $table->timestamps();
        });
        
        Schema::create('content_product', function (Blueprint $table) {
            $table->primary(['content_id', 'product_id']);

            $table->foreignId('content_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('product_id')
                ->constrained()
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
        Schema::table('content_product', function (Blueprint $table) {
            $table->dropForeign(['content_id', 'product_id']);
        });

        Schema::dropIfExists('products');
    }
}
