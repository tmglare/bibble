<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model {
	use HasFactory;

	protected $guarded = array(
		"id",
		"created_at",
		"updated_at",
		"deleted_at"
	);

	protected $fillable = array(
		"name"
	);

	protected $casts = array(
		"name" => "string",
		"deleted_at" => "datetime"
	);

	public function books() {
		return $this->belongsToMany("App\Models\Book")->withTimestamps();
	}
}
