<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Login')->name('login');
Route::inertia('/login', 'Login')->name('login');

Route::get('/dashboard/employees', [EmployeeController::class, 'index'])->name('dashboard.employees');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard/employees/export', [EmployeeController::class, 'exportCsv'])->name('dashboard.employees.export');
    Route::post('/dashboard/employees', [EmployeeController::class, 'store'])->name('dashboard.employees.store');
    Route::put('/dashboard/employees/{employee}', [EmployeeController::class, 'update'])->name('dashboard.employees.update');
    Route::delete('/dashboard/employees/{employee}', [EmployeeController::class, 'destroy'])->name('dashboard.employees.destroy');
});