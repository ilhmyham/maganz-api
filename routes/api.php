<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\InternshipController;
use App\Http\Controllers\Api\ApplicationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('maganz')->group(function(){
    Route::apiResource('/roles', RoleController::class);
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Get profile by user ID (bisa untuk lihat profile orang lain)
    Route::get('profiles/{userId}', [ProfileController::class, 'show']);

    // Update & delete photo (hanya pemilik profile)
    Route::put('profiles', [ProfileController::class, 'update']);
    Route::delete('profiles/photo', [ProfileController::class, 'deletePhoto']);


    Route::apiResource('internships', InternshipController::class);
    Route::post('internships/{internship}/apply', [InternshipController::class, 'apply']);

    // Untuk mahasiswa
    Route::post('internships/{internship}/applications', [ApplicationController::class, 'store']);
    Route::get('applications/me', [ApplicationController::class, 'userApplications']);
    Route::delete('applications/{application}', [ApplicationController::class, 'destroy']);

    // Untuk perusahaan
    Route::get('internships/{internship}/applications', [ApplicationController::class, 'index']);
    Route::patch('applications/{application}/status', [ApplicationController::class, 'updateStatus']);
});
