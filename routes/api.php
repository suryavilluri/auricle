<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Apis;


Route::post('register', [Apis\UserController::class, 'register']);
Route::post('login', [Apis\UserController::class, 'login']);

Route::middleware(['auth:api'])->group(function () {
    Route::get('user', [Apis\UserController::class, 'user']);
    Route::post('logout', [Apis\UserController::class, 'logout']);

    // courses
    Route::post('create-course',  [Apis\CourseController::class, 'createCourse']);
    Route::post('update-course/{id}',  [Apis\CourseController::class, 'updateCourse']);
    Route::delete('delete-course/{id}', [Apis\CourseController::class, 'deleteCourse']);
    // lessons
    Route::post('create-lesson',  [Apis\LessonController::class, 'createLesson']);
    Route::post('update-lesson/{id}',  [Apis\LessonController::class, 'updateLesson']);
    Route::delete('delete-lesson/{id}', [Apis\LessonController::class, 'deleteLesson']);
});

Route::get('get-all-courses',  [Apis\CourseController::class, 'allCourses']);
Route::get('get-course/{id}',  [Apis\CourseController::class, 'getCourse']);
Route::get('get-all-lessons',  [Apis\LessonController::class, 'allLessons']);
Route::get('get-lesson/{id}',  [Apis\LessonController::class, 'getLesson']);

