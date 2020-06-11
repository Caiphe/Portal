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
			$table->primary(['country_id', 'user_id']);
			$table->foreignId('country_id')->constrained();
			$table->foreignId('user_id')->constrained();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('country_opco', function (Blueprint $table) {
			$table->dropForeign('country_opco_country_id_foreign');
			$table->dropForeign('country_opco_user_id_foreign');
		});
		Schema::dropIfExists('country_opco');
	}
}
