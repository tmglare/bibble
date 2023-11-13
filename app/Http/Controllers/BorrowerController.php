<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Borrower;
use Inertia\Inertia;
use DNS1D;
use PDF;

class BorrowerController extends Controller {
	public function __construct(Borrower $borrower) {
		$this->borrower = $borrower;
	}

	/**
	* Display a listing of the resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function index(Request $request) {
		$columnName = $request->input("columnName");
		$direction  = $request->input("direction");

		if (! $direction) { $direction = "asc"; }

		if ($columnName) {
			$sortColumn = $columnName;
		} else {
			$sortColumn = "";
		}

		if ($sortColumn) {
			$borrowers = $this->borrower->withTrashed()->orderBy($sortColumn,$direction)->orderBy("surname")->orderBy("forenames")->paginate(10)->withQueryString();
		} else {
			$borrowers = $this->borrower->withTrashed()->orderBy("surname",$direction)->orderBy("forenames",$direction)->paginate(10)->withQueryString();
		}

		return Inertia::render(
			"Borrower/BorrowerIndex",
			array("borrowers" => $borrowers)
		);
	}

	/**
	* Show the form for creating a new resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function create() {
		$borrower = new $this->borrower;

		return Inertia::render(
			"Borrower/BorrowerCreate",
			array(
				"borrower" => $borrower
			)
		);
	}

	/**
	* Store a newly created resource in storage.
	*
	* @param  \Illuminate\Http\Request  $request
	* @return \Illuminate\Http\Response
	*/
	public function store(Request $request) {
		$this->validate(
			$request,
			array(
				"surname"  => "required",
				"street"   => "required",
				"town"     => "required",
				"postcode" => "required"
			)
		);

		$maxBarcode = $this->borrower->max("barcode");

		if (! $maxBarcode) {
			$maxBarcode = "B1000";
		}

		$newBarcode = ++$maxBarcode;

		$data = $request->all();
		$data["barcode"] = $newBarcode;

		$borrower = $this->borrower->create($data);

		return redirect()->action("App\Http\Controllers\BorrowerController@show",$borrower->id);
	}

	/**
	* Display the specified resource.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function show($id) {
		try {
			$borrower = $this->borrower->findOrFail($id);
		} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
			return redirect()->back()->withErrors(__("Borrower cannot be found"));
		}

		$barcodeImage = DNS1D::getBarcodePNG($borrower->barcode,"C128");
		$borrower->barcodeImage = $barcodeImage;

		return Inertia::render(
			"Borrower/BorrowerShow",
			array("borrower" => $borrower)
		);
	}

	/**
	* Show the form for editing the specified resource.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function edit($id) {
		try {
			$borrower = $this->borrower->findOrFail($id);
		} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
			return redirect()->back()->withErrors(__("Borrower cannot be found"));
		}

		return Inertia::render(
			"Borrower/BorrowerEdit",
			array("borrower" => $borrower)
		);
	}

	/**
	* Update the specified resource in storage.
	*
	* @param  \Illuminate\Http\Request  $request
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function update(Request $request, $id) {
		$this->validate(
			$request,
			array(
				"surname"  => "required",
				"street"   => "required",
				"town"     => "required",
				"postcode" => "required",
				"barcode"  => "nullable|unique:borrowers,barcode,$id"
			)
		);

		$data = $request->all();
		$borrower = $this->borrower->findOrFail($id);

		if (! $borrower->barcode) {
			$maxBarcode = $this->borrower->where("barcode","REGEXP","^B\d+")->max("barcode");

			if (! $maxBarcode) {
				$maxBarcode = "B1000";
			}

			$newBarcode = ++$maxBarcode;

			$data["barcode"] = $newBarcode;
		}

		$borrower->fill($data)->save();

		return redirect()->action("App\Http\Controllers\BorrowerController@show",$id);
	}

	/**
	* Remove the specified resource from storage.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function destroy($id) {
		$borrower = $this->borrower->withTrashed()->find($id);
		if ($borrower->trashed()) {
			$borrower->forceDelete();
		} else {
			$borrower->delete();
		}
		return redirect()->action("App\Http\Controllers\BorrowerController@index");
	}

	public function reinstate($id) {
		$borrower = $this->borrower->withTrashed()->find($id);
		$borrower->restore();
		return redirect()->action("App\Http\Controllers\BorrowerController@index");
	}

	public function selectByBarcode($barcode = null) {
		if (! $barcode) {
			return null;
		}
		$borrowerId = $this->borrower->where("barcode",$barcode)->value("id");

		if (! $borrowerId) {
			return null;
		}

		return $borrowerId;
	}

	public function getBorrowerByBarcode($barcode = null) {
		if (! $barcode) {
			return null;
		}

		$borrower = $this->borrower->where("barcode",$barcode)->first();

		if (! $borrower) {
			return null;
		}

		return $borrower;
	}

	public function barcodesPDF() {
		$borrowers = $this->borrower->orderBy("barcode")->get();

		$borrowers = $borrowers->map(function($borrower) {
			return [
				"name"         => $borrower->name,
				"barcode"      => $borrower->barcode,
				"barcodeImage" => DNS1D::getBarcodePNG($borrower->barcode,"C128")
			];
		});

		$pdf = PDF::loadView('Borrower/barcodesPDF',array("borrowers" => $borrowers));
		return $pdf->download("barcodes.pdf");
	}
}
