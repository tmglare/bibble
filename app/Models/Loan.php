<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model {
	use HasFactory;

	protected $casts = array(
		"borrowed_on" => "date:Y-m-d",
		"due_back"    => "date:Y-m-d",
		"returned_on" => "date:Y-m-d"
	);

	protected $guarded = array(
		"id",
		"created_at",
		"updated_at"
	);

	protected $fillable = array(
		"borrower_id",
		"inventory_item_id",
		"borrowed_on",
		"due_back",
		"returned_on"
	);

	public function borrower() {
		return $this->belongsTo("\App\Models\Borrower")->withTrashed();
	}

	public function inventoryItem() {
		return $this->belongsTo("\App\Models\InventoryItem")->withTrashed();
	}
}
