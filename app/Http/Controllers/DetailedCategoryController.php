<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\DetailedCategory;
use App\Models\GeneralCategory;
use Inertia\Inertia;

class DetailedCategoryController extends Controller {
	public function __construct(DetailedCategory $detailedCategory) {
		$this->detailedCategory = $detailedCategory;
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
			$sortColumn = "detailedCategoryName";
		}

		if ($columnName == "generalCategoryName") {
			$detailedCategories = $this->detailedCategory->with("generalCategory")->withTrashed()
				->orderBy(
					GeneralCategory::select('name')
           ->whereColumn('id', 'detailed_categories.general_category_id')
           ->limit(1),$direction
				)
				->orderBy("name")
				->paginate(10)->withQueryString();
		} else {
			$detailedCategories = $this->detailedCategory->with("generalCategory")->withTrashed()
				->orderBy("name",$direction)
				->paginate(10)->withQueryString();
		}

		return Inertia::render(
			"DetailedCategory/DetailedCategoryIndex",
			array("detailedCategories" => $detailedCategories)
		);
	}

	/**
	* Show the form for creating a new resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function create() {
		$generalCategories = GeneralCategory::orderBy("name") ->get()->pluck("name","id")->toArray();
		$detailedCategory = new $this->detailedCategory;

		return Inertia::render(
			"DetailedCategory/DetailedCategoryCreate",
			array(
				"detailedCategory" => $detailedCategory,
				"generalCategories" => $generalCategories
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
				"name" => "required|unique:detailed_categories",
				"general_category_id" => "required"
			)
		);

		$data = $request->all();

		$detailedCategory = $this->detailedCategory->create($data);

		return redirect()->action("App\Http\Controllers\DetailedCategoryController@show",$detailedCategory->id);
	}

	/**
	* Display the specified resource.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function show($id) {
		try {
			$detailedCategory = $this->detailedCategory->with("generalCategory")->findOrFail($id);
		} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
			return redirect()->back()->withErrors(__("Category cannot be found"));
		}

		return Inertia::render(
			"DetailedCategory/DetailedCategoryShow",
			array("detailedCategory" => $detailedCategory)
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
			$detailedCategory = $this->detailedCategory->with("generalCategory")->findOrFail($id);
		} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
			return redirect()->back()->withErrors(__("Category cannot be found"));
		}

		$generalCategories = GeneralCategory::orderBy("name") ->get()->pluck("name","id")->toArray();

		return Inertia::render(
			"DetailedCategory/DetailedCategoryEdit",
			array(
				"detailedCategory" => $detailedCategory,
				"generalCategories" => $generalCategories
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
				"name" => "required|unique:detailed_categories,name,$id",
				"general_category_id" => "required"
			)
		);

		$data = $request->all();
		$detailedCategory = $this->detailedCategory->findOrFail($id);

		$detailedCategory->fill($data)->save();

		return redirect()->action("App\Http\Controllers\DetailedCategoryController@show",$id);
	}

	/**
	* Remove the specified resource from storage.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function destroy($id) {
		$detailedCategory = $this->detailedCategory->withTrashed()->find($id);
		if ($detailedCategory->trashed()) {
			$detailedCategory->forceDelete();
		} else {
			$detailedCategory->delete();
		}
		return redirect()->action("App\Http\Controllers\DetailedCategoryController@index");
	}

	public function reinstate($id) {
		$detailedCategory = $this->detailedCategory->withTrashed()->find($id);
		$detailedCategory->restore();
		return redirect()->action("App\Http\Controllers\DetailedCategoryController@index");
	}
}
