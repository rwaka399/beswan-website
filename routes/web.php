<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\FinancialLogController;
use App\Http\Controllers\LessonPackageController;
use App\Http\Controllers\Master\RoleController;
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ViewController;
use Illuminate\Support\Facades\Route;

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

// Route::get('/csrf-token', function () {
//     return response()->json(['csrf_token' => csrf_token()]);
// });


Route::get('/', [ViewController::class, 'index'])->name('home');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost'])->name('login.post');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerPost'])->name('register.post');

Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('/profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile-index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile-edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('profile-update');
        Route::get('/history', [TransactionController::class, 'invoiceHistory'])->name('history');
    });

    Route::prefix('/master')->group(function () {
        Route::get('/', [ViewController::class, 'dashboard'])->name('dashboard');

        Route::prefix('/user')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('user-index');
            Route::get('/create', [UserController::class, 'create'])->name('user-create');
            Route::post('/store', [UserController::class, 'store'])->name('user-store');
            Route::get('/edit/{id}', [UserController::class, 'edit'])->name('user-edit');
            Route::put('/update/{id}', [UserController::class, 'update'])->name('user-update');
            Route::delete('/destroy/{id}', [UserController::class, 'destroy'])->name('user-destroy');
            Route::get('/get-cities', [UserController::class, 'getCities'])->name('get-cities');
            Route::get('/get-kecamatan', [UserController::class, 'getKecamatan'])->name('get-kecamatan');
        });


        Route::prefix('/role')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('role-index');
            Route::get('/create', [RoleController::class, 'create'])->name('role-create');
            Route::post('/store', [RoleController::class, 'store'])->name('role-store');
            Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('role-edit');
            Route::put('/update/{id}', [RoleController::class, 'update'])->name('role-update');
            Route::delete('/destroy/{id}', [RoleController::class, 'destroy'])->name('role-destroy');
        });

        Route::prefix('/menu')->group(function () {
            Route::get('/', [MenuController::class, 'index'])->name('menu-index');
            Route::get('/create', [MenuController::class, 'create'])->name('menu-create');
            Route::post('/store', [MenuController::class, 'store'])->name('menu-store');
            Route::get('/show/{id}', [MenuController::class, 'show'])->name('menu-show');
            Route::get('/edit/{id}', [MenuController::class, 'edit'])->name('menu-edit');
            Route::put('/update/{id}', [MenuController::class, 'update'])->name('menu-update');
            Route::delete('/destroy/{id}', [MenuController::class, 'destroy'])->name('menu-destroy');
            Route::get('/tree', [MenuController::class, 'getMenuTree'])->name('menu-tree');
            Route::get('/sub-menus/{parentId}', [MenuController::class, 'getSubMenus'])->name('menu-sub-menus');
        });

        Route::prefix('/lesson_package')->group(function () {
            Route::get('/', [LessonPackageController::class, 'index'])->name('lesson-package-index');
            Route::get('/create', [LessonPackageController::class, 'create'])->name('lesson-package-create');
            Route::post('/store', [LessonPackageController::class, 'store'])->name('lesson-package-store');
            Route::get('/edit/{id}', [LessonPackageController::class, 'edit'])->name('lesson-package-edit');
            Route::put('/update/{id}', [LessonPackageController::class, 'update'])->name('lesson-package-update');
            Route::delete('/destroy/{id}', [LessonPackageController::class, 'destroy'])->name('lesson-package-destroy');
        });

        Route::prefix('/financial')->group(function () {
            Route::get('/', [FinancialLogController::class, 'index'])->name('financial-index');
            Route::get('/create', [FinancialLogController::class, 'create'])->name('financial-create');
            Route::post('/store', [FinancialLogController::class, 'store'])->name('financial-store');
            Route::get('/show/{id}', [FinancialLogController::class, 'show'])->name('financial-show');
            Route::get('/edit/{id}', [FinancialLogController::class, 'edit'])->name('financial-edit');
            Route::put('/update/{id}', [FinancialLogController::class, 'update'])->name('financial-update');
            Route::delete('/destroy/{id}', [FinancialLogController::class, 'destroy'])->name('financial-destroy');
            Route::get('/report', [FinancialLogController::class, 'report'])->name('financial-report');
            Route::get('/export', [FinancialLogController::class, 'export'])->name('financial-export');
            Route::get('/dashboard', [FinancialLogController::class, 'dashboard'])->name('financial-dashboard');
        });
    });

    Route::prefix('/transaction')->group(function () {
        Route::get('/checkout/{lessonPackageId}', [TransactionController::class, 'showCheckout'])->name('transaction.checkout');
        Route::post('/create-invoice', [TransactionController::class, 'createInvoice'])->name('transaction.create-invoice');
        Route::get('/success', [TransactionController::class, 'success'])->name('transaction.success');
        Route::get('/failed', [TransactionController::class, 'failed'])->name('transaction.failed');
    });
});


Route::post('/xendit/webhook', [TransactionController::class, 'handleWebhook'])->name('xendit.webhook');
