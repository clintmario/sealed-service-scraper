<?php
namespace App\Modules\Core\Services;

use App\Modules\Core\Services\BaseService;
use Illuminate\Contracts\Foundation\Application;
use App\Modules\Core\Entities\Core;
use App\Modules\Core\Entities\Log;
use App\Modules\Core\Entities\Event\EventSubject;
use App\Modules\Core\Entities\Event\EventObserver;

class EventService extends BaseService
{
    private $eventSubject;

    const EVENT_TYPE_SYNC = 'event-type-sync';
    const EVENT_TYPE_ASYNC = 'event-type-async';

    const EVENT_USER_CREATED = 'event-user-created';
    const EVENT_USER_LOGGED_IN = 'event-user-logged-in';
    const EVENT_GENERATE_EMAIL_ERROR = 'event-generate-email-error';

    const EVENT_USER_WATCHED_LESSON = 'event-user-watched-lesson';
    const EVENT_CONTACT_US = 'event-contact-us';

    public function __construct(Application $app)
    {
        parent::__construct($app);
        //Core::setService(EventService::class, $this);

        $this->eventSubject = new EventSubject();

    }

    public function executeAsyncEvents($eventSecret, $eventName, $eventObject)
    {
        $this->eventSubject->executeAsyncEvents($eventSecret, $eventName, $eventObject);
    }

    public function subscribe($eventName, $eventType, $service, $methodName)
    {
        $eventObserver = new EventObserver($eventName, $eventType, $service, $methodName);
        $this->eventSubject->attach($eventObserver);
    }

    public function fire($eventName, $eventObject)
    {
        $this->eventSubject->fire($eventName, $eventObject);
    }
}