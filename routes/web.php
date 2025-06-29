<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use \App\Http\Controllers\IncomeController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->name('categories.')->prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::get('/create', [CategoryController::class, 'create'])->name('create');
    Route::post('/store', [CategoryController::class, 'store'])->name('store');
    Route::get('/edit/{category}', [CategoryController::class, 'edit'])->name('edit');
    Route::put('/update/{category}', [CategoryController::class, 'update'])->name('update');
    Route::delete('/destroy/{category}', [CategoryController::class, 'destroy'])->name('destroy');
});

Route::middleware(['auth', 'verified'])->name('incomes.')->prefix('incomes')->group(function () {
    Route::get('/', [IncomeController::class, 'index'])->name('index');
    Route::get('/show', [IncomeController::class, 'show'])->name('show');
    Route::get('/create', [IncomeController::class, 'create'])->name('create');
    Route::post('/store', [IncomeController::class, 'store'])->name('store');
    Route::get('/edit/{income}', [IncomeController::class, 'edit'])->name('edit');
    Route::put('/update/{income}', [IncomeController::class, 'update'])->name('update');
    Route::delete('/destroy/{income}', [IncomeController::class, 'destroy'])->name('destroy');
});


require __DIR__ . '/auth.php';
