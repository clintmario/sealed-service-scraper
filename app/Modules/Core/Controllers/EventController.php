<?php

namespace App\Modules\Core\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Core\Entities\Core;
use App\Modules\Core\Entities\Log;
use App\Modules\Core\Services\EventService;

class EventController extends Controller
{
    public function fireAsyncEvents(Request $request)
    {
        $eventSecret = $request->input('event_secret');
        $eventName = $request->input('event_name');
        $eventObject = json_decode($request->input('event_object'));

        $eventService = Core::getService(EventService::class);

        $eventService->executeAsyncEvents($eventSecret, $eventName, $eventObject);
    }
}
