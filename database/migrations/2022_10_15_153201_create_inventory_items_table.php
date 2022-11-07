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
		Schema::create('inventory_items', function (Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->softDeletes();

			$table->bigInteger("book_id")->unsigned()->nullable(false)->comment("Book ID");
			$table->smallInteger("copy_no")->unsigned()->nullable(false)->default(1)->comment("Copy no");
			$table->text("notes")->comment("Notes");

			$table->unique(array("book_id","copy_no"));
			$table->foreign("book_id")->references("id")->on("books");
		});
	}

	/**
	* Reverse the migrations.
	*
	* @return void
	*/
	public function down() {
		Schema::dropIfExists('inventory_items');
	}
};
