<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model {
	use HasFactory;
	use SoftDeletes;

	protected $dates = array(
		"deleted_at"
	);

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
		"name" => "string"
	);
}
