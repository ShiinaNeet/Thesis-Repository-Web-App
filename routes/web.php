<?php

use App\Http\Controllers\AuthorsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\KeywordsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ThesisController;
use App\Http\Controllers\UsersController;
use Illuminate\Auth\Events\Logout;

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
Route::get('/logout', [UsersController::class, 'logout']);
Route::get('/dashboard', [PageController::class, 'dashboard']);


// for auth
Route::post('/login', [UsersController::class, 'login']);
Route::post('/getUsers', [UsersController::class, 'getusers']);
Route::post('/register', [UsersController::class, 'register']);
Route::post('/search', [ThesisController::class,'search']);

Route::group(['middleware' => 'check_auth'], function () {
    Route::get('search', [PageController::class, 'thesisSearch']);
    Route::get('accounts/get', [UsersController::class, 'GetUsers']);
    Route::get('author/get', [AuthorsController::class, 'get']);
    Route::get('keyword/get', [KeywordsController::class, 'get']);
    Route::get('category/get', [CategoryController::class, 'get']);
    Route::get('thesis/get', [ThesisController::class, 'get']);
    Route::get('thesisq/{id}', [ThesisController::class, 'getThesisById']);
    Route::get('dashboard/get', [PageController::class, 'dashboardData']);
    Route::get('videos/{videoname}', [ThesisController::class, 'getVideo']);
    Route::get('pdf/{pdfname}', [ThesisController::class, 'getPdf']);
  
   

    Route::prefix('keyword')->group(function () {
        Route::post('delete', [KeywordsController::class, 'delete']);
        Route::post('disable', [KeywordsController::class, 'disable']);
        Route::post('save', [KeywordsController::class, 'save']);
        Route::post('enable', [KeywordsController::class, 'enable']);
      
    });
    Route::post('database/import', [DatabaseController::class, 'import']);
    Route::get('database/export', [DatabaseController::class, 'export']);
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
 

    Route::post('abc', [ThesisController::class, 'upload']);
    Route::post('logout',[UsersController::class, 'logout']);
    Route::prefix('thesis')->group(function () {
        Route::post('delete', [ThesisController::class, 'delete']);
        Route::post('disable', [ThesisController::class, 'disable']);
        Route::post('save', [ThesisController::class, 'save']);
        Route::post('enable', [ThesisController::class, 'enable']);
      
    });
    

});
