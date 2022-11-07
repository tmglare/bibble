<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Author;
use Inertia\Inertia;
use PDF;

class AuthorController extends Controller {
	public function __construct(Author $author) {
		$this->author = $author;
	}
	/**
	* Display a listing of the resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function index() {
		$authors = $this->author->withTrashed()->orderBy("name")->paginate(10);

		return Inertia::render(
			"Author/AuthorIndex",
			array("authors" => $authors)
		);
	}

	/**
	* Show the form for creating a new resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function create() {
		$author = new $this->author;

		return Inertia::render(
			"Author/AuthorCreate",
			array("author" => $author)
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
				"name" => "required"
			)
		);

		$data = $request->all();

		$author = $this->author->create($data);

		return redirect()->action("App\Http\Controllers\AuthorController@show",$author->id);
	}

	/**
	* Display the specified resource.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function show($id) {
		try {
			$author = $this->author->findOrFail($id);
		} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
			return redirect()->back()->withErrors(__("Author cannot be found"));
		}

		return Inertia::render(
			"Author/AuthorShow",
			array("author" => $author)
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
			$author = $this->author->findOrFail($id);
		} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
			return redirect()->back()->withErrors(__("Author cannot be found"));
		}

		return Inertia::render(
			"Author/AuthorEdit",
			array("author" => $author)
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
				"name" => "required"
			)
		);

		$data = $request->all();
		$author = $this->author->findOrFail($id);

		$author->fill($data)->save();

		return redirect()->action("App\Http\Controllers\AuthorController@show",$id);
	}

	/**
	* Remove the specified resource from storage.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function destroy($id) {
		$author = $this->author->withTrashed()->find($id);
		try {
			if ($author->trashed()) {
				if ($author->books()->count() > 0) {
					return redirect()->back()->withErrors(__("Cannot delete due to linked data"));
				}
				$author->forceDelete();
			} else {
				$author->delete();
			}
		} catch (\Illuminate\Database\QueryException $e) {
			return redirect()->back()->withErrors($e->getmessage());
		}
		return redirect()->action("App\Http\Controllers\AuthorController@index");
	}

	public function downloadPDF($id) {
		$author = $this->author->find($id);

		$pdf = PDF::loadView('pdf',array("author" => $author));
		return $pdf->download("author.pdf");
	}

	public function reinstate($id) {
		$author = $this->author->withTrashed()->find($id);
		$author->restore();
		return redirect()->action("App\Http\Controllers\AuthorController@index");
	}
}
