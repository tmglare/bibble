<?php

namespace App\Http\Controllers;

use Illuminate\Mail\SentMessage;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Borrower;
use App\Models\InventoryItem;
use App\Services\Loan\OverdueService;

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
		$columnName = $request->input("columnName");
		$direction  = $request->input("direction");

		if (! $direction) { $direction = "asc"; }

		if ($columnName) {
			$sortColumn = $columnName;
		} else {
			$sortColumn = "borrowed_on";
		}

		if ($borrowerId) {
			$loans = $this->loan
				->with(array("borrower","inventoryItem.book"))
				->where("borrower_id",$borrowerId)
				->join("borrowers","loans.borrower_id","=","borrowers.id")
				->join("inventory_items","loans.inventory_item_id","=","inventory_items.id")
				->join("books","inventory_items.book_id","=","books.id")
				->select("loans.id AS loan_id","inventory_item_id","borrower_id","borrowed_on","due_back","returned_on")
				->whereNull("returned_on")
				->orderBy($sortColumn,$direction)
				->paginate(8)
				->withQueryString();
		} else {
			$loans = $this->loan
				->with(array("borrower","inventoryItem.book"))
				->join("borrowers","loans.borrower_id","=","borrowers.id")
				->join("inventory_items","loans.inventory_item_id","=","inventory_items.id")
				->join("books","inventory_items.book_id","=","books.id")
				->select("loans.id AS loan_id","inventory_item_id","borrower_id","borrowed_on","due_back","returned_on")
				->whereNull("returned_on")
				->orderBy($sortColumn,$direction)
				->paginate(8)
				->withQueryString(); }

		return Inertia::render(
			"Loan/LoanIndex",
			array(
				"loans" => $loans,
				"borrowerId" => $borrowerId
			)
		);
	}

	public function indexInclHistory(Request $request) {
		$borrowerId = $request->input("borrowerId");
		$columnName = $request->input("columnName");
		$direction  = $request->input("direction");

		if (! $direction) { $direction = "asc"; }

		if ($columnName) {
			$sortColumn = $columnName;
		} else {
			$sortColumn = "borrowed_on";
		}

		if ($borrowerId) {
			$loans = $this->loan
				->with(array("borrower","inventoryItem.book"))
				->where("borrower_id",$borrowerId)
				->join("borrowers","loans.borrower_id","=","borrowers.id")
				->orderBy($sortColumn,$direction)
				->paginate(8)
				->withQueryString();
		} else {
			$loans = $this->loan
				->with(array("borrower","inventoryItem.book"))
				->join("borrowers","loans.borrower_id","=","borrowers.id")
				->join("inventory_items","loans.inventory_item_id","=","inventory_items.id")
				->join("books","inventory_items.book_id","=","books.id")
				->select("loans.id AS loan_id","inventory_item_id","borrower_id","borrowed_on","due_back","returned_on")
				->orderBy($sortColumn,$direction)
				->paginate(8)
				->withQueryString();
		}

		return Inertia::render(
			"Loan/LoanHistoryIndex",
			array(
				"loans" => $loans
			)
		);
	}

	/**
	* Show the form for creating a new resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function create() {
		$borrowers = Borrower::select("id","forenames","surname")->orderBy("surname")->orderBy("forenames")->get();
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

		$inventoryItemId = $request->input("inventory_item_id");

		$this->validate(
			$request,
			array(
				"inventory_item_id" => array(
					Rule::unique("loans")->where(
						function ($query) use ($inventoryItemId) {
							return $query->
								where("inventory_item_id",$inventoryItemId)->
								whereNull("returned_on");
						}
					)
				)
			),
			[ "inventory_item_id" => __("Item has already been booked out!") ]
		);

		$data = $request->all();

		$loan = $this->loan->create($data);

		session()->flash("message",__("Item successfully booked out"));

		if ($request->input("counter")) {
			return redirect()->action("App\Http\Controllers\LoanController@counter");
		} else {
			return redirect()->action("App\Http\Controllers\LoanController@show",$loan->id);
		}
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
			// return redirect()->back()->withErrors(__("Loan cannot be found"));
			return redirect()->back()->withErrors($e->getMessage());
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

		// $borrowers = Borrower::select("id","name")->orderBy("name")->get();
		$borrowers = Borrower::select("id","forenames","surname")->orderBy("surname")->orderBy("forenames")->get();
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

	public function return() {
		return Inertia::render(
			"Loan/LoanReturn"
		);
	}

	public function processReturn(Request $request) {
		$itemId = $request->input("inventory_item_id");
		if ($itemId) {
			$item = InventoryItem::find($itemId);
			if ($item) {
				$loan = $this->loan->where("inventory_item_id",$itemId)->whereNull("returned_on")->first();
				if (! $loan) {
					return redirect()->back()->withErrors(__("Item is not on loan!"));
				}
				$loan->returned_on = Carbon::today();
				$loan->save();

				// return redirect()->action("App\Http\Controllers\LoanController@show",$loan->id);

				session()->flash("message",__("Item successfully booked in"));

				if ($request->input("counter")) {
					return redirect()->action("App\Http\Controllers\LoanController@counter");
				} else {
					return redirect()->action("App\Http\Controllers\LoanController@show",$loan->id);
				}
			}
		}

		return redirect()->back()->withErrors(__("Item not found"));
	}

	public function counter() {
		$borrowers = Borrower::select("id","forenames","surname","barcode")->orderBy("surname")->orderBy("forenames")->get();
		$inventoryItemsOffLoan = InventoryItem::with("book")->select("id","copy_no","book_id","barcode")->whereDoesntHave("loans",function (Builder $query) { $query->whereNull("returned_on");})->get();
		$inventoryItemsOnLoan = InventoryItem::with("book")->select("id","copy_no","book_id","barcode")->whereHas("loans",function (Builder $query) { $query->whereNull("returned_on");})->get();

		$loan = new $this->loan;
		$loan->borrowed_on = Carbon::today();
		$loan->due_back    = $loan->borrowed_on->addWeek(3);

		return Inertia::render(
			"Loan/LoanCounter",
			array(
				"loan"           => $loan,
				"borrowers"      => $borrowers,
				"inventoryItemsOffLoan" => $inventoryItemsOffLoan,
				"inventoryItemsOnLoan" => $inventoryItemsOnLoan
			)
		);
	}

	public function overdue($id, OverdueService $overdueService) {
		try {
			$sentEmail = $overdueService->sendEmail($id);
		} catch (\Exception $e) {
			return redirect()->back()->withErrors(__("Email failed - ") . $e->getMessage());
		}
		return redirect()->action("App\Http\Controllers\LoanController@index");
	}
}
