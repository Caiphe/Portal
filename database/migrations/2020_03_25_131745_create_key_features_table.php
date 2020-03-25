<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeyFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('key_features', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('key_feature_product', function(Blueprint $table){
            $table->primary(['key_feature_id', 'product_id']);

            $table->foreignId('key_feature_id')
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
        Schema::table('key_feature_product', function (Blueprint $table) {
            $table->dropForeign(['key_feature_id', 'product_id']);
        });
        
        Schema::dropIfExists('key_features');
    }
}
