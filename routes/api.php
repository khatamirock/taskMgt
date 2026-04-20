<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
//
Route::get('/users/count', function (){

        $users = User::all();
        return ['users'=>$users, 'count'=> User::count()];
    }
);



// Projects Route
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/show/{id}',[ProjectController::class,'show']);
    
    Route::post('/projects',                  [ProjectController::class, 'store']);
    Route::delete('/projects/{project}',      [ProjectController::class, 'destroy']);
    Route::post('/projects/{project}/assign', [ProjectController::class, 'assignMember']);

});


//task routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus']);
    Route::get('/tasks/{task}',[TaskController::class,'show']);
    Route::patch('/tasks/{task}',[TaskController::class,'update']);
});

