<?php

use App\Http\Controllers\AuthorsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\KeywordsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ThesisController;
use App\Http\Controllers\UsersController;
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

Route::get('/', [PageController::class, 'index']);
Route::get('/login', [PageController::class, 'login']);
Route::get('/dashboard', [PageController::class, 'dashboard']);
// for auth
Route::post('/login', [UsersController::class, 'login']);
Route::post('/getUsers', [UsersController::class, 'getusers']);
Route::post('/register', [UsersController::class, 'register']);

Route::group(['middleware' => 'check_auth'], function () {
    
    Route::get('author/get', [AuthorsController::class, 'get']);
    Route::get('keyword/get', [KeywordsController::class, 'get']);
    Route::get('category/get', [CategoryController::class, 'get']);
    Route::get('thesis/get', [ThesisController::class, 'get']);

    Route::prefix('keyword')->group(function () {
        Route::post('delete', [KeywordsController::class, 'delete']);
        Route::post('disable', [KeywordsController::class, 'disable']);
        Route::post('save', [KeywordsController::class, 'save']);
        Route::post('enable', [KeywordsController::class, 'enable']);
      
    });

    Route::prefix('author')->group(function () {
        Route::post('delete', [AuthorsController::class, 'delete']);
        Route::post('disable', [AuthorsController::class, 'disable']);
        Route::post('save', [AuthorsController::class, 'save']);
        Route::post('enable', [AuthorsController::class, 'enable']);
      
    });

    Route::prefix('category')->group(function () {
        Route::post('delete', [CategoryController::class, 'delete']);
        Route::post('disable', [CategoryController::class, 'disable']);
        Route::post('save', [CategoryController::class, 'save']);
        Route::post('enable', [CategoryController::class, 'enable']);
      
    });

    Route::prefix('thesis')->group(function () {
        Route::post('delete', [ThesisController::class, 'delete']);
        Route::post('disable', [ThesisController::class, 'disable']);
        Route::post('save', [ThesisController::class, 'save']);
        Route::post('enable', [ThesisController::class, 'enable']);
      
    });


});
