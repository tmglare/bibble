<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model {
	use HasFactory;

	protected $guarded = array(
		"id",
		"created_at",
		"updated_at"
	);

	protected $fillable = array(
		"name"
	);

	protected $casts = array(
		"name" => "string"
	);
}
