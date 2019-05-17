<?php
namespace App\Modules\MITOCW\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Modules\CMS\Services\CMSService;
use App\Modules\MITOCW\Services\MITOCWService;
use App\Modules\Core\Entities\Core;

class POCSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pocService = Core::getService(MITOCWService::class);
        $pocService->seedPOC();
    }
}
