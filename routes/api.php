<?php

use App\Http\Controllers\api\v1\AuthController;
use App\Http\Controllers\api\v1\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::prefix("v1")->group(function () {
    // Route::post('/auth/findEmail', [AuthController::class, "findEmail"]);
    Route::post('/auth/register', [AuthController::class, "register"]);
    Route::post('/auth/login', [AuthController::class, "login"]);
    Route::post('/auth/provider', [AuthController::class, "provider"]);
});;

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix("v1")->group(function () {
        Route::get('/auth/refrestoken', [AuthController::class, 'refrestoken']);
        Route::get('/auth/user', [AuthController::class, 'user']);
        Route::post('/auth/logout', [AuthController::class, 'destroy']);
        Route::get('/auth/users', [AuthController::class, 'index']);
        Route::prefix('/products')->group(function () {
            Route::get('/', [ProductController::class, 'index']);
            Route::get('/{company}/company', [ProductController::class, 'productCompany']);
            Route::get('/{id}/{company}/product', [ProductController::class, 'productById']);
            Route::post('/insert', [ProductController::class, 'insert']);
            Route::put('/{id}', [ProductController::class, 'update']);
            Route::delete('/{id}', [ProductController::class, 'delete']);
        });
    });
});
