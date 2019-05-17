<?php
namespace App\Modules\CMS\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Modules\CMS\Services\CMSService;
use App\Modules\Core\Entities\Core;

class CMSSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cmsService = Core::getService(CMSService::class);
        $cmsService->addLookups();
    }
}
