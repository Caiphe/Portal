<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUserDeletionRequestsTableChangeColumnUserEmail extends Migration
{
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_deletion_requests', function (Blueprint $table) {
            $table->string('user_email')->change();
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
            $table->dropColumn('user_email')->change();
        });
    }
}
