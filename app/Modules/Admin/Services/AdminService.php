<?php
namespace App\Modules\Admin\Services;

//use Illuminate\Support\ServiceProvider;
use App\Modules\Core\Services\BaseService;
use App\Modules\Core\Services\EventService;
use Illuminate\Contracts\Foundation\Application;
use App\Modules\Admin\Models\AdminModel;
use App\Modules\Core\Entities\Core;
use Illuminate\Support\Facades\Session;
use App\Modules\Core\Entities\Config;
use Illuminate\Support\Facades\Mail;

class AdminService extends BaseService
{
    protected $adminModel;
    public $sessionQueries;
    protected $eventService;

    const ADMIN_SESSION_REPORT_QUERY_KEY = 'admin_report_query_key';
    const ADMIN_SESSION_REPORTED_QUERIES_KEY = 'admin_reported_queries_key';

    public function __construct(Application $app)
    {
        parent::__construct($app);
        //Core::setService(AdminService::class, $this);

        $this->adminModel = new AdminModel();
        $this->sessionQueries = [];

        $this->eventService = Core::getService(EventService::class);
        //echo "HOHOHO!!!";
        //$this->sessionQueries = array_merge((array)Session::get(self::ADMIN_SESSION_REPORTED_QUERIES_KEY), []);
        //Session::flash(self::ADMIN_SESSION_REPORTED_QUERIES_KEY, $this->sessionQueries);
        //$this->clearQueries();

        $this->eventService->subscribe(EventService::EVENT_GENERATE_EMAIL_ERROR, EventService::EVENT_TYPE_ASYNC, $this, 'sendErrorEmail');
    }

    public function isQueryReportingEnabled()
    {
        return !empty(Session::get(AdminService::ADMIN_SESSION_REPORT_QUERY_KEY));
    }

    public function recordQuery($query)
    {
        $queryObject = [
            'sql' => $query->sql,
            'bindings' => $query->bindings,
            'time' => round($query->time / 1000, 4),
        ];

        if (empty(Session::get(self::ADMIN_SESSION_REPORTED_QUERIES_KEY))) {
            Session::put(self::ADMIN_SESSION_REPORTED_QUERIES_KEY, []);
        }

        $savedQueries = Session::get(self::ADMIN_SESSION_REPORTED_QUERIES_KEY);
        array_push($savedQueries, $queryObject);
        Session::put(self::ADMIN_SESSION_REPORTED_QUERIES_KEY, $savedQueries);
        $this->sessionQueries = $savedQueries;

        //array_push($this->sessionQueries, $queryObject);

        //$this->sessionQueries = array_merge((array)Session::get(self::ADMIN_SESSION_REPORTED_QUERIES_KEY), [$queryObject]);
        //Session::flash(self::ADMIN_SESSION_REPORTED_QUERIES_KEY, $this->sessionQueries);
    }

    public function clearQueries()
    {
        Session::put(self::ADMIN_SESSION_REPORTED_QUERIES_KEY, []);
        Session::forget(self::ADMIN_SESSION_REPORTED_QUERIES_KEY);

        $this->sessionQueries = [];
    }

    /*public function register()
    {
        $this->app->bind('App\Modules\Group\Services\GroupService', function($app) {
            return new GroupService($app);
        });
    }

    public function provides()
    {
        return ['App\Modules\Group\Services\GroupService'];
    }

    public function createGroup()
    {
        return $this->groupModel->createGroup();
    }*/

    public function throwSampleError()
    {
        throw new \Exception("This is a sample admin generated error.");
    }

    public function sendErrorEmail($eventObject)
    {
        $exception = $eventObject->exception;
        Mail::send("Fortuna.Admin::Base.admin-errors", ['content' => $eventObject->content], function($message) use ($exception) {
            $message->subject("[" . Config::get('module.app_segment') . "] " . $exception->file . " <" . $exception->line . ">")
                ->to('tech@bogex.com')
                ->replyTo('tech@bogex.com');
        });
    }
}