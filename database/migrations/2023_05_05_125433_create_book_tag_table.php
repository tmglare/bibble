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
		Schema::create('book_tag', function (Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->bigInteger("book_id")->unsigned()->nullable(false)->comment("Book ID");
			$table->bigInteger("tag_id")->unsigned()->nullable(false)->comment("Tag ID");

			$table->foreign("book_id")->references("id")->on("books");
			$table->foreign("tag_id")->references("id")->on("tags");
		});
	}

	/**
	* Reverse the migrations.
	*
	* @return void
	*/
	public function down() {
		Schema::dropIfExists('book_tag');
	}
};
