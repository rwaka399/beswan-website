<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\FinancialLogController;
use App\Http\Controllers\LessonPackageController;
use App\Http\Controllers\Master\RoleController;
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\Master\MenuController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeacherAttendanceController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ViewController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', [ViewController::class, 'index'])->name('home');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerPost'])->name('register.post');

// SSO Routes
Route::get('/auth/{provider}', [AuthController::class, 'redirectToProvider'])->name('auth.provider');
Route::get('/auth/{provider}/callback', [AuthController::class, 'handleProviderCallback']);

Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('/profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile-index')->middleware('permission:profile,read');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile-edit')->middleware('permission:profile,update');
        Route::put('/update', [ProfileController::class, 'update'])->name('profile-update')->middleware('permission:profile,update');
        Route::get('/history', [TransactionController::class, 'invoiceHistory'])->name('history')->middleware('permission:history_transaksi,read');
    });

    Route::prefix('/master')->middleware('role:Admin,Guru')->group(function () {
        Route::get('/', [ViewController::class, 'dashboard'])->name('dashboard')->middleware('permission:dashboard,read');

        Route::prefix('/user')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('user-index')->middleware('permission:user,read');
            Route::get('/create', [UserController::class, 'create'])->name('user-create')->middleware('permission:user,create');
            Route::post('/store', [UserController::class, 'store'])->name('user-store')->middleware('permission:user,create');
            Route::get('/edit/{id}', [UserController::class, 'edit'])->name('user-edit')->middleware('permission:user,update');
            Route::put('/update/{id}', [UserController::class, 'update'])->name('user-update')->middleware('permission:user,update');
            Route::delete('/destroy/{id}', [UserController::class, 'destroy'])->name('user-destroy')->middleware('permission:user,delete');
            Route::get('/get-cities', [UserController::class, 'getCities'])->name('get-cities');
            Route::get('/get-kecamatan', [UserController::class, 'getKecamatan'])->name('get-kecamatan');
        });


        Route::prefix('/role')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('role-index')->middleware('permission:role,read');
            Route::get('/create', [RoleController::class, 'create'])->name('role-create')->middleware('permission:role,create');
            Route::post('/store', [RoleController::class, 'store'])->name('role-store')->middleware('permission:role,create');
            Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('role-edit')->middleware('permission:role,update');
            Route::put('/update/{id}', [RoleController::class, 'update'])->name('role-update')->middleware('permission:role,update');
            Route::delete('/destroy/{id}', [RoleController::class, 'destroy'])->name('role-destroy')->middleware('permission:role,delete');
        });

        Route::prefix('/menu')->group(function () {
            Route::get('/', [MenuController::class, 'index'])->name('menu-index')->middleware('permission:menu,read');
            Route::get('/create', [MenuController::class, 'create'])->name('menu-create')->middleware('permission:menu,create');
            Route::post('/store', [MenuController::class, 'store'])->name('menu-store')->middleware('permission:menu,create');
            Route::get('/show/{id}', [MenuController::class, 'show'])->name('menu-show')->middleware('permission:menu,read');
            Route::get('/edit/{id}', [MenuController::class, 'edit'])->name('menu-edit')->middleware('permission:menu,update');
            Route::put('/update/{id}', [MenuController::class, 'update'])->name('menu-update')->middleware('permission:menu,update');
            Route::delete('/destroy/{id}', [MenuController::class, 'destroy'])->name('menu-destroy')->middleware('permission:menu,delete');
            Route::get('/tree', [MenuController::class, 'getMenuTree'])->name('menu-tree');
            Route::get('/sub-menus/{parentId}', [MenuController::class, 'getSubMenus'])->name('menu-sub-menus');
        });

        Route::prefix('/lesson_package')->group(function () {
            Route::get('/', [LessonPackageController::class, 'index'])->name('lesson-package-index')->middleware('permission:lesson_package,read');
            Route::get('/create', [LessonPackageController::class, 'create'])->name('lesson-package-create')->middleware('permission:lesson_package,create');
            Route::post('/store', [LessonPackageController::class, 'store'])->name('lesson-package-store')->middleware('permission:lesson_package,create');
            Route::get('/edit/{id}', [LessonPackageController::class, 'edit'])->name('lesson-package-edit')->middleware('permission:lesson_package,update');
            Route::put('/update/{id}', [LessonPackageController::class, 'update'])->name('lesson-package-update')->middleware('permission:lesson_package,update');
            Route::delete('/destroy/{id}', [LessonPackageController::class, 'destroy'])->name('lesson-package-destroy')->middleware('permission:lesson_package,delete');
        });

        Route::prefix('/financial')->group(function () {
            Route::get('/', [FinancialLogController::class, 'index'])->name('financial-index')->middleware('permission:keuangan-log,read');
            Route::get('/create', [FinancialLogController::class, 'create'])->name('financial-create')->middleware('permission:keuangan-tambah,create');
            Route::post('/store', [FinancialLogController::class, 'store'])->name('financial-store')->middleware('permission:keuangan-tambah,create');
            Route::get('/show/{id}', [FinancialLogController::class, 'show'])->name('financial-show')->middleware('permission:keuangan-log,read');
            Route::get('/edit/{id}', [FinancialLogController::class, 'edit'])->name('financial-edit')->middleware('permission:keuangan-log,update');
            Route::put('/update/{id}', [FinancialLogController::class, 'update'])->name('financial-update')->middleware('permission:keuangan-log,update');
            Route::delete('/destroy/{id}', [FinancialLogController::class, 'destroy'])->name('financial-destroy')->middleware('permission:keuangan-log,delete');
            Route::get('/report', [FinancialLogController::class, 'report'])->name('financial-report')->middleware('permission:laporan-keuangan,read');
            Route::get('/export', [FinancialLogController::class, 'export'])->name('financial-export')->middleware('permission:laporan-keuangan,read');
            Route::get('/dashboard', [FinancialLogController::class, 'dashboard'])->name('financial-dashboard')->middleware('permission:keuangan-dashboard,read');
        });

        // Attendance Routes for Admin
        Route::prefix('/attendance')->name('master.attendance.')->group(function () {
            Route::get('/', [AttendanceController::class, 'index'])->name('index')->middleware('permission:attendance_master,read');
            Route::get('/create', [AttendanceController::class, 'create'])->name('create')->middleware('permission:attendance_master,create');
            Route::post('/store', [AttendanceController::class, 'store'])->name('store')->middleware('permission:attendance_master,create');
            Route::get('/show/{attendance}', [AttendanceController::class, 'show'])->name('show')->middleware('permission:attendance_master,read');
            Route::patch('/close/{attendance}', [AttendanceController::class, 'close'])->name('close')->middleware('permission:attendance_master,update');
            Route::patch('/reopen/{attendance}', [AttendanceController::class, 'reopen'])->name('reopen')->middleware('permission:attendance_master,update');
        });
    });

    // Teacher Attendance Routes
    Route::prefix('/guru')->name('teacher.')->group(function () {
        Route::prefix('/attendance')->name('attendance.')->group(function () {
            Route::get('/', [TeacherAttendanceController::class, 'index'])->name('index')->middleware('permission:attendance_guru,read');
            Route::post('/check-in', [TeacherAttendanceController::class, 'checkIn'])->name('check-in')->middleware('permission:attendance_guru,create');
            Route::get('/history', [TeacherAttendanceController::class, 'history'])->name('history')->middleware('permission:attendance_guru,read');
        });
    });

    Route::prefix('/transaction')->group(function () {
        Route::get('/checkout/{lessonPackageId}', [TransactionController::class, 'showCheckout'])->name('transaction.checkout');
        Route::post('/create-invoice', [TransactionController::class, 'createInvoice'])->name('transaction.create-invoice');
        Route::post('/check-status', [TransactionController::class, 'checkPaymentStatus'])->name('transaction.check-status');
        Route::get('/success', [TransactionController::class, 'success'])->name('transaction.success');
        Route::get('/failed', [TransactionController::class, 'failed'])->name('transaction.failed');
    });
});

// Webhooks (no auth required)
Route::post('/xendit/webhook', [TransactionController::class, 'handleWebhook'])->name('xendit.webhook');

// Debug routes (only accessible in development or with debug key)
Route::get('/xendit-test/logs', [TransactionController::class, 'webhookLogs'])->name('xendit.logs');

// Legacy webhook route untuk compatibility
Route::post('/transaction/webhook', [TransactionController::class, 'handleWebhook']);

