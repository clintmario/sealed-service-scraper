<?php

namespace App\Modules\Group\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GroupModel extends Model
{
    public function createGroup()
    {
        DB::insert("INSERT INTO groups(created_at, updated_at)
          VALUES(NOW(), NOW())");

        return DB::getPdo()->lastInsertId();
    }

    public function existsSystemGroupByName($groupName)
    {
        $result = DB::select("SELECT *
            FROM groups_system
            WHERE name = :group_name", ['group_name' => $groupName]);

        return !empty($result);
    }

    public function createSystemGroup($groupId, $groupName)
    {
        DB::insert("INSERT INTO groups_system(id, name, created_at, updated_at)
          VALUES(:group_id, :group_name, NOW(), NOW())", ['group_id' => $groupId, 'group_name' => $groupName]);

        return $groupId;
    }

    public function getSystemGroupById($groupId)
    {
        $result = DB::select("SELECT *
            FROM groups_system
            WHERE id = :group_id", ['group_id' => $groupId]);

        if (!empty($result)) {
            return $result[0];
        }

        return null;
    }

    public function getSystemGroupByName($groupName)
    {
        $result = DB::select("SELECT *
            FROM groups_system
            WHERE name = :group_name", ['group_name' => $groupName]);

        if (!empty($result)) {
            return $result[0];
        }

        return null;
    }

    public function existsUserInSystemGroup($userId, $groupId)
    {
        $result = DB::select("SELECT *
            FROM users_groups
            WHERE user_id = :user_id
            AND group_id = :group_id", ['user_id' => $userId, 'group_id' => $groupId]);

        return !empty($result);
    }

    public function addUserToSystemGroupByName($userId, $groupName)
    {
        $group = $this->getSystemGroupByName($groupName);
        if (!empty($group) && !$this->existsUserInSystemGroup($userId, $group->id)) {
            DB::insert("INSERT INTO users_groups(user_id, group_id, created_at, updated_at)
              VALUES(:user_id, :group_id, NOW(), NOW())", ['user_id' => $userId, 'group_id' => $group->id]);
        }
    }

    public function getUserSystemGroups($userId)
    {
        $result = DB::select("SELECT g.name
            FROM groups_system g
            JOIN users_groups ug ON ug.group_id = g.id
            WHERE ug.user_id = :user_id", ['user_id' => $userId]);

        $userSystemGroups = [];
        foreach ($result as $group) {
            array_push($userSystemGroups, $group->name);
        }

        return $userSystemGroups;
    }
}
