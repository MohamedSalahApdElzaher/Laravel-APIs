<?php

use App\Http\Controllers\API\ApiController;
use App\Http\Controllers\Sanctum\ProjectController;
use App\Http\Controllers\Sanctum\StudentController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// REST API ENDPOINTS (NO AUHTNTICATION NEEDED)

Route::get('list-employees', [ApiController::class, 'listEmployees']);

Route::get('get-employee/{id}', [ApiController::class, 'getEmployee']);

Route::post('create-employee', [ApiController::class, 'createEmployee']);

Route::put('update-employee/{id}', [ApiController::class, 'updateEmployee']);

Route::delete('delete-employee/{id}', [ApiController::class, 'deleteEmployee']);



// SANCTUM APIs ENDPOINTS (NO AUHTNTICATION NEEDED)

Route::post('register', [StudentController::class, 'register']);
Route::post('login', [StudentController::class, 'login']);


// SANCTUM APIs ENDPOINTS (AUHTNTICATION NEEDED)

Route::group(['middleware' => ['auth:sanctum']], function(){

    // for student model

    Route::get('profile', [StudentController::class, 'profile']);
    Route::get('logout', [StudentController::class, 'logout']);

    // for project model

    Route::post('create-project', [ProjectController::class, 'createProject']);
    Route::get('list-project', [ProjectController::class, 'listProject']);
    Route::get('single-project/{id}', [ProjectController::class, 'singleProject']);
    Route::delete('delete-project/{id}', [ProjectController::class, 'delete']);

});

