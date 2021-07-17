<?php

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
})->name('welcome');

Auth::routes([
    #'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
  ]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/uebersicht', [App\Http\Controllers\HomeController::class, 'uebersicht'])->name('uebersicht');

Route::post('/', [App\Http\Controllers\BuchPositionenController::class, 'store']);

# Schnittstelle
Route::post('/qweqwe', [App\Http\Controllers\GromasLieferscheinController::class, 'push_to_database']);
