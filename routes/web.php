<?php

use App\Events\ServerCreated;
use App\Events\Transaction;
use App\Http\Controllers\cms\BannerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\cms\AuthController;
use App\Http\Controllers\cms\GameController;
use App\Http\Controllers\cms\UserController;
use App\Http\Controllers\cms\PriceController;
use App\Http\Controllers\cms\CountryController;
use App\Http\Controllers\cms\PaymentController;
use App\Http\Controllers\cms\CategoryController;
use App\Http\Controllers\cms\NavigationController;
use App\Http\Controllers\cms\PricePointController;
use App\Http\Controllers\cms\UserAccessController;
use App\Http\Controllers\cms\TransactionController;

// use Illuminate\Http\Request;

/* ============ Frontend Controller ============ */
use App\Http\Controllers\frontend\HomeController as HomeFrontend;
use App\Http\Controllers\frontend\PaymentController as PaymentFrontend;
use App\Http\Controllers\frontend\GameController as GameFrontend;
use App\Http\Controllers\frontend\TransactionController as FrontendTransactionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeFrontend::class, 'index'])->name('home');
Route::get('/games', [GameFrontend::class, 'index'])->name('games');
Route::prefix('payment')->group(function () {
    Route::get('/invoice', [PaymentFrontend::class, 'checkInvoice'])->name('payment');
    Route::prefix('confirmation')->group(function () {
        Route::get('/', [PaymentFrontend::class, 'confirmation'])->name('payment.confirmation');
        Route::post('/', [PaymentFrontend::class, 'infoPayment'])->name('payment.confirmation.info');
        Route::post('/va', [PaymentFrontend::class, 'vaPayment'])->name('payment.confirmation.va');
    });
    Route::get('/{slug}', [PaymentFrontend::class, 'index'])->name('payment.games');
});
Route::post('/payment-vendor/{code}', [PaymentFrontend::class, 'parseToVendor'])->name('payment.parse.vendor');
Route::post('/transaction', [FrontendTransactionController::class, 'transaction'])->name('payment.transaction');

Route::get('/administrator/login', [AuthController::class, 'index'])->name('login');
Route::post('/administrator/login', [AuthController::class, 'login'])->name('auth.login');

Route::get('/administrator/register', [AuthController::class, 'register'])->name('register');
Route::post('/administrator/register', [AuthController::class, 'store'])->name('auth.register');



Route::middleware(['auth', 'access'])->group(function () {

    Route::get('/administrator', function () {
        $title = "Dashboard";
        return view('cms.pages.dashboard.index', compact('title'));
    })->name('cms.dashboard');

    // navigation
    Route::get('/administrator/navigation', [NavigationController::class, 'index'])->name('cms.navigation');
    Route::get('/administrator/navigation/{id}', [NavigationController::class, 'changeStatus'])->name('cms.navigation.status');
    Route::post('/administrator/navigation', [NavigationController::class, 'store'])->name('cms.navigation.store');
    Route::patch('/administrator/navigation', [NavigationController::class, 'update'])->name('cms.navigation.update');
    Route::delete('/administrator/navigation/delete', [NavigationController::class, 'destroy'])->name('cms.navigation.delete');


    // user
    Route::get('/administrator/user', [UserController::class, 'index'])->name('cms.user');
    Route::post('/administrator/user', [UserController::class, 'store'])->name('cms.user.store');
    Route::patch('/administrator/user', [UserController::class, 'update'])->name('cms.user.update');
    Route::delete('/administrator/user', [UserController::class, 'destroy'])->name('cms.user.delete');

    Route::get('/administrator/user/{id}', [UserController::class, 'changeStatus'])->name('cms.user.status');


    // user-access
    Route::get('/administrator/user-access', [UserAccessController::class, 'index'])->name('cms.user-access');
    Route::post('/administrator/user-access', [UserAccessController::class, 'store'])->name('cms.user-access.store');
    Route::patch('/administrator/user-access', [UserAccessController::class, 'update'])->name('cms.user-access.update');
    Route::delete('/administrator/user-access', [UserAccessController::class, 'destroy'])->name('cms.user-access.delete');

    Route::get('/administrator/user-access/{id}', [UserAccessController::class, 'access'])->name('cms.user-access.access');
    Route::post('/administrator/user-access/checked', [UserAccessController::class, 'checked'])->name('cms.user-access.checked');


    // Country
    Route::get('/administrator/country', [CountryController::class, 'index'])->name('cms.country');
    Route::post('/administrator/country', [CountryController::class, 'store'])->name('cms.country.store');
    Route::patch('/administrator/country', [CountryController::class, 'update'])->name('cms.country.update');
    Route::delete('/administrator/country', [CountryController::class, 'destroy'])->name('cms.country.delete');


    // Category
    Route::get('/administrator/category', [CategoryController::class, 'index'])->name('cms.category');
    Route::post('/administrator/category', [CategoryController::class, 'store'])->name('cms.category.store');
    Route::patch('/administrator/category', [CategoryController::class, 'update'])->name('cms.category.update');
    Route::delete('/administrator/category', [CategoryController::class, 'destroy'])->name('cms.category.delete');


    // Payment Type
    Route::get('/administrator/payment', [PaymentController::class, 'index'])->name('cms.payment');
    Route::post('/administrator/payment', [PaymentController::class, 'store'])->name('cms.payment.store');
    Route::patch('/administrator/payment', [PaymentController::class, 'update'])->name('cms.payment.update');
    Route::delete('/administrator/payment', [PaymentController::class, 'destroy'])->name('cms.payment.delete');

    // Game Type
    Route::get('/administrator/game', [GameController::class, 'index'])->name('cms.game');
    Route::post('/administrator/game', [GameController::class, 'store'])->name('cms.game.store');
    Route::patch('/administrator/game', [GameController::class, 'update'])->name('cms.game.update');
    Route::delete('/administrator/game', [GameController::class, 'destroy'])->name('cms.game.delete');

    // price list
    Route::get('/administrator/price', [PriceController::class, 'index'])->name('cms.price');
    Route::get('/administrator/price/add', [PriceController::class, 'add'])->name('cms.price.add');
    Route::post('/administrator/price', [PriceController::class, 'store'])->name('cms.price.store');

    Route::post('/administrator/price/addAll', [PriceController::class, 'addAll'])->name('cms.price.addAll');

    Route::patch('/administrator/price', [PriceController::class, 'update'])->name('cms.price.update');
    Route::delete('/administrator/price', [PriceController::class, 'destroy'])->name('cms.price.delete');
    Route::post('/administrator/price/import', [PriceController::class, 'import'])->name('cms.price.import');


    // set PPN

    // logout
    Route::get('/administrator/logout', [AuthController::class, 'logout'])->name('logout');

    // transaction
    Route::get('/administrator/transaction', [TransactionController::class, 'index'])->name('cms.transaction');
    Route::post('/administrator/transaction/check', [TransactionController::class, 'check'])->name('cms.transaction.check');

    // price point
    Route::get('/administrator/pricepoint', [PricePointController::class, 'index'])->name('cms.pricepoint');
    Route::get('/administrator/pricepoint/add', [PricePointController::class, 'add'])->name('cms.pricepoint.add');
    Route::get('/administrator/pricepoint/{id}', [PricePointController::class, 'list'])->name('cms.pricepoint.list');
    Route::post('/administrator/pricepoint', [PricePointController::class, 'store'])->name('cms.pricepoint.store');
    Route::patch('/administrator/pricepoint', [PricePointController::class, 'update'])->name('cms.pricepoint.update');
    Route::delete('/administrator/pricepoint', [PricePointController::class, 'destroy'])->name('cms.pricepoint.delete');


    // banner
    Route::get('/administrator/banner', [BannerController::class, 'index'])->name('cms.banner');
    Route::post('/administrator/banner', [BannerController::class, 'store'])->name('cms.banner.store');
    Route::patch('/administrator/banner', [BannerController::class, 'update'])->name('cms.banner.update');
    Route::delete('/administrator/banner', [BannerController::class, 'destroy'])->name('cms.banner.delete');
    Route::get('/administrator/banner/{id}', [BannerController::class, 'changeStatus'])->name('cms.banner.status');
});
