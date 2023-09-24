<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Author;
use App\Models\DetailedCategory;
use App\Models\InventoryItem;
use App\Models\Book;
use Inertia\Inertia;

class BookController extends Controller {
	public function __construct(Book $book) {
		$this->book = $book;
	}
	/**
	* Display a listing of the resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function index() {
		$books = $this->book->withTrashed()->with("detailedCategory","author")->orderBy("title")->paginate(10);

		return Inertia::render(
			"Book/BookIndex",
			array("books" => $books)
		);
	}

	/**
	* Show the form for creating a new resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function create() {
		$authors = Author::select("id","name")->orderBy("name")->get();

		$detailedCategories = DetailedCategory::select("id","name")->orderBy("name")->get();

		$book = new $this->book;

		return Inertia::render(
			"Book/BookCreate",
			array(
				"book"               => $book,
				"authors"            => $authors,
				"detailedCategories" => $detailedCategories
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
				"title"                  => "required",
				"author_id"              => "required",
				"detailed_category_id"   => "required",
				"isbn"                   => "required|unique:books",
				"publisher"              => "required"
			)
		);

		$data = $request->all();

		$book = $this->book->create($data);

		if ($request->input("add_inventory_item")) {
			$maxBarcode = InventoryItem::max("barcode");

			if (! $maxBarcode) {
				$maxBarcode = "T1000";
			}

			$newBarcode = ++$maxBarcode;

			$inventoryItem = new InventoryItem;
			$inventoryItem->book_id = $book->id;
			$inventoryItem->copy_no = 1;
			$inventoryItem->barcode = $newBarcode;

			$inventoryItem->save();

			session()->flash("message",__("Book title and library copy added"));
		} else {
			session()->flash("message",__("Book title added (no library copy added)"));
		}

		return redirect()->action("App\Http\Controllers\BookController@show",$book->id);
		// return redirect()->action("App\Http\Controllers\BookController@create");
	}

	/**
	* Display the specified resource.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function show($id) {
		try {
			$book = $this->book->with("author","detailedCategory")->findOrFail($id);
		} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
			return redirect()->back()->withErrors(__("Book cannot be found"));
		}

		return Inertia::render(
			"Book/BookShow",
			array("book" => $book)
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
			$book = $this->book->findOrFail($id);
		} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
			return redirect()->back()->withErrors(__("Book cannot be found"));
		}

		$detailedCategories = DetailedCategory::orderBy("name") ->get()->pluck("name","id")->toArray();
		$authors            = Author::withTrashed()->orderBy("name") ->get()->pluck("name","id")->toArray();

		return Inertia::render(
			"Book/BookEdit",
			array(
				"book"               => $book,
				"authors"            => $authors,
				"detailedCategories" => $detailedCategories
			)
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
				"isbn"                 => "required|unique:books,isbn,$id",
				"title"                => "required",
				"publisher"            => "required",
				"detailed_category_id" => "required",
				"author_id"            => "required"
			)
		);

		$data = $request->all();
		$book = $this->book->findOrFail($id);

		$book->fill($data)->save();

		return redirect()->action("App\Http\Controllers\BookController@show",$id);
	}

	/**
	* Remove the specified resource from storage.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function destroy($id) {
		$book = $this->book->withTrashed()->find($id);

		try {
			if ($book->trashed()) {
				if ($book->inventoryItems()->count() > 0) {
					return redirect()->back()->withErrors(__("Cannot delete due to linked data"));
				}
				$book->forceDelete();
			} else {
				$book->delete();
			}
		} catch (\Illuminate\Database\QueryException $e) {
			//return redirect()->back()->withErrors($e->getMessage());
			return redirect()->back()->withErrors($e->getmessage());
		}

		return redirect()->action("App\Http\Controllers\BookController@index");
	}

	public function reinstate($id) {
		$book = $this->book->withTrashed()->find($id);
		$book->restore();
		return redirect()->action("App\Http\Controllers\BookController@index");
	}
}
