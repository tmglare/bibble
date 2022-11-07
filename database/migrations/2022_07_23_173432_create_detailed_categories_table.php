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
		Schema::create('detailed_categories', function (Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->softDeletes();
			$table->string("name",40)->nullable(false)->comment("Name");
			$table->bigInteger("general_category_id")->unsigned()->nullable(false)->comment("General category link");

			$table->unique("name");
			$table->foreign("general_category_id")->references("id")->on("general_categories");
		});
	}

	/**
	* Reverse the migrations.
	*
	* @return void
	*/
	public function down() {
		Schema::dropIfExists('detailed_categories');
	}
};
