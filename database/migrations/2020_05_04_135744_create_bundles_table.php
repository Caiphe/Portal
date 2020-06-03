<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBundlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bundles', function (Blueprint $table) {
            $table->primary('bid');
            $table->string('bid')->index();
            $table->string('name');
            $table->string('slug');
            $table->string('display_name');
            $table->text('description');
            $table->string('category_cid')->default('misc');
            $table->string('banner')->default('/images/banner-default.png');
            $table->timestamps();

            $table->foreign('category_cid')
                ->references('cid')
                ->on('categories')
                ->onDelete('cascade');
        });

        Schema::create('bundle_product', function (Blueprint $table) {
            $table->primary(['bundle_bid', 'product_pid']);

            $table->string('bundle_bid');
            $table->string('product_pid');

            $table->foreign('bundle_bid')
                ->references('bid')
                ->on('bundles')
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
    public function down()
    {
        Schema::table('bundles', function (Blueprint $table) {
            $table->dropForeign(['category_cid']);
        });

        Schema::table('bundle_product', function (Blueprint $table) {
            $table->dropForeign('bundle_product_bundle_bid_foreign');
            $table->dropForeign('bundle_product_product_pid_foreign');
        });

        Schema::dropIfExists('bundle_product');
        Schema::dropIfExists('bundles');
    }
}
