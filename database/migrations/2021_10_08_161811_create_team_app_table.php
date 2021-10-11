<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamAppTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_app', function (Blueprint $table) {
            $table->primary(['team_id', 'app_aid']);

            $table->string('team_id');
            $table->string('app_aid');
            $table->string('status')->default('pending');
            $table->string('created_by')->nullable();

            $table->foreign('app_aid')
                ->references('aid')
                ->on('apps')
                ->onDelete('cascade');

            $table->foreign('team_id')
                ->references('id')
                ->on('teams')
                ->onDelete('cascade');

            $table->foreign('created_by')
                ->references('email')
                ->on('users')
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
        Schema::table('team_app', function (Blueprint $table) {
            $table->dropForeign('team_id_app_aid_foreign');
        });

        Schema::dropIfExists('team_app');
    }
}
