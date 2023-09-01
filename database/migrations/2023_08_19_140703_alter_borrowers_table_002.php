<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	* Run the migrations.
	*
	* @return void
	*/
	public function up() {
		Schema::table('borrowers', function (Blueprint $table) {
			$table->string("forenames",80)->nullable(false)->default("")->comment("Forenames");
			$table->string("surname",80)->nullable(false)->default("")->comment("Surname");
			$table->string("name",80)->nullable(true)->comment("Name")->change();

			$table->index("surname");
		});
	}

	/**
	* Reverse the migrations.
	*
	* @return void
	*/
	public function down() {
		Schema::table('borrowers', function (Blueprint $table) {
			$table->dropColumn("forenames");
			$table->dropColumn("surname");
			$table->string("name",80)->nullable(false)->default("")->comment("Name")->change();
		});
	}
};
