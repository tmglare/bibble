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
		Schema::create('authors', function (Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->string("name",80)->nullable(false)->comment("Name");

			$table->unique("name");
		});
	}

	/**
	* Reverse the migrations.
	*
	* @return void
	*/
	public function down() {
		Schema::dropIfExists('authors');
	}
};
