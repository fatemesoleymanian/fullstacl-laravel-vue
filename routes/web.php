<?php

use Illuminate\Support\Facades\Route;

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

//Route::get('/', function () {
//    return view('welcome');
//});
Route::prefix('app')->middleware(\App\Http\Middleware\adminCheck::class)->group(function (){
    Route::post('/create_tag',[\App\Http\Controllers\AdminController::class , 'addTag']);
    Route::get('/get_tags',[\App\Http\Controllers\AdminController::class , 'getTag']);
    Route::post('/edit_tag',[\App\Http\Controllers\AdminController::class , 'editTag']);
    Route::post('/delete_tag',[\App\Http\Controllers\AdminController::class , 'deleteTag']);
    Route::post('/upload',[\App\Http\Controllers\AdminController::class , 'upload']);
    Route::post('/delete_image',[\App\Http\Controllers\AdminController::class , 'deleteImage']);
    Route::post('/create_category',[\App\Http\Controllers\AdminController::class , 'addCategory']);
    Route::get('/get_categories',[\App\Http\Controllers\AdminController::class , 'getCategory']);
    Route::post('/edit_category',[\App\Http\Controllers\AdminController::class , 'editCategory']);
    Route::post('/delete_category',[\App\Http\Controllers\AdminController::class , 'deleteCategory']);
    Route::post('/create_useradmin',[\App\Http\Controllers\AdminController::class , 'addAdmin']);
    Route::get('/get_useradmin',[\App\Http\Controllers\AdminController::class , 'getAdmin']);
    Route::post('/edit_useradmin',[\App\Http\Controllers\AdminController::class , 'editAdmin']);
    Route::post('/delete_useradmin',[\App\Http\Controllers\AdminController::class , 'deleteAdmin']);
    Route::post('/admin_login',[\App\Http\Controllers\AdminController::class , 'adminLogin']);

    Route::post('/create_role',[\App\Http\Controllers\AdminController::class , 'addRole']);
    Route::post('/edit_role',[\App\Http\Controllers\AdminController::class , 'editRole']);
    Route::post('/delete_role',[\App\Http\Controllers\AdminController::class , 'deleteRole']);
    Route::get('/get_roles',[\App\Http\Controllers\AdminController::class , 'getRoles']);
});


Route::get('/logout',[\App\Http\Controllers\AdminController::class , 'logout']);
Route::get('/{any?}',[\App\Http\Controllers\AdminController::class , 'index']);
//Route::get('/',[\App\Http\Controllers\AdminController::class , 'index']);



//Route::get('/{any?}', function () {
//    return view('welcome');
//});
