<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\MutasiController;
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
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:api')->group(function () {
    //user
    Route::get('users', [UserController::class, 'index']);
    Route::post('users', [UserController::class, 'store']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);    
    
    //barang
    Route::get('barangs', [BarangController::class, 'index']);
    Route::post('barangs', [BarangController::class, 'store']);
    Route::get('barangs/{id}', [BarangController::class, 'show']);
    Route::put('barangs/{id}', [BarangController::class, 'update']);
    Route::delete('barangs/{id}', [BarangController::class, 'destroy']);
    
    //mutasi
    Route::get('mutasi', [MutasiController::class, 'index']);
    Route::post('mutasi', [MutasiController::class, 'store']);
    Route::get('mutasi/{id}', [MutasiController::class, 'show']);
    Route::put('mutasi/{id}', [MutasiController::class, 'update']);
    Route::delete('mutasi/{id}', [MutasiController::class, 'destroy']);
    
    //historymutasi
    
    Route::get('mutasi/historybybarang/{barangId}', [MutasiController::class, 'historyByBarang']);
    Route::get('mutasi/historybyuser/{userId}', [MutasiController::class, 'historyByUser']);
    
    });