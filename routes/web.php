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

// Don't add this rule in development. 404s are easier to debug without it.
if (app()->environment('production')) {
    Route::get('/{any}', function () {
        return file_get_contents(public_path('ui-index.html'));
    })->where('any', '.*');
}
