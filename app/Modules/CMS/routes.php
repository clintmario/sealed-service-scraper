<?php
//use App\Modules\CMS\Services\CMSService;
//use App\Modules\Core\Entities\Core;

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

//$cmsService = Core::getService(CMSService::class);
//$menuItems = $cmsService->getMenuItems();

Route::group(['middleware' => ['web']], function () {
    Route::get('/cms', 'App\Modules\CMS\Controllers\CMSController@cms');

    /*foreach ($menuItems as $key => $menuItem) {
        Route::get('/cms/' . strtolower($key) . '/list', 'App\Modules\CMS\Controllers\CMSController@list' . $key . 's');
        Route::get('/cms/' . strtolower($key) . '/add', 'App\Modules\CMS\Controllers\CMSController@add' . $key);
        Route::get('/cms/' . strtolower($key) . '/deleted', 'App\Modules\CMS\Controllers\CMSController@listDeleted' . $key . 's');
    }*/

    Route::get('/cms/list', 'App\Modules\CMS\Controllers\CMSController@listObjects');
    Route::get('/cms/save', 'App\Modules\CMS\Controllers\CMSController@getObject');
    Route::post('/cms/save', 'App\Modules\CMS\Controllers\CMSController@saveObject');
    Route::get('/cms/delete', 'App\Modules\CMS\Controllers\CMSController@deleteObject');
    Route::get('/cms/deleted', 'App\Modules\CMS\Controllers\CMSController@deletedObjects');

    Route::post('/tags/list', 'App\Modules\CMS\Controllers\CMSController@listTags');
    Route::post('/tags/get_lessons', 'App\Modules\CMS\Controllers\CMSController@getLessonsInTag');
    Route::post('/tags/get_tracks', 'App\Modules\CMS\Controllers\CMSController@getTracksInTag');
    Route::post('/tags/get_quizzes', 'App\Modules\CMS\Controllers\CMSController@getQuizzesInTag');

    Route::get('/cms/commit', 'App\Modules\CMS\Controllers\CMSController@listCommits');
    Route::post('/cms/commit', 'App\Modules\CMS\Controllers\CMSController@saveCommits');
    Route::post('/cms/publish', 'App\Modules\CMS\Controllers\CMSController@publishObjects');
});
