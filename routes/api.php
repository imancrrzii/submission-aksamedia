<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\NilaiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Soal Bonus SQL
Route::get('/nilaiRT', [NilaiController::class, 'getNilaiRT']);
Route::get('/nilaiST', [NilaiController::class, 'getNilaiST']);


// Soal Utama
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/divisions', [DivisionController::class, 'index']);
    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::post('/employees', [EmployeeController::class, 'store']);
    Route::put('/employees/{id}', [EmployeeController::class, 'update']); 
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);
});
