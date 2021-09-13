<?php

use App\Http\Controllers\Task\TaskController;
use Illuminate\Http\Request;
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


Route::group(
    ['prefix' => 'task'],
    function () {
        Route::get('/organize', [TaskController::class, 'organize']);
        Route::get('/get-tasks', [TaskController::class, 'getTasks']);
    }
);
