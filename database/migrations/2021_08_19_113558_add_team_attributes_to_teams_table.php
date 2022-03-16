<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTeamAttributesToTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->unique('name');
            $table->string('url')->nullable();
            $table->string('contact')->nullable();
            $table->string('country');
            $table->string('logo');
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropUnique('name');
            $table->dropColumn('url');
            $table->dropColumn('contact');
            $table->dropColumn('country');
            $table->dropColumn('logo');
            $table->dropColumn('description');
        });
    }
}
