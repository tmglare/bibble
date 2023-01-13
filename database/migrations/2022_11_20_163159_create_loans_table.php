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
		Schema::create('loans', function (Blueprint $table) {
			$table->id();
			$table->timestamps();

			$table->bigInteger("borrower_id")->unsigned()->nullable(false)->comment("Borrower ID");
			$table->bigInteger("inventory_item_id")->unsigned()->nullable(false)->comment("Inventory Item ID");

			$table->date("borrowed_on")->nullable(false)->comment("Borrowed on");
			$table->date("due_back")->nullable(false)->comment("Due back");
			$table->date("returned_on")->nullable(true)->comment("Returned on");

			$table->foreign("borrower_id")->references("id")->on("borrowers");
			$table->foreign("inventory_item_id")->references("id")->on("inventory_items");

			$table->index("borrowed_on");
			$table->index("due_back");
			$table->index("returned_on");
		});
	}

	/**
	* Reverse the migrations.
	*
	* @return void
	*/
	public function down() {
		Schema::dropIfExists('loans');
	}
};
