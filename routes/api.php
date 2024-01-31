<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\V1\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', [AuthController::class, 'login'])->name('login');;
        Route::post('register', [AuthController::class, 'register'])->name('register');

        Route::group(['middleware' => 'auth:sanctum'], function () {
            Route::get('logout', [AuthController::class, 'logout'])->name('logout');
            Route::get('user', [AuthController::class, 'user'])->name('user');
        });

    });

    Route::group(['namespace' => 'V1', 'middleware' => 'auth:sanctum'], function () {
        Route::group(['prefix' => 'task'], function () {

            // Route::resource('task', 'TaskController');
            Route::get('/', [TaskController::class, 'index'])->name('api.task.index');
            Route::post('/store', [TaskController::class, 'store'])->name('api.task.store');
            Route::get('/{id}', [TaskController::class, 'view'])->whereNumber('id')->name('api.task.update');
            Route::post('/{id}', [TaskController::class, 'update'])->whereNumber('id')->name('api.task.update');
            Route::post('/mark-complete/{id}', [TaskController::class, 'markAsComplete'])->whereNumber('id')->name('api.task.complete');
            Route::delete('/{id}', [TaskController::class, 'destroy'])->whereNumber('id')->name('api.task.destroy');
        });

    });

});
