<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model {
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
		"name",
		"ordered_name"
	);

	protected $casts = array(
		"name" => "string",
		"ordered_name" => "string",
		"deleted_at" => "datetime"
	);

	public function books() {
		return $this->hasMany("\App\Models\Book");
	}

	public function setOrderedNameAttribute($value) {
		if ($value) {
			$this->attributes["ordered_name"] = $value;
		} else {
			$this->attributes["ordered_name"] = "";
		}
	}
}
