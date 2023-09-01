<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Borrower extends Model {
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
		"forenames",
		"surname",
		"style",
		"street",
		"town",
		"postcode",
		"telephone",
		"email",
		"barcode"
	);

	protected $casts = array(
		"forenames" => "string",
		"surname"   => "string",
		"style"     => "string",
		"street"    => "string",
		"town"      => "string",
		"postcode"  => "string",
		"telephone" => "string",
		"email"     => "string",
		"barcode"   => "string"
	);

	protected $appends = array(
		"name"
	);

	public function getNameAttribute() {
		if (! $this->forenames) { return $this->surname; }
		if (! $this->surname)   { return $this->forenames; }
		return ("$this->surname, $this->forenames");
	}

	public function setTownAttribute($value) {
		$this->attributes["town"] = strtoupper($value);
	}

	public function setPostcodeAttribute($value) {
		$this->attributes["postcode"] = strtoupper($value);
	}
}
