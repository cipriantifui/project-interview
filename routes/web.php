<?php

use App\Http\Controllers\CountryController;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::get('/', [CountryController::class, 'index']);
Route::post('country/filter-zone', [CountryController::class, 'filterCountryByZone'])->name('country.filter-zone');
Route::post('country/sort-zone', [CountryController::class, 'sortCountryByZone'])->name('country.sort-zone');
