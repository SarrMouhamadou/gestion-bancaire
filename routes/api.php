<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ClientAuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CompteController;
use App\Http\Controllers\TransactionController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('client')->group(function () {
    Route::post('register', [ClientAuthController::class, 'register']);
    Route::post('login', [ClientAuthController::class, 'login']);
    Route::middleware('auth:client')->post('logout', [ClientAuthController::class, 'logout']);
});

Route::prefix('admin')->group(function () {
    Route::post('register', [AdminAuthController::class, 'register']);
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::middleware('auth:admin')->post('logout', [AdminAuthController::class, 'logout']);
});


// Routes pour les admins
Route::middleware('auth:admin')->group(function () {
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('comptes', CompteController::class);
    Route::apiResource('transactions', TransactionController::class)->only(['store', 'show']);
});

// Routes pour les clients
Route::middleware('auth:client')->group(function () {
    Route::get('mes-comptes', function (Request $request) {
        return $request->user()->comptes()->with(['cartes', 'transactionsSource', 'transactionsDest'])->get();
    });
    Route::post('transactions', [TransactionController::class, 'store']);
});
