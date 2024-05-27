<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model {
	use HasFactory;
	use SoftDeletes;

	// protected $dates = array(
		// "deleted_at"
	// );

	protected $guarded = array(
		"id",
		"created_at",
		"updated_at",
		"deleted_at"
	);

	protected $fillable = array(
		"title",
		"author_id",
		"detailed_category_id",
		"isbn",
		"publisher",
		"edition",
		"first_publication_date",
		"edition_date"
	);

	protected $casts = array(
		"title"                  => "string",
		"isbn"                   => "string",
		"publisher"              => "string",
		"edition"                => "string",
		"first_publication_date" => "string",
		"edition_date"           => "string",
		"deleted_at"             => "datetime"
	);

	public function author() {
		return $this->belongsTo("\App\Models\Author")->withTrashed();
	}

	public function detailedCategory() {
		return $this->belongsTo("\App\Models\DetailedCategory");
	}

	public function inventoryItems() {
		return $this->hasMany("\App\Models\InventoryItem");
	}

	public function tags() {
		return $this->belongsToMany("App\Models\Tag")->withTimestamps();
	}
}
