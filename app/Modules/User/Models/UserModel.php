<?php

namespace App\Modules\User\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserModel extends Model
{
    public function existsUserById($userId)
    {
        $result = DB::select("SELECT *
            FROM users
            WHERE id = :user_id", ['user_id' => $userId]);

        return !empty($result);
    }

    public function existsUserByEmail($email)
    {
        $result = DB::select("SELECT *
            FROM users
            WHERE email = :email", ['email' => $email]);

        return !empty($result);
    }

    public function getUserById($userId)
    {
        $result = DB::select("SELECT *
            FROM users
            WHERE id = :user_id", ['user_id' => $userId]);

        if (!empty($result)) {
            return $result[0];
        }

        return null;
    }

    public function getUserByEmail($email)
    {
        $result = DB::select("SELECT *
            FROM users
            WHERE email = :email", ['email' => $email]);

        if (!empty($result)) {
            return $result[0];
        }

        return null;
    }

    public function insertLogin($userId)
    {
        DB::insert("INSERT INTO logins(user_id, created_at, updated_at)
          VALUES(:user_id, NOW(), NOW())", ['user_id' => $userId]);
    }
}
