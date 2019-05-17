<?php
/**
 * Created by PhpStorm.
 * User: ClintMario
 * Date: 9/5/2016
 * Time: 1:47 PM
 */

/*Route::get('/', function () {
    return View('Fortuna::home', ['sections' => ['home', 'services', 'clients', 'portfolio', 'testimonials', 'about', 'contact']]);
});

Route::post('/contact', 'App\Modules\Home\Controllers\ContactController@store');
*/

Route::group(['middleware' => ['web']], function () {
    // Authentication Routes
    Route::get('login', 'App\Modules\User\Controllers\Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'App\Modules\User\Controllers\Auth\LoginController@login');
    Route::post('logout', 'App\Modules\User\Controllers\Auth\LoginController@logout');
    Route::get('logout', '\App\Modules\User\Controllers\Auth\LoginController@logout');

    // Registration Routes
    Route::get('register', 'App\Modules\User\Controllers\Auth\RegisterController@showRegistrationForm');
    Route::post('register', 'App\Modules\User\Controllers\Auth\RegisterController@register');

    // Password Reset Routes
    Route::get('password/reset', 'App\Modules\User\Controllers\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'App\Modules\User\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'App\Modules\User\Controllers\Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'App\Modules\User\Controllers\Auth\ResetPasswordController@reset');

    //Logged In Home
    //Route::get('/home', 'App\Modules\User\Controllers\HomeController@index');
});
