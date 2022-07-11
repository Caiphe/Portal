<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpcoRoleRequestActionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opco_role_request_action', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('opco_role_requests')->onDelete('CASCADE');
            $table->string('approved_by')->constrained('users')->onDelete('CASCADE');
            $table->boolean('approved')->default(0);
            $table->string('message');
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
        Schema::table('opco_role_request_action', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['request_id']);
        });

        Schema::dropIfExists('opco_role_request_action');
    }
}
