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
		Schema::create('books', function (Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->softDeletes();
			$table->string("title",80)->nullable(false)->comment("Title");
			$table->bigInteger("author_id")->unsigned()->nullable(false)->comment("Author link");
			$table->bigInteger("detailed_category_id")->unsigned()->nullable(false)->comment("Detailed category link");
			$table->string("isbn")->nullable(false)->comment("ISBN number");
			$table->string("publisher",80)->nullable(false)->comment("Publisher");
			$table->string("first_publication_date",40)->nullable(true)->comment("First publication date");
			$table->string("edition_date",40)->nullable(true)->comment("Edition date");
			$table->string("edition",40)->nullable(true)->comment("Edition");

			$table->index("title");
			$table->index("publisher");
			$table->unique("isbn");
			$table->foreign("author_id")->references("id")->on("authors");
			$table->foreign("detailed_category_id")->references("id")->on("detailed_categories");
		});
	}

	/**
	* Reverse the migrations.
	*
	* @return void
	*/
	public function down() {
		Schema::dropIfExists('books');
	}
};
