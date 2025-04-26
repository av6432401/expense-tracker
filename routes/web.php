<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ExpenseController;
Route::get('/', function () {
    return view('home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('expenses/export', [HomeController::class, 'exportCsv'])->name('expenses.export');
Route::middleware(['auth'])->group(function () {
    Route::resource('expenses', ExpenseController::class);
    Route::get('expenses/export', [ExpenseController::class, 'exportCsv'])->name('expenses.export');
});
Route::get('/expenses/daywise/{date}', [ExpenseController::class, 'getDaywiseExpenses'])->name('expenses.daywise');
Route::get('expenses/export', [ExpenseController::class, 'exportCsv'])->name('expenses.export');
