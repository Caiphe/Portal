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
            $table->unsignedBigInteger('category_id')->default(1);
            $table->string('banner')->default('/images/banner-default.png');
            $table->timestamps();

            $table->foreign('category_id')
                ->references('id')
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

        Schema::create('bundle_content', function (Blueprint $table) {
            $table->primary(['bundle_bid', 'content_id']);

            $table->string('bundle_bid');
            $table->unsignedBigInteger('content_id');

            $table->foreign('bundle_bid')
                ->references('bid')
                ->on('bundles')
                ->onDelete('cascade');

            $table->foreign('content_id')
                ->references('id')
                ->on('contents')
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
            $table->dropForeign(['category_id']);
        });

        Schema::table('bundle_product', function (Blueprint $table) {
            $table->dropForeign('bundle_product_bundle_bid_foreign');
            $table->dropForeign('bundle_product_product_pid_foreign');
        });

        Schema::table('bundle_content', function (Blueprint $table) {
            $table->dropForeign('bundle_content_content_id_foreign');
            $table->dropForeign('bundle_content_bundle_bid_foreign');
        });

        Schema::dropIfExists('bundle_product');
        Schema::dropIfExists('bundle_content');
        Schema::dropIfExists('bundles');
    }
}
