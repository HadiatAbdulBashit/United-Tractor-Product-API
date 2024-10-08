<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

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

Route::prefix("/v1")->group(function () {
    Route::post("/register", [AuthController::class, 'register']);
    Route::post("/login", [AuthController::class, 'login'])->name('login');

    Route::middleware("auth:api")->group(function () {
        Route::get("/refresh", [AuthController::class, 'refresh']);
        Route::post("/logout", [AuthController::class, 'logout']);
        Route::get("/me", [AuthController::class, 'me']);

        Route::prefix("/category-products")->group(function () {
            Route::get("/", [CategoryProductController::class, 'index']);
            Route::get("/{id}", [CategoryProductController::class, 'show']);
            Route::post("/", [CategoryProductController::class, 'store']);
            Route::put("/{id}", [CategoryProductController::class, 'update']);
            Route::delete("/{id}", [CategoryProductController::class, 'destroy']);
        });

        Route::prefix("/products")->group(function () {
            Route::get("/", [ProductController::class, 'index']);
            Route::get("/{id}", [ProductController::class, 'show']);
            Route::post("/", [ProductController::class, 'store']);
            Route::put("/{id}", [ProductController::class, 'update']);
            Route::delete("/{id}", [ProductController::class, 'destroy']);
        });
    });
});
