<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SettingController; 
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Api\DashboardApiController;

Auth::routes(['register' => false, 'login' => false]);

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// ==========================================================
// ⭐ RUTAS API - DEBEN IR PRIMERO
// ==========================================================
Route::get('/sales/api', [DashboardApiController::class, 'getSales'])->name('api.sales');
Route::get('/products/api', [DashboardApiController::class, 'getProducts'])->name('api.products');
Route::get('/categories/api', [DashboardApiController::class, 'getCategories'])->name('api.categories');
Route::get('/customers/api', [DashboardApiController::class, 'getCustomers'])->name('api.customers');

// ==========================================================
// RUTAS DE AUTENTICACIÓN Y CONFIGURACIÓN
// ==========================================================

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/settings/edit', [SettingController::class, 'edit'])->name('settings.edit');
Route::put('/settings', [SettingController::class, 'update'])->name('settings.update'); 

Route::get('/register', function () {
    return view('auth.register'); 
})->name('register');

Route::get('/login', [LoginController::class, 'showLoginView'])->name('login');
Route::post('/login/firebase', [LoginController::class, 'loginWithFirebase'])->name('login.firebase');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

// ==========================================================
// RUTAS CRUD DE RECURSOS
// ==========================================================

// Rutas para la gestión de clientes (Customers)
Route::prefix('customers')->group(function () {
    Route::get('/', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/', [CustomerController::class, 'store'])->name('customers.store'); 
    
    Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::get('/{customer}', [CustomerController::class, 'show'])->name('customers.show'); 
    Route::put('/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
});

// Rutas para la gestión de categorías (Categories)
Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});

// Rutas para la gestión de productos (Products)
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    Route::get('/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/', [ProductController::class, 'store'])->name('products.store');
    Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});

// Rutas para la Gestión de Ventas (Sales)
Route::prefix('sales')->group(function () {
    Route::get('/', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/', [SaleController::class, 'store'])->name('sales.store');
    Route::post('/generate-note', [SaleController::class, 'generateNote'])->name('sales.generate_note');
    
    Route::get('/{sale}/edit-data', [SaleController::class, 'getEditData'])->name('sales.edit_data');
    Route::get('/{sale}/view-data', [SaleController::class, 'getViewData'])->name('sales.view_data');
    Route::get('/{sale}/ticket', [SaleController::class, 'ticket'])->name('sales.ticket');
    Route::get('/{sale}/edit', [SaleController::class, 'edit'])->name('sales.edit');
    
    Route::get('/{sale}', [SaleController::class, 'show'])->name('sales.show');
    Route::put('/{sale}', [SaleController::class, 'update'])->name('sales.update');
    Route::delete('/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy');
});