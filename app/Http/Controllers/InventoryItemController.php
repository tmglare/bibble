<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\InventoryItem;
use App\Models\Book;
use Inertia\Inertia;

class InventoryItemController extends Controller {
	public function __construct(InventoryItem $inventoryItem) {
		$this->inventoryItem = $inventoryItem;
	}

	/**
	* Display a listing of the resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function index() {
		$inventoryItems = $this->inventoryItem->with("book","book.author")->withTrashed()->orderBy("book_id")->paginate(10);

		return Inertia::render(
			"InventoryItem/InventoryItemIndex",
			array("inventoryItems" => $inventoryItems)
		);
	}

	/**
	* Show the form for creating a new resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function create() {
		$books = Book::select("id","title")->orderBy("title")->get();

		$inventoryItem = new $this->inventoryItem;

		return Inertia::render(
			"InventoryItem/InventoryItemCreate",
			array(
				"inventoryItem" => $inventoryItem,
				"books"         => $books
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
				"book_id" => "required",
				"copy_no" => array(
					Rule::unique("inventory_items")->where(
						function ($query) use ($request) {
							return $query->where("book_id",$request->book_id)->where("copy_no",$request->copy_no);
						}
					)
				)
			)
		);

		$maxBarcode = $this->inventoryItem->max("barcode");

		if (! $maxBarcode) {
			$maxBarcode = "T1000";
		}

		$newBarcode = ++$maxBarcode;

		$data = $request->all();
		$data["barcode"] = $newBarcode;

		$book = $this->inventoryItem->create($data);

		return redirect()->action("App\Http\Controllers\InventoryItemController@show",$book->id);
	}

	/**
	* Display the specified resource.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function show($id) {
		try {
			$inventoryItem = $this->inventoryItem->with("book","book.detailedCategory","book.author")->findOrFail($id);
		} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
			return redirect()->back()->withErrors(__("Item cannot be found"));
		}

		return Inertia::render(
			"InventoryItem/InventoryItemShow",
			array("inventoryItem" => $inventoryItem)
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
			$inventoryItem = $this->inventoryItem->with("book","book.detailedCategory","book.author")->findOrFail($id);
		} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
			return redirect()->back()->withErrors(__("Item cannot be found"));
		}

		return Inertia::render(
			"InventoryItem/InventoryItemEdit",
			array("inventoryItem" => $inventoryItem)
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
				"book_id" => "required",
				"copy_no" => array(
					Rule::unique("inventory_items")->where(
						function ($query) use ($request,$id) {
							return $query->
								where("book_id",$request->book_id)->
								where("copy_no",$request->copy_no)->
								where("id","!=",$id);
						}
					)
				),
				"barcode"  => "nullable|unique:inventory_items,barcode,$id"
			)
		);

		$data = $request->all();
		$inventoryItem = $this->inventoryItem->findOrFail($id);

		if (! $data["barcode"] and ! $inventoryItem->barcode) {
			$maxBarcode = $this->inventoryItem->where("barcode","REGEXP","^T\d+")->max("barcode");

			if (! $maxBarcode) {
				$maxBarcode = "T1000";
			}

			$newBarcode = ++$maxBarcode;

			$data["barcode"] = $newBarcode;
		}

		$inventoryItem->fill($data)->save();

		return redirect()->action("App\Http\Controllers\InventoryItemController@show",$id);
	}

	/**
	* Remove the specified resource from storage.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function destroy($id) {
		$inventoryItem = $this->inventoryItem->withTrashed()->find($id);
		if ($inventoryItem->trashed()) {
			$inventoryItem->forceDelete();
		} else {
			$inventoryItem->delete();
		}
		return redirect()->action("App\Http\Controllers\InventoryItemController@index");
	}

	public function reinstate($id) {
		$inventoryItem = $this->inventoryItem->withTrashed()->find($id);
		$inventoryItem->restore();
		return redirect()->action("App\Http\Controllers\InventoryItemController@index");
	}

	public function selectByBarcode($barcode = null) {
		if (! $barcode) {
			return null;
		}
		$inventoryItemId = $this->inventoryItem->where("barcode",$barcode)->value("id");

		if (! $inventoryItemId) {
			return null;
		}

		return $inventoryItemId;
	}

	public function getItemByBarcode($barcode = null) {
		if (! $barcode) {
			return null;
		}

		$item = $this->inventoryItem->where("barcode",$barcode)->with("book")->first();

		if (! $item) {
			return null;
		}

		return $item;
	}
}
