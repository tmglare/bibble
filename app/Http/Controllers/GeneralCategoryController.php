<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\GeneralCategory;
use Inertia\Inertia;

class GeneralCategoryController extends Controller {
	public function __construct(GeneralCategory $generalCategory) {
		$this->generalCategory = $generalCategory;
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
			$sortColumn = "name";
		}

		$generalCategories = $this->generalCategory->withTrashed()->orderBy($sortColumn,$direction)->paginate(10)->withQueryString();

		return Inertia::render(
			"GeneralCategory/GeneralCategoryIndex",
			array("generalCategories" => $generalCategories)
		);
	}

	/**
	* Show the form for creating a new resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function create() {
		$generalCategory = new $this->generalCategory;

		return Inertia::render(
			"GeneralCategory/GeneralCategoryCreate",
			array("generalCategory" => $generalCategory)
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
				"name" => "required|unique:general_categories"
			)
		);

		$data = $request->all();

		$generalCategory = $this->generalCategory->create($data);

		return redirect()->action("App\Http\Controllers\GeneralCategoryController@show",$generalCategory->id);
	}

	/**
	* Display the specified resource.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function show($id) {
		try {
			$generalCategory = $this->generalCategory->findOrFail($id);
		} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
			return redirect()->back()->withErrors(__("Department cannot be found"));
		}

		return Inertia::render(
			"GeneralCategory/GeneralCategoryShow",
			array("generalCategory" => $generalCategory)
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
			$generalCategory = $this->generalCategory->findOrFail($id);
		} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
			return redirect()->back()->withErrors(__("Department cannot be found"));
		}

		return Inertia::render(
			"GeneralCategory/GeneralCategoryEdit",
			array("generalCategory" => $generalCategory)
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
				"name" => "required|unique:general_categories,name,$id"
			)
		);

		$data = $request->all();
		$generalCategory = $this->generalCategory->findOrFail($id);

		$generalCategory->fill($data)->save();

		return redirect()->action("App\Http\Controllers\GeneralCategoryController@show",$id);
	}

	/**
	* Remove the specified resource from storage.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function destroy($id) {
		$generalCategory = $this->generalCategory->withTrashed()->find($id);

		try {
			if ($generalCategory->trashed()) {
				if ($generalCategory->detailedCategories()->count() > 0) {
					return redirect()->back()->withErrors(__("Cannot delete due to linked data"));
				}
				$generalCategory->forceDelete();
			} else {
				$generalCategory->delete();
			}
		} catch (\Illuminate\Database\QueryException $e) {
			return redirect()->back()->withErrors($e->getMessage());
		}

		return redirect()->action("App\Http\Controllers\GeneralCategoryController@index");
	}

	public function reinstate($id) {
		$generalCategory = $this->generalCategory->withTrashed()->find($id);
		$generalCategory->restore();
		return redirect()->action("App\Http\Controllers\GeneralCategoryController@index");
	}
}
