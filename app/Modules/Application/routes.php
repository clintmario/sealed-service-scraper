<?php
/**
 * Created by PhpStorm.
 * User: ClintMario
 * Date: 9/5/2016
 * Time: 1:47 PM
 */

Route::group(['middleware' => ['web']], function () {

    // Library
    Route::get('/library', 'App\Modules\Application\Controllers\LibraryController@index');
    Route::get('/courses', 'App\Modules\Application\Controllers\LibraryController@courses');
    Route::get('/tags', 'App\Modules\Application\Controllers\LibraryController@tags');
    Route::get('/assignments', 'App\Modules\Application\Controllers\LibraryController@assignments');
    Route::get('/tracks', 'App\Modules\Application\Controllers\LibraryController@tracks');
    Route::get('/lessons', 'App\Modules\Application\Controllers\LibraryController@lessons');

    // Logged In Home
    Route::get('/home', 'App\Modules\Application\Controllers\ApplicationController@index');
    Route::get('/home/completed_assignments', 'App\Modules\Application\Controllers\ApplicationController@completedAssignments');

    // Contact page
    Route::get('/contact', 'App\Modules\Application\Controllers\ApplicationController@contactUs');
    Route::post('/contact', 'App\Modules\Application\Controllers\ApplicationController@contactUs');

    // Lesson page
    Route::get('/lesson', 'App\Modules\Application\Controllers\ApplicationController@lesson');
    Route::post('/watch_lesson', 'App\Modules\Application\Controllers\ApplicationController@watchLesson');
    Route::get('/next_lesson', 'App\Modules\Application\Controllers\ApplicationController@nextLesson');
    Route::get('/next_lesson_in_assignment', 'App\Modules\Application\Controllers\ApplicationController@nextLessonInAssignment');
});

