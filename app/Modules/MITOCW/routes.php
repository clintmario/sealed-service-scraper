<?php
/**
 * Created by PhpStorm.
 * User: ClintMario
 * Date: 9/5/2016
 * Time: 1:47 PM
 */

Route::group(['middleware' => ['web']], function () {
    //Route::get('/mit_ocw_video_importer', 'App\Modules\Home\Controllers\ExtractController@importVideos');
    //Route::get('/mit_ocw_skill_importer', 'App\Modules\Home\Controllers\ExtractController@importSkills');
});
