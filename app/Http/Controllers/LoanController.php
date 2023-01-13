<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

use App\Models\Loan;
use App\Models\Borrower;
use App\Models\InventoryItem;

use Inertia\Inertia;
use Carbon\Carbon;

class LoanController extends Controller {
	public function __construct(Loan $loan) {
		$this->loan = $loan;
	}

	/**
	* Display a listing of the resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function index(Request $request) {
		$borrowerId = $request->input("borrowerId");

		if ($borrowerId) {
			$loans = $this->loan->where("borrower_id",$borrowerId)->with("borrower","inventoryItem.book")->orderBy("returned_on")->orderBy("borrowed_on")->paginate(10);
		} else {
			$loans = $this->loan->with("borrower","inventoryItem.book")->orderBy("returned_on")->orderBy("borrowed_on")->paginate(10);
		}

		return Inertia::render(
			"Loan/LoanIndex",
			array("loans" => $loans)
		);
	}

	/**
	* Show the form for creating a new resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function create() {
		$borrowers = Borrower::select("id","name")->orderBy("name")->get();
		$inventoryItems = InventoryItem::with("book")->select("id","copy_no","book_id")->whereDoesntHave("loans",function (Builder $query) { $query->whereNull("returned_on");})->get();

		$loan = new $this->loan;
		$loan->borrowed_on = Carbon::today();
		$loan->due_back    = $loan->borrowed_on->addWeek(3);

		return Inertia::render(
			"Loan/LoanCreate",
			array(
				"loan"           => $loan,
				"borrowers"      => $borrowers,
				"inventoryItems" => $inventoryItems
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
				"borrower_id"       => "required",
				"inventory_item_id" => "required",
				"borrowed_on"       => "required",
				"due_back"          => "required"
			)
		);

		$data = $request->all();

		$loan = $this->loan->create($data);

		return redirect()->action("App\Http\Controllers\LoanController@show",$loan->id);
	}

	/**
	* Display the specified resource.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function show($id) {
		try {
			$loan = $this->loan->with(array("borrower","inventoryItem.book"))->findOrFail($id);
		} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
			return redirect()->back()->withErrors(__("Loan cannot be found"));
		}

		return Inertia::render(
			"Loan/LoanShow",
			array("loan" => $loan)
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
			$loan = $this->loan->with(array("borrower","inventoryItem.book"))->findOrFail($id);
		} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
			return redirect()->back()->withErrors(__("Loan cannot be found"));
		}

		$borrowers = Borrower::select("id","name")->orderBy("name")->get();
		$inventoryItems = InventoryItem::with("book")->select("id","copy_no","book_id")->whereDoesntHave("loans",function (Builder $query) { $query->whereNull("returned_on");})->get();

		return Inertia::render(
			"Loan/LoanEdit",
			array("loan" => $loan)
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
				"borrowed_on"       => "required",
				"due_back"          => "required"
			)
		);

		$data = $request->all();
		$loan = $this->loan->findOrFail($id);

		$loan->fill($data)->save();

		return redirect()->action("App\Http\Controllers\LoanController@show",$id);
	}

	/**
	* Remove the specified resource from storage.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function destroy($id) {
		$loan = $this->loan->find($id);
		$loan->delete();
		return redirect()->action("App\Http\Controllers\LoanController@index");
	}
}
