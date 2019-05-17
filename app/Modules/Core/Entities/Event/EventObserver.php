<?php
namespace App\Modules\Core\Entities\Event;

use App\Modules\Core\Services\BaseService;

class EventObserver
{
    private $eventName;
    private $eventType;
    private $methodName;
    private $service;

    public function __construct($eventName, $eventType, BaseService $service, $methodName)
    {
        $this->eventName = $eventName;
        $this->eventType = $eventType;
        $this->methodName = $methodName;
        $this->service = $service;
    }

    public function getEventName()
    {
        return $this->eventName;
    }

    public function setEventName($eventName)
    {
        $this->eventName = $eventName;
    }

    public function getEventType()
    {
        return $this->eventType;
    }

    public function setEventType($eventType)
    {
        $this->eventType = $eventType;
    }

    public function getMethodName()
    {
        return $this->methodName;
    }

    public function setMethodName($methodName)
    {
        $this->methodName = $methodName;
    }

    public function getService()
    {
        return $this->service;
    }

    public function setService($service)
    {
        $this->service = $service;
    }
}