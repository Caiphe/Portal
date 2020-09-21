<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountryProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('country_product', function (Blueprint $table) {
            $table->primary(['country_code', 'product_pid']);
            $table->string('country_code');
            $table->string('product_pid');

            $table->foreign('country_code')
                ->references('code')
                ->on('countries')
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
        Schema::table('country_product', function (Blueprint $table) {
            $table->dropForeign('country_product_country_code_foreign');
            $table->dropForeign('country_product_product_pid_foreign');
        });

        Schema::dropIfExists('country_product');
    }
}
