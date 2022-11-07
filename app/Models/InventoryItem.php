<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryItem extends Model {
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
		"book_id",
		"copy_no",
		"notes"
	);

	protected $casts = array(
		"copy_no" => "integer",
		"notes"   => "string"
	);

	public function book() {
		return $this->belongsTo("\App\Models\Book");
	}
}
