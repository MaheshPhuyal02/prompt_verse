<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PromptController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard-stats', [DashboardController::class, 'stats']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/user', [AuthController::class, 'user']);
    Route::post('/file/upload', [FileController::class, 'saveFile']);
    Route::get('/file', [FileController::class, 'index']);
    Route::delete('/file/{fileId}', [FileController::class, 'destroy']);
    Route::get('/admin/purchases', [PurchaseController::class, 'all']);
    Route::apiResource('purchases', PurchaseController::class);
    Route::apiResource('carts', CartController::class);
    Route::apiResource('prompts', PromptController::class);
    Route::get('/get_button', [CartController::class, 'getKhaltiButton']);
    Route::get('/addresses', [AddressController::class, 'index']);
    Route::post('/addresses', [AddressController::class, 'store']);
    Route::put('/addresses/{address}', [AddressController::class, 'update']);
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy']);
    Route::post('/addresses/{address}/default', [AddressController::class, 'setDefault']);
    Route::get('/user/purchases', [ProfileController::class, 'purchases']);
    Route::get('/admin/all-prompts', [\App\Http\Controllers\PromptController::class, 'allPrompts']);
});

// Public file access
Route::get('/file/{fileId}', [FileController::class, 'getFile']);

// Khalti payment return URL - this should be public as Khalti will call it
Route::get('/payment/success', [CartController::class, 'handleKhaltiReturn']);

