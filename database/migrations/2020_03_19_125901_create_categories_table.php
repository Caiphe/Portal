<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->primary('cid');
            $table->string('cid')->index();
            $table->string('title');
            $table->string('slug');
            $table->string('theme')->default('mixed');
        });

        \DB::insert('insert into categories (cid, title, slug) values (?, ?, ?)', ['misc', 'Misc', 'misc']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
