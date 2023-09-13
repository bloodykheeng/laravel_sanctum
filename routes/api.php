<?php

use App\Http\Controllers\API\DataSetController;
use App\Http\Controllers\API\DataSetDetailController;
use App\Http\Controllers\API\DataSetElementDetailController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public Routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('forgot-password', [PasswordResetController::class, 'forgetPassword']);
Route::get('/reset-password', [PasswordResetController::class, 'handleresetPasswordLoad']);
Route::post('/reset-password', [PasswordResetController::class, 'handlestoringNewPassword']);

Route::get('/fetchAndStoreDataSetDetail', [DataSetDetailController::class, 'fetchAndStoreDataSetDetail']);
Route::get('/fetchAndstoreDataElementsUnderDataSet', [DataSetElementDetailController::class, 'fetchAndstoreDataElementsUnderDataSet'])->name('fetchAndstoreDataElementsUnderDataSet');


Route::get('/getAllDataSetsDetails', [DataSetDetailController::class, 'showAllDatasetDetails']);
Route::get('/dataSetDetailsWithTheredataElementDetails', [DataSetDetailController::class, 'showAllDatasetDetailsWithDataElementDetails']);

// private routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('tasks', TasksController::class);
    Route::get('logout', [AuthController::class, 'logout']);
});