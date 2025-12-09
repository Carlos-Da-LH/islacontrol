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
use App\Http\Controllers\ReportController;
use App\Http\Controllers\OllamaController;
use App\Http\Controllers\CashRegisterController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\SubscriptionController;


//Auth::routes(['register' => false, 'login' => false]);
Auth::routes([
    'register' => false,  // No queremos la ruta de registro de Laravel
    'login' => false,     // Ya tienes tu login personalizado
    'reset' => true,      // Habilitar rutas de reset de contraseña
    'verify' => false     // No necesitas verificación de email
]);


Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// ==========================================================
// RUTAS LEGALES (PÚBLICAS)
// ==========================================================
Route::get('/privacidad', function () {
    return view('legal.privacy');
})->name('legal.privacy');

Route::get('/terminos', function () {
    return view('legal.terms');
})->name('legal.terms');

Route::get('/contacto', function () {
    return view('legal.contact');
})->name('legal.contact');

Route::get('/acerca-de', function () {
    return view('legal.about');
})->name('legal.about');

// ==========================================================
// RUTAS DE AUTENTICACIÓN (SIN MIDDLEWARE)
// ========================================================== 

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/login', [LoginController::class, 'showLoginView'])->name('login');
Route::post('/login/firebase', [LoginController::class, 'loginWithFirebase'])->name('login.firebase');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('password/reset', function () {
    return view('auth.forgot-password');
})->name('password.request');
// ==========================================================
// RUTAS PROTEGIDAS (REQUIEREN AUTENTICACIÓN)
// ==========================================================

// ==========================================================
// 💳 WEBHOOK DE STRIPE (SIN AUTH)
// ==========================================================
Route::post('/stripe/webhook', [SubscriptionController::class, 'webhook']);

Route::middleware(['auth'])->group(function () {
    // ==========================================================
    // 💳 SUSCRIPCIONES
    // ==========================================================
    // Rutas en inglés
    Route::prefix('subscription')->name('subscription.')->group(function () {
        Route::get('/select-plan', [SubscriptionController::class, 'selectPlan'])->name('select-plan');
        Route::get('/plans', [SubscriptionController::class, 'plans'])->name('plans');
        Route::get('/checkout/{plan}', [SubscriptionController::class, 'checkout'])->name('checkout');
        Route::post('/subscribe/{plan}', [SubscriptionController::class, 'subscribe'])->name('subscribe');
        Route::get('/dashboard', [SubscriptionController::class, 'dashboard'])->name('dashboard');
        Route::post('/cancel', [SubscriptionController::class, 'cancel'])->name('cancel');
        Route::post('/resume', [SubscriptionController::class, 'resume'])->name('resume');
        Route::post('/swap/{plan}', [SubscriptionController::class, 'swap'])->name('swap');
        Route::get('/invoices', [SubscriptionController::class, 'invoices'])->name('invoices');
        Route::get('/invoice/{invoiceId}', [SubscriptionController::class, 'downloadInvoice'])->name('invoice.download');
    });

    // Rutas en español (compatibilidad)
    Route::prefix('suscripcion')->name('subscription.es.')->group(function () {
        Route::get('/seleccionar-plan', [SubscriptionController::class, 'selectPlan'])->name('select-plan');
        Route::get('/planes', [SubscriptionController::class, 'plans'])->name('plans');
        Route::get('/checkout/{plan}', [SubscriptionController::class, 'checkout'])->name('checkout');
        Route::post('/subscribe/{plan}', [SubscriptionController::class, 'subscribe'])->name('subscribe');
        Route::get('/dashboard', [SubscriptionController::class, 'dashboard'])->name('dashboard');
        Route::post('/cancelar', [SubscriptionController::class, 'cancel'])->name('cancel');
        Route::post('/reanudar', [SubscriptionController::class, 'resume'])->name('resume');
        Route::post('/cambiar/{plan}', [SubscriptionController::class, 'swap'])->name('swap');
        Route::get('/facturas', [SubscriptionController::class, 'invoices'])->name('invoices');
        Route::get('/factura/{invoiceId}', [SubscriptionController::class, 'downloadInvoice'])->name('invoice.download');
    });
    // ==========================================================
    // ⭐ RUTAS API - Dashboard
    // ==========================================================
    Route::get('/sales/api', [DashboardApiController::class, 'getSales'])->name('api.sales');
    Route::get('/products/api', [DashboardApiController::class, 'getProducts'])->name('api.products');
    Route::get('/categories/api', [DashboardApiController::class, 'getCategories'])->name('api.categories');
    Route::get('/customers/api', [DashboardApiController::class, 'getCustomers'])->name('api.customers');
    Route::get('/settings/api', [DashboardApiController::class, 'getSettings'])->name('api.settings');
    Route::get('/api/sale-items/top-products', [DashboardApiController::class, 'getTopProducts'])->name('api.sale-items.top-products');
    Route::get('/api/subscription/info', [DashboardApiController::class, 'getSubscriptionInfo'])->name('api.subscription.info');

    // ==========================================================
    // 🤖 RUTAS API - Ollama AI
    // ==========================================================
    Route::post('/api/ollama/chat', [OllamaController::class, 'chat'])->name('api.ollama.chat');
    Route::get('/api/ollama/status', [OllamaController::class, 'checkStatus'])->name('api.ollama.status');
    Route::get('/api/ollama/history', [OllamaController::class, 'getHistory'])->name('api.ollama.history');
    Route::post('/api/ollama/clear-history', [OllamaController::class, 'clearHistory'])->name('api.ollama.clear-history');
    Route::post('/api/ollama/new-session', [OllamaController::class, 'newSession'])->name('api.ollama.new-session');
    Route::get('/api/ollama/sessions', [OllamaController::class, 'getSessions'])->name('api.ollama.sessions');
    Route::delete('/api/ollama/session', [OllamaController::class, 'deleteSession'])->name('api.ollama.delete-session');
    Route::post('/api/ollama/clean-old', [OllamaController::class, 'cleanOldConversations'])->name('api.ollama.clean-old');

    // ==========================================================
    // 👁️ PANTALLA DEL CLIENTE (Customer Display)
    // ==========================================================
    Route::get('/pos/customer-display', function () {
        return view('pos.customer-display');
    })->name('pos.customer-display');

    // ==========================================================
    // 💰 API POS - Guardar Venta
    // ==========================================================
    Route::post('/pos/save-sale', [SaleController::class, 'store'])->name('pos.save-sale');

    // ==========================================================
    // DASHBOARD Y CONFIGURACIÓN
    // ==========================================================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/check-business-type', [DashboardController::class, 'checkBusinessType'])->name('api.check-business-type');
    Route::post('/api/save-business-type', [DashboardController::class, 'saveBusinessType'])->name('api.save-business-type');
    Route::get('/settings/edit', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

    // ==========================================================
    // 👥 GESTIÓN DE CAJEROS
    // ==========================================================
    Route::prefix('cajeros')->name('cashiers.')->group(function () {
        Route::get('/', [CashierController::class, 'index'])->name('index');
        Route::get('/crear', [CashierController::class, 'create'])->name('create');
        Route::post('/', [CashierController::class, 'store'])->name('store');
        Route::get('/api/activos', [CashierController::class, 'getActive'])->name('active');
        Route::get('/{cashier}/editar', [CashierController::class, 'edit'])->name('edit');
        Route::put('/{cashier}', [CashierController::class, 'update'])->name('update');
        Route::delete('/{cashier}', [CashierController::class, 'destroy'])->name('destroy');
        Route::get('/{cashier}', [CashierController::class, 'show'])->name('show');
    });

    // ==========================================================
    // 💰 GESTIÓN DE CAJA
    // ==========================================================
    Route::prefix('caja')->name('cash-register.')->group(function () {
        Route::get('/', [CashRegisterController::class, 'index'])->name('index');
        Route::get('/abrir', [CashRegisterController::class, 'create'])->name('create');
        Route::post('/abrir', [CashRegisterController::class, 'store'])->name('store');
        Route::get('/api/status', [CashRegisterController::class, 'status'])->name('status');
        Route::get('/api/history', [CashRegisterController::class, 'getHistory'])->name('history');
        Route::get('/{id}/cerrar', [CashRegisterController::class, 'closeForm'])->name('close-form');
        Route::post('/{id}/cerrar', [CashRegisterController::class, 'close'])->name('close');
        Route::put('/{id}', [CashRegisterController::class, 'update'])->name('update');
        Route::delete('/{id}', [CashRegisterController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [CashRegisterController::class, 'show'])->name('show');
    });

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

    // Rutas para Reportes
    Route::prefix('reports')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('reports.index');
        Route::post('/corte-caja', [ReportController::class, 'corteCaja'])->name('reports.corte-caja');
    });
}); // Fin del grupo de rutas protegidas con middleware auth