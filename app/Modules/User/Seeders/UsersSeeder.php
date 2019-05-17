<?php
namespace App\Modules\User\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Modules\Group\Services\SystemGroupService;
use App\Modules\User\Services\UserService;
use App\Modules\Core\Entities\Core;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $systemGroupService = Core::getService(SystemGroupService::class);
        $systemGroupService->createSystemGroup(UserService::ALL_USERS_GROUP_NAME);
        $systemGroupService->createSystemGroup(UserService::ADMIN_USERS_GROUP_NAME);

        $userService = Core::getService(UserService::class);
        if (!$userService->existsUserByEmail('learner@cm.com')) {
            DB::table('users')->insert([
                'name' => 'CM Learner',
                'email' => 'learner@cm.com',
                'password' => bcrypt('learner123'),
                'created_at' => gmdate('Y-m-d H:i:s'),
                'updated_at' => gmdate('Y-m-d H:i:s'),
            ]);

            $userId = DB::getPdo()->lastInsertId();
            $userService->addUserToSystemGroupByName($userId, UserService::ALL_USERS_GROUP_NAME);
        }

        if (!$userService->existsUserByEmail('admin@cm.com')) {
            DB::table('users')->insert([
                'name' => 'CM Admin',
                'email' => 'admin@cm.com',
                'password' => bcrypt('admin123'),
                'created_at' => gmdate('Y-m-d H:i:s'),
                'updated_at' => gmdate('Y-m-d H:i:s'),
            ]);

            $userId = DB::getPdo()->lastInsertId();
            $userService->addUserToSystemGroupByName($userId, UserService::ALL_USERS_GROUP_NAME);
            $userService->addUserToSystemGroupByName($userId, UserService::ADMIN_USERS_GROUP_NAME);
        }
    }
}
