<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDeletionRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_deletion_requests', function (Blueprint $table) {
            $table->id();
            $table->string('countries')->nullable();
            $table->string('request_by');
            $table->foreignId('user_id')->constrained('users')->onDelete('CASCADE');
            $table->string('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->timestamps();
        });
    }

       /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_deletion_requests', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('user_deletion_requests');
    }
}
