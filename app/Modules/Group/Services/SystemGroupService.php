<?php
namespace App\Modules\Group\Services;

use Illuminate\Contracts\Foundation\Application;
use App\Modules\Core\Entities\Core;

class SystemGroupService extends GroupService
{
    public function __construct(Application $app)
    {
        parent::__construct($app);
        //Core::setService(SystemGroupService::class, $this);
    }

    public function createSystemGroup($groupName)
    {
        if($this->groupModel->existsSystemGroupByName($groupName)) {
            return $this->getSystemGroupByName($groupName);
        }

        $groupId = $this->groupModel->createGroup();
        $this->groupModel->createSystemGroup($groupId, $groupName);

        return $this->getSystemGroupById($groupId);
    }

    public function getSystemGroupById($groupId)
    {
        return $this->groupModel->getSystemGroupById($groupId);
    }

    public function getSystemGroupByName($groupName)
    {
        return $this->groupModel->getSystemGroupByName($groupName);
    }

    public function addUserToSystemGroupByName($userId, $groupName)
    {
        $this->groupModel->addUserToSystemGroupByName($userId, $groupName);
    }

    public function getUserSystemGroups($userId)
    {
        return $this->groupModel->getUserSystemGroups($userId);
    }
}