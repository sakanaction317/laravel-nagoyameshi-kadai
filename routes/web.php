<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\RestaurantController as AdminRestaurantController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CompanyController  as AdminCompanyController;
use App\Http\Controllers\Admin\TermController as AdminTermController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;

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
/*Route::get('/', function () {
    return view('welcome');
});
*/

require __DIR__ . '/auth.php';

Route::get('/', [HomeController::class, 'index'])->name('home');

/*管理者としてログインしている状態でのみアクセスできるように認可を設定*/
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'auth:admin'], function () {
    Route::get('home', [Admin\HomeController::class, 'index'])->name('home');
    Route::resource('users', Admin\UserController::class)->only(['index', 'show']);
    Route::resource('restaurants', Admin\RestaurantController::class)->only(['index', 'show', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('categories', Admin\CategoryController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('company', Admin\CompanyController::class)->only(['index', 'edit', 'update']);
    Route::resource('terms', Admin\TermController::class)->only(['index', 'edit', 'update']);

    /*個別で指定したい場合
    Route::get('users', [Admin\UserController::class, 'index'])->name('users.index');
Route::get('users/{user}', [Admin\UserController::class, 'show'])->name('users.show');
*/
});

/*管理者としてログインしていない状態でのみアクセスできるように認可を設定*/
Route::group(['middleware' => 'guest:admin'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
   

    /*ログイン済*/
    Route::group(['middleware' => ['auth']], function () {
        Route::resource('user', UserController::class)->only(['index', 'edit', 'update']);
       

    });
});