<?php

use App\Http\Controllers\ConfigController;
use App\Http\Controllers\CovPassController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PublicKeyController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\SigningKeyController;
use App\Http\Controllers\VisitController;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => '/config'], function () {
    Route::get('/host', [ConfigController::class, 'isHostAllowed']);
});

Route::group(['prefix' => '/visit'], function () {
    Route::post('/register', [VisitController::class, 'registerVisit']);
});

Route::group(['prefix' => '/covpass'], function () {
    Route::post('/check', [CovPassController::class, 'checkCovPass']);
    Route::post('/sign', [CovPassController::class, 'signContactDetails']);
});

Route::group(['middleware' => EnsureFrontendRequestsAreStateful::class], function () {
    Route::post('/session/authenticate', [SessionController::class, 'authenticate']);

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/session/current', [SessionController::class, 'getCurrent']);
        Route::post('/session/destroy', [SessionController::class, 'destroy']);

        Route::group(['prefix' => '/location'], function () {
            Route::get('/', [LocationController::class, 'getAll']);
            Route::put('/', [LocationController::class, 'createOrUpdate']);
            Route::get('/{id}', [LocationController::class, 'get']);
            Route::get('/{id}/pdf', [LocationController::class, 'downloadPdf']);
        });

        Route::group(['prefix' => '/public-key'], function () {
            Route::get('/', [PublicKeyController::class, 'getAll']);
        });

        Route::group(['prefix' => '/signing-key'], function () {
            Route::get('/', [SigningKeyController::class, 'get']);
        });
    });
});
