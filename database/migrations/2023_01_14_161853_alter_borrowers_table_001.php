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
			$table->char("barcode",10)->nullable(true)->comment("Barcode");

			$table->unique("barcode");
		});
	}

	/**
	* Reverse the migrations.
	*
	* @return void
	*/
	public function down() {
		Schema::table('borrowers', function (Blueprint $table) {
			$table->dropUnique("barcode");
			$table->dropColumn("barcode");
		});
	}
};
