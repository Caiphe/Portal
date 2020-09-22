<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountryOpcoTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('country_opco', function (Blueprint $table) {
			$table->primary(['country_code', 'user_id']);

			$table->string('country_code');
            $table->unsignedBigInteger('user_id');

			$table->foreign('country_code')
                ->references('code')
                ->on('countries')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('country_opco', function (Blueprint $table) {
			$table->dropForeign('country_opco_country_code_foreign');
			$table->dropForeign('country_opco_user_id_foreign');
		});
		Schema::dropIfExists('country_opco');
	}
}
