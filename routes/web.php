<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;
use App\Http\Controllers\AnswerController;
use Illuminate\Http\Request;

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
    return redirect('forms');
});

/* Route::get('/home', function () {
    return view('site.home');
})->middleware(['auth'])->name('home'); */

Route::resource('forms', FormController::class)->middleware(['auth']);
Route::resource('form', AnswerController::class);

require __DIR__.'/auth.php';
