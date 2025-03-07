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
		Schema::create('tags', function (Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->string("name",40)->nullable(false)->comment("Tag");
			$table->unique("name");
		});
	}

	/**
	* Reverse the migrations.
	*
	* @return void
	*/
	public function down() {
		Schema::dropIfExists('tags');
	}
};
