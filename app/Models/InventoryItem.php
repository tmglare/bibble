<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryItem extends Model {
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
		"book_id",
		"copy_no",
		"notes",
		"barcode"
	);

	protected $casts = array(
		"copy_no" => "integer",
		"notes"   => "string",
		"barcode" => "string",
		"deleted_at" => "datetime"
	);

	public function book() {
		return $this->belongsTo("\App\Models\Book");
	}

	public function loans() {
		return $this->hasMany("\App\Models\Loan");
	}
}
