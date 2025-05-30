<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ClientAuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CompteController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CarteBancaireController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\RemboursementController;
use App\Http\Controllers\FraisBancaireController;
use App\Http\Controllers\TicketSupportController;
use App\Models\FraisBancaire;
use App\Models\CarteBancaire;

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

Route::get('test-email', function () {
    \Illuminate\Support\Facades\Mail::raw('Test email', function ($message) {
        $message->to('msarrmoustapha@gmail.com')->subject('Test Email');
    });
    return 'Email envoyé !';
});

// Routes pour les admins
Route::middleware('auth:admin')->group(function () {
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('comptes', CompteController::class);
    Route::apiResource('transactions', TransactionController::class)->only(['store', 'show']);
    Route::apiResource('cartes', CarteBancaireController::class);
    Route::put('cartes/{id}/status', [CarteBancaireController::class, 'updateStatus']);
    Route::apiResource('credits', CreditController::class);
    Route::apiResource('remboursements', RemboursementController::class);
    Route::apiResource('frais', FraisBancaireController::class)->only(['store', 'show']);
    Route::put('frais/{id}/cancel', [FraisBancaireController::class, 'cancel']);
    Route::apiResource('tickets', TicketSupportController::class);
});

// Routes pour les clients
Route::middleware('auth:client')->group(function () {
    Route::get('mes-comptes', function (Request $request) {
        return $request->user()->comptes()->with(['cartes', 'transactionsSource', 'transactionsDest'])->get();
    });
    Route::post('transactions', [TransactionController::class, 'store']);
    Route::get('mes-cartes', function (Request $request) {
        $comptes = $request->user()->comptes()->pluck('id');
        return CarteBancaire::whereIn('compte_id', $comptes)->get();
    });
    Route::put('cartes/{id}/status', [CarteBancaireController::class, 'updateStatus']);
    Route::post('credits', [CreditController::class, 'store']);
    Route::get('mes-credits', function (Request $request) {
        return $request->user()->credits()->with('remboursements')->get();
    });
    Route::post('remboursements', [RemboursementController::class, 'store']);
    Route::get('mes-frais', function (Request $request) {
        $comptes = $request->user()->comptes()->pluck('id');
        return FraisBancaire::whereIn('compte_id', $comptes)->get();
    });
    Route::post('tickets', [TicketSupportController::class, 'store']);
    Route::get('mes-tickets', function (Request $request) {
        return $request->user()->tickets()->with('admin')->get();
    });
});
