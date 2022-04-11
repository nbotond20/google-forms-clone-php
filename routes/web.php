<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;
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
    return redirect('/home');
});

Route::get('/home', function () {
    return view('site.home');
})->middleware(['auth']);

Route::get('/forms', [FormController::class, 'show']);

Route::get('/', function () {
    return redirect()->route('example-form-show');
});

Route::get('/pelda-form', function () {
    return view('form');
})->name('example-form-show');

Route::post('/', function (Request $request) {
    $request->validate([
        'title' => 'required|min:3',
        'exp-date' => 'required|date',
        // Form array
        'groups.*.textinput' => 'required|min:3',
        'groups.*.selector' => 'required|in:one,two,three',
    ]);
    // ...
})->name('example-form-process');


require __DIR__.'/auth.php';
