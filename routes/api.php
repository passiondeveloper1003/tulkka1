<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Zoom\MeetingController;

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

Route::get('/meetings', [MeetingController::class,'list']);

// Create meeting room using topic, agenda, start_time.
Route::post('/meetings', [MeetingController::class,'create']);

// Get information of the meeting room by ID.
Route::get('/meetings/{id}', [MeetingController::class,'get'])->where('id', '[0-9]+');
Route::patch('/meetings/{id}', [MeetingController::class,'update'])->where('id', '[0-9]+');
Route::delete('/meetings/{id}', [MeetingController::class,'delete'])->where('id', '[0-9]+');

Route::group(['prefix'=>'paypal'], function () {
    Route::post('/order/create', [\App\Http\Controllers\PaypalPaymentController::class,'create']);
    Route::post('/order/capture/', [\App\Http\Controllers\PaypalPaymentController::class,'capture']);
});

Route::group(['prefix' => '/development'], function () {
    Route::get('/', function () {
        return 'api test';
    });

    Route::middleware('api') ->group(base_path('routes/api/auth.php'));

    Route::namespace('Web')->group(base_path('routes/api/guest.php'));

    Route::prefix('panel')->middleware('api.auth')->namespace('Panel')->group(base_path('routes/api/user.php'));

    Route::group(['namespace' => 'Config', 'middleware' => []], function () {
        Route::get('/config', ['uses' => 'ConfigController@list']);
    });

    Route::prefix('instructor')->middleware(['api.auth', 'api.level-access:teacher'])->namespace('Instructor')->group(base_path('routes/api/instructor.php'));
});
