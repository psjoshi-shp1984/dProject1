<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\StaticPageController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are automatically prefixed with "api" and
| assigned the "api" middleware group.
|
*/
Route::middleware(['publicapi'])->group(function () {
    //echo "A";die;
   Route::get('/sliders', [SliderController::class, 'index']);
Route::get('/static-pages', [StaticPageController::class, 'index']);
});

// Route::get('/sliders', [SliderController::class, 'index']);
// Route::get('/static-pages', [StaticPageController::class, 'index']);
Route::get('/test', function () {
    return response()->json(['message' => 'API working successfully!']);
});

