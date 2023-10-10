<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\NoteGroupController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskGroupController;
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

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');

    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('notes')->group(function () {
        Route::controller(NoteGroupController::class)->group(function () {
            Route::post('/', 'getNoteGroups');
            Route::post('/add-new-group', 'addNoteGroup');
        });

        Route::prefix('{note_group:id}')->group(function () {
            Route::controller(NoteGroupController::class)->group(function () {
                Route::post('/', 'getNoteGroup');
                Route::post('/update', 'updateNoteGroup');
                Route::post('/delete', 'deleteNoteGroup');
            });

            Route::controller(NoteController::class)->group(function () {
                Route::post('/create', 'addNote');
                Route::post('/{note:id}', 'getNote');
                Route::post('/{note:id}/update', 'updateNote');
                Route::post('/{note:id}/delete', 'deleteNote');
            });
        });
    });

    Route::prefix('tasks')->group(function () {
        Route::controller(TaskGroupController::class)->group(function () {
            Route::post('/', 'getTaskGroups');
            Route::post('/add-new-group', 'addTaskGroup');
        });

        Route::prefix('{task_group:id}')->group(function () {
            Route::controller(TaskGroupController::class)->group(function () {
                Route::post('/', 'getTaskGroup');
                Route::post('/update', 'updateTaskGroup');
                Route::post('/delete', 'deleteTaskGroup');
            });

            Route::controller(TaskController::class)->group(function () {
                Route::post('/create', 'addTask');
                Route::post('/{task:id}', 'getTask');
                Route::post('/{task:id}/update', 'updateTask');
                Route::post('/{task:id}/delete', 'deleteTask');
            });
        });
    });
});
