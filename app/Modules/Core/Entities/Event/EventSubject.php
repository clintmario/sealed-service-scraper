<?php
namespace App\Modules\Core\Entities\Event;

use App\Modules\Core\Entities\Util;
use App\Modules\Core\Entities\Config;
use App\Modules\Core\Entities\Log;
use App\Modules\Core\Services\EventService;

class EventSubject
{
    private $observers = [];

    const EVENT_SUPER_SECRET = '0123456789ABCDEF';
    const EVENT_SECRET_PAYLOAD = 'event-secret-payload';

    public function __construct()
    {
    }

    public function attach(EventObserver $observer)
    {
        $eventName = $observer->getEventName();
        $eventType = $observer->getEventType();

        if (empty($this->observers[$eventName])) {
            $this->observers[$eventName] = [];
        }

        if (empty($this->observers[$eventName][$eventType])) {
            $this->observers[$eventName][$eventType] = [];
        }

        array_push($this->observers[$eventName][$eventType], $observer);
    }

    public function detach(EventObserver $observer)
    {
        $eventName = $observer->getEventName();
        $eventType = $observer->getEventType();

        if(!empty($this->observers[$eventName][$eventType]) && is_array($this->observers[$eventName][$eventType])) {
            foreach($this->observers[$eventName][$eventType] as $index => $eventObserver) {
                if ($observer == $eventObserver) {
                    unset($this->observers[$eventName][$eventType][$index]);
                    $this->observers[$eventName][$eventType] = array_values($this->observers[$eventName][$eventType]);
                }
            }
        }
    }

    public function fire($eventName, $eventObject)
    {
        if (!empty($this->observers[$eventName][EventService::EVENT_TYPE_ASYNC]) && is_array($this->observers[$eventName][EventService::EVENT_TYPE_ASYNC])) {
            $params = [
                'event_secret' => Util::twoWayEncrypt(EventSubject::EVENT_SECRET_PAYLOAD, EventSubject::EVENT_SUPER_SECRET),
                'event_name' => $eventName,
                'event_object' => json_encode($eventObject),
            ];
            Util::postAsync(Config::get('app.url') . '/async_events', $params);
        }

        //Log::info("Triggering Sync Events with object: " . print_r($eventObject, true));
        $this->executeEvents($eventName, EventService::EVENT_TYPE_SYNC, $eventObject);

    }

    public function executeEvents($eventName, $eventType, $eventObject)
    {
        if (!empty($this->observers[$eventName][$eventType]) && is_array($this->observers[$eventName][$eventType])) {
            foreach($this->observers[$eventName][$eventType] as $eventObserver) {
                if (method_exists($eventObserver->getService(), $eventObserver->getMethodName())) {
                    $eventObserver->getService()->{$eventObserver->getMethodName()}($eventObject);
                }
            }
        }
    }

    public function executeAsyncEvents($eventSecret, $eventName, $eventObject)
    {
        $payload = Util::twoWayDecrypt($eventSecret, EventSubject::EVENT_SUPER_SECRET);
        if ($payload != EventSubject::EVENT_SECRET_PAYLOAD) {
            Log::error("Unauthorized Event Payload. Not executing event: " . $eventName);
            return;
        }

        //Log::info("Triggering Async Events with object: " . print_r($eventObject, true));
        $this->executeEvents($eventName, EventService::EVENT_TYPE_ASYNC, $eventObject);
    }
}