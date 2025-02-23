<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('roles', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('name');
			$table->string('label');
		});

		Schema::create('permissions', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('name');
			$table->string('label');
		});

		Schema::create('role_user', function (Blueprint $table) {
			$table->primary(['role_id', 'user_id']);

			$table->unsignedBigInteger('role_id');
			$table->unsignedBigInteger('user_id');

			$table->foreign('role_id')
				->references('id')
				->on('roles')
				->onDelete('cascade');

			$table->foreign('user_id')
				->references('id')
				->on('users')
				->onDelete('cascade');
		});

		Schema::create('permission_role', function (Blueprint $table) {
			$table->primary(['permission_id', 'role_id']);

			$table->unsignedBigInteger('permission_id');
			$table->unsignedBigInteger('role_id');

			$table->foreign('permission_id')
				->references('id')
				->on('permissions')
				->onDelete('cascade');

			$table->foreign('role_id')
				->references('id')
				->on('roles')
				->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('role_user', function (Blueprint $table) {
			$table->dropForeign('role_user_role_id_foreign');
			$table->dropForeign('role_user_user_id_foreign');
		});

		Schema::table('permission_role', function (Blueprint $table) {
			$table->dropForeign('permission_role_permission_role_foreign');
			$table->dropForeign('permission_role_permission_role_foreign');
		});

		Schema::dropIfExists('roles');
	}
}
