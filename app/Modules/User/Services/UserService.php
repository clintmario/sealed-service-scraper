<?php
namespace App\Modules\User\Services;

use App\Modules\Core\Services\BaseService;
use Illuminate\Contracts\Foundation\Application;
use App\Modules\User\Models\UserModel;
use App\Modules\Core\Services\EventService;
use App\Modules\Group\Services\SystemGroupService;
use Illuminate\Support\Facades\Session;
use App\Modules\Core\Entities\Core;
use App\Modules\Core\Entities\Log;

class UserService extends BaseService
{
    const ALL_USERS_GROUP_NAME = 'Users';
    const ADMIN_USERS_GROUP_NAME = 'Admins';

    const USER_SESSION_SYSTEM_GROUP_KEY = 'user_system_groups';

    protected $userModel;
    protected $eventService;
    protected $groupService;

    public function __construct(Application $app)
    {
        parent::__construct($app);
        //Core::setService(UserService::class, $this);

        $this->userModel = new UserModel();
        $this->eventService = Core::getService(EventService::class);
        $this->groupService = Core::getService(SystemGroupService::class);

        $this->eventService->subscribe(EventService::EVENT_USER_LOGGED_IN, EventService::EVENT_TYPE_ASYNC, $this, 'insertLogin');
    }

    public function existsUserById($userId)
    {
        return $this->userModel->existsUserById($userId);
    }

    public function existsUserByEmail($email)
    {
        return $this->userModel->existsUserByEmail($email);
    }

    public function getUserById($userId)
    {
        return $this->userModel->getUserById($userId);
    }

    public function getUserByEmail($email)
    {
        return $this->userModel->getUserByEmail($email);
    }

    public function insertLogin($userObject)
    {
        $this->userModel->insertLogin($userObject->user_id);
    }

    public function addUserToSystemGroupByName($userId, $groupName)
    {
        $this->groupService->addUserToSystemGroupByName($userId, $groupName);
    }

    public function getUserSystemGroups($userId)
    {
        $userSystemGroups = Session::get(UserService::USER_SESSION_SYSTEM_GROUP_KEY);
        if (!empty($userSystemGroups)) {
            return $userSystemGroups;
        }

        $userSystemGroups = $this->groupService->getUserSystemGroups($userId);
        Session::put(UserService::USER_SESSION_SYSTEM_GROUP_KEY, $userSystemGroups);

        return $userSystemGroups;
    }

    public function isUserWebUser($userId)
    {
        return in_array(UserService::ALL_USERS_GROUP_NAME, $this->getUserSystemGroups($userId));
    }

    public function isUserAdmin($userId)
    {
        return in_array(UserService::ADMIN_USERS_GROUP_NAME, $this->getUserSystemGroups($userId));
    }
}