<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

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
	public function index(Request $request) {
		$columnName = $request->input("columnName");
		$direction  = $request->input("direction");
		$searchTitle = $request->input("searchTitle");
		$searchAuthor = $request->input("searchAuthor");

		if (! $searchTitle) { $searchTitle = ""; }
		$searchTitle = "$searchTitle%";
		if (! $searchAuthor) { $searchAuthor = ""; }
		$searchAuthor = "$searchAuthor%";

		if (! $direction) { $direction = "asc"; }

		if ($columnName) {
			$sortColumn = $columnName;
		} else {
			$sortColumn = "title";
		}

		if ($sortColumn == "detailedCategory.name") {
			$books = tap(
				$this->book->withTrashed()->with("detailedCategory","author")
					->where("title","LIKE",$searchTitle)
					->whereHas(
						"author",
						function($query) use ($searchAuthor) {
							$query->where("ordered_name","LIKE",$searchAuthor);
						}
					)
					->orderBy(
						DetailedCategory::select('name')
            ->whereColumn('id', 'books.detailed_category_id')
            ->limit(1),$direction
					)
					->orderBy("title")
					->paginate(10)->withQueryString()
			)->map(
				function($book) {
					$copies = $book->inventoryItems()->count();
					$book->copies = $copies;
					return $book;
				}
			);
		} elseif ($sortColumn == "author.ordered_name") {
			$books = tap(
				$this->book->withTrashed()->with("detailedCategory","author")
					->where("title","LIKE",$searchTitle)
					->whereHas(
						"author",
						function($query) use ($searchAuthor) {
							$query->where("ordered_name","LIKE",$searchAuthor);
						}
					)
					->orderBy(
						Author::select('ordered_name')
            ->whereColumn('id', 'books.author_id')
            ->limit(1),$direction
					)
					->orderBy(
						"title"
					)
					->paginate(10)->withQueryString()
			)->map(
				function($book) {
					$copies = $book->inventoryItems()->count();
					$book->copies = $copies;
					return $book;
				}
			);
		} else {
			$books = tap(
				$this->book->withTrashed()->with("detailedCategory","author")
					->where("title","LIKE",$searchTitle)
					->whereHas(
						"author",
						function($query) use ($searchAuthor) {
							$query->where("ordered_name","LIKE",$searchAuthor);
						}
					)
					->orderBy(
						"title",$direction
					)
					->orderBy(
						Author::select('name')
            ->whereColumn('id', 'books.author_id')
            ->orderBy('name')
            ->limit(1)
					)
					->paginate(10)->withQueryString()
			)->map(
				function($book) {
					$copies = $book->inventoryItems()->count();
					$book->copies = $copies;
					return $book;
				}
			);
		}

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
		$authors = Author::select("id","name")->orderBy("name")->get()->pluck("name");

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
				"detailed_category_id"   => "required",
				"isbn"                   => "required|unique:books",
				"publisher"              => "required"
			)
		);

		$data = $request->all();

		try {
			$authorName = $request->input("author_name");
		} catch (\Exception $e) {
			return redirect()->back()->withErrors(__("Author not specified"));
		}

		$author = Author::where("name",$authorName)->first();

		if (! $author) {
			preg_match("/^(.*)\s+(\w+)$/",$authorName,$matches);
			if (sizeof($matches) > 2) {
				$forenames = $matches[1];
				$surname = $matches[2];
				$orderedName = "$surname, $forenames";
			} else {
				$orderedName = $authorName;
			}
			$author = Author::create(["name" => $authorName, "ordered_name" => $orderedName]);
		}

		$data["author_id"] = $author->id;

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

		$book->copies = $book->inventoryItems()->count();

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

	public function addCopy($id) {
		$book = $this->book->withTrashed()->find($id);

		$maxCopyNo = $book->inventoryItems()->max("copy_no");
		if (is_null($maxCopyNo)) { $maxCopyNo = 0; }
		$newCopyNo = $maxCopyNo + 1;

		$maxBarcode = InventoryItem::max("barcode");

		if (! $maxBarcode) {
			$maxBarcode = "T1000";
		}

		$newBarcode = ++$maxBarcode;

		$inventoryItem = new InventoryItem;
		$inventoryItem->book_id = $book->id;
		$inventoryItem->copy_no = $newCopyNo;
		$inventoryItem->barcode = $newBarcode;

		$inventoryItem->save();

		session()->flash("message",__("New library copy added"));

		return redirect()->action("App\Http\Controllers\BookController@show",$id);
	}
}
