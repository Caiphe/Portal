<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->foreign('user_id')->constrained('users')->nullable();
            $table->string('logable_id')->nullable();
            $table->string('logable_type')->nullable();
            $table->longText('message')->nullable();
            $table->timestamps();
        });
    }

    // user_id, logable_id, logable_type, message

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('logs');
    }
}
