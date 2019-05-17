<?php
namespace App\Modules\Group\Services;

//use Illuminate\Support\ServiceProvider;
use App\Modules\Core\Services\BaseService;
use Illuminate\Contracts\Foundation\Application;
use App\Modules\Group\Models\GroupModel;
use App\Modules\Core\Entities\Core;

class GroupService extends BaseService
{
    protected $groupModel;

    public function __construct(Application $app)
    {
        parent::__construct($app);
        //Core::setService(GroupService::class, $this);

        $this->groupModel = new GroupModel();
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
    }*/

    public function createGroup()
    {
        return $this->groupModel->createGroup();
    }
}