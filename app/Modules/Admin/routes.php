<?php
/**
 * Created by PhpStorm.
 * User: ClintMario
 * Date: 9/5/2016
 * Time: 1:47 PM
 */

use App\Modules\Admin\Services\AdminService;
use App\Modules\Core\Entities\Core;

$adminService = Core::getService(AdminService::class);
//$adminService->clearQueries();

\Event::listen('Illuminate\Database\Events\QueryExecuted', function ($query) {
    $adminService = Core::getService(AdminService::class);
    if ($adminService->isQueryReportingEnabled()) {
        $adminService->recordQuery($query);
        //print_r($query->sql);
        //print_r($query->bindings);
        //print_r($query->time);
    }
});

Route::group(['middleware' => ['web']], function () {
    Route::get('/admin', 'App\Modules\Admin\Controllers\AdminController@actions');
    Route::get('/admin/report_queries', 'App\Modules\Admin\Controllers\AdminController@report_queries');
    Route::post('/admin/report_queries', 'App\Modules\Admin\Controllers\AdminController@report_queries');
    Route::get('/admin/force_login', 'App\Modules\Admin\Controllers\AdminController@get_force_login');
    Route::post('/admin/force_login', 'App\Modules\Admin\Controllers\AdminController@force_login');
    Route::get('/admin/error_emails', 'App\Modules\Admin\Controllers\AdminController@get_error_emails');
    Route::post('/admin/error_emails', 'App\Modules\Admin\Controllers\AdminController@send_error_emails');
});