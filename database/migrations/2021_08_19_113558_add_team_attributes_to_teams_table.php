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
            $table->string('name');
            $table->string('url');
            $table->string('contact');
            $table->string('country');
            $table->string('logo');
            $table->text('description');
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
            $table->dropColumn('name');
            $table->dropColumn('url');
            $table->dropColumn('contact');
            $table->dropColumn('country');
            $table->dropColumn('logo');
            $table->dropColumn('description');
        });
    }
}
