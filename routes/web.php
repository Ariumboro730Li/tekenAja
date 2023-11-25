<?php

use App\Http\Controllers\Auth\Manual\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
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


Auth::routes();

Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        return redirect('/home');
    });
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('logout', [LoginController::class, 'logout']);
    Route::controller(BookController::class)->group(function () {
        Route::prefix('books')->group(function () {
            Route::get('/', 'page')->name('book.page');
            Route::get('/id/{id}', 'fetchByid')->name('book.fetch-by-id');
            Route::post('/', 'store')->name('book.store');
            Route::put('/', 'update')->name('book.update');
            Route::delete('/{id}', 'delete')->name('book.delete');
            Route::get('datatable', 'datatable')->name('book.datatable');
        });
    });
    Route::controller(AuthorController::class)->group(function () {
        Route::prefix('authors')->group(function(){
            Route::get('/', 'page')->name('author.page');
            Route::get('/list', 'list')->name('author.list');
            Route::get('/id/{id}', 'fetchByid')->name('author.fetch-by-id');
            Route::post('/', 'store')->name('author.store');
            Route::put('/', 'update')->name('author.update');
            Route::delete('/{id}', 'delete')->name('author.delete');
            Route::get('datatable', 'datatable')->name('author.datatable');
        });
    });
    Route::group(['middleware' => 'admin-only'], function () {
        Route::controller(UserController::class)->group(function () {
            Route::prefix('users')->group(function(){
                Route::get('/', 'page')->name('user.page');
                Route::get('/id/{id}', 'fetchByid')->name('user.fetch-by-id');
                Route::post('/', 'store')->name('user.store');
                Route::post('/activate/{id}', 'activate')->name('user.activate');
                Route::put('/', 'update')->name('user.update');
                Route::delete('/{id}', 'delete')->name('user.delete');
                Route::get('datatable', 'datatable')->name('user.datatable');
                Route::get('roles/list', 'rolesList')->name('user.roles.list');
            });
        });
    });

});

