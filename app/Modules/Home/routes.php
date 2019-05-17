<?php
/**
 * Created by PhpStorm.
 * User: ClintMario
 * Date: 9/5/2016
 * Time: 1:47 PM
 */

Route::group(['middleware' => ['web']], function () {
    Route::get('/', function () {
        if (Auth::check()) {
            return redirect('home');
        }

        return View('Fortuna.Home::Base.home', ['sections' => ['home', 'services']]);
    });

    Route::post('/contact', 'App\Modules\Home\Controllers\ContactController@store');

    Route::get('/hello', 'App\Modules\Home\Controllers\ContactController@hello');
});