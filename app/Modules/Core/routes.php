<?php
Route::post('/async_events', 'App\Modules\Core\Controllers\EventController@fireAsyncEvents');