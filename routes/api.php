<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VoyageController;

Route::prefix('voyage')->group(function() {
    Route::controller(VoyageController::class)->group(function() {
        Route::post('/', 'addVoyage');
        Route::put('/{id}', 'editVoyage');
    });
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
