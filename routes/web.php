<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\GeneralCategoryController;
use App\Http\Controllers\DetailedCategoryController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowerController;
use App\Http\Controllers\InventoryItemController;
use App\Http\Controllers\LoanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::group(
	array("middleware" => array("auth")),
	function() {
		Route::resource("authors",AuthorController::class);
		Route::get("authors/{id}/reinstate",array(AuthorController::class,"reinstate"));
		Route::get("downloadPDF/{id}",array(AuthorController::class,'downloadPDF'));
		Route::resource("generalCategories",GeneralCategoryController::class);
		Route::get("generalCategories/{id}/reinstate",array(GeneralCategoryController::class,"reinstate"));
		Route::resource("detailedCategories",DetailedCategoryController::class);
		Route::get("detailedCategories/{id}/reinstate",array(DetailedCategoryController::class,"reinstate"));
		Route::resource("books",BookController::class);
		Route::get("books/{id}/reinstate",array(BookController::class,"reinstate"));
		Route::resource("borrowers",BorrowerController::class);
		Route::get("borrowers/{id}/reinstate",array(BorrowerController::class,"reinstate"));
		Route::resource("inventoryItems",InventoryItemController::class);
		Route::get("inventoryItems/{id}/reinstate",array(InventoryItemController::class,"reinstate"));
		Route::resource("loans",LoanController::class);
		Route::get("inventoryItems/byBarcode/{barcode}",array(InventoryItemController::class,"selectByBarcode"));
		Route::get("borrowers/byBarcode/{barcode}",array(BorrowerController::class,"selectByBarcode"));
	}
);

require __DIR__.'/auth.php';
