<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$apiVersion = 'v1';
Route::group(['prefix' => $apiVersion], function () use ($apiVersion) {
    Route::get('/status', function () use ($apiVersion) {
        return response([
            'message'   => 'webservice is running!',
            'version'   => $apiVersion
        ]);
    })->name('status');
    
	Route::group([],__DIR__.'/auth.php');
});

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/