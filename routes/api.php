<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InstagramController;
use App\Http\Controllers\Api\ContactUsController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SpreadSheetController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CarDetailController;
use App\Http\Controllers\Api\FrontendController;

Route::prefix('v1')->group(function () {
   
   
    Route::get('/the-car-king/feed', [InstagramController::class, 'thecarkingFeed']);
    Route::get('/instagram/feed', [InstagramController::class, 'getFeed']);
    Route::post('/contact-us', [ContactUsController::class, 'store']);
    Route::get('/get-google-review', [ReviewController::class, 'getGoogleReview']);
     // goole spreace sheet add api 
    Route::post('/add-request', [SpreadSheetController::class, 'AddRequest']);
    
     // Car details API
     Route::post('/admin-login', [AuthController::class, 'adminLogin']); 
    
     Route::middleware('auth:sanctum')->group(function () {
        Route::post('/delete-car-image', [CarDetailController::class, 'deleteCarImage']);
        Route::post('/upload-car-image', [CarDetailController::class, 'uploadCarImage']);
        Route::get('/car-details', [CarDetailController::class, 'index']);
        Route::get('/car-details/{id}', [CarDetailController::class, 'show']);
        Route::post('/car-details', [CarDetailController::class, 'store']);
        Route::post('/car-details/{id}', [CarDetailController::class, 'update']);
        Route::get('/car-delete/{id}', [CarDetailController::class, 'destroy']);
     });
     
    // Frontend API routes
    Route::get('/get-all-makes', [FrontendController::class, 'getAllMakes']);
    Route::get('/get-all-models', [FrontendController::class, 'getAllModels']);
    Route::post('/get-all-cars', [FrontendController::class, 'getAllCars']);
    Route::get('/get-car/{id}', [FrontendController::class, 'getCarById']);
});