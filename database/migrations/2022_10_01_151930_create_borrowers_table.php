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
		Schema::create('borrowers', function (Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->softDeletes();

			$table->string("name",80)->nullable(false)->comment("Name");
			$table->string("style",20)->nullable(true)->comment("Style/title");
			$table->text("street")->nullable(true)->comment("Number/street/district");
			$table->string("town",40)->nullable(false)->comment("Town");
			$table->string("postcode",40)->nullable(false)->comment("Postcode");
			$table->string("telephone",40)->nullable(true)->comment("Telephone number");
			$table->string("email",80)->nullable(true)->comment("Email address");

			$table->index("name");
			$table->index("town");
			$table->index("postcode");
		});
	}

	/**
	* Reverse the migrations.
	*
	* @return void
	*/
	public function down() {
		Schema::dropIfExists('borrowers');
	}
};
