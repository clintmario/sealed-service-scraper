<?php
namespace App\Modules\CMS\Entities\Base;

use App\Modules\Core\Entities\Util;
use App\Modules\Core\Entities\Config;
use App\Modules\Core\Entities\Log;
use App\Modules\Core\Services\EventService;
use App\Modules\CMS\Models\CMSModel;

class CMSLookup
{
    private $cmsModel;

    public function __construct(CMSModel $cmsModel)
    {
        $this->cmsModel = $cmsModel;
    }

    public function addAndGetLookupById($lookupId, $lookupName, $lookupTable)
    {
        if (empty($this->cmsModel->existsLookupById($lookupId, $lookupTable))) {
            $this->cmsModel->addLookupById($lookupId, $lookupName, $lookupTable);
        }

        return $this->cmsModel->getLookupById($lookupId, $lookupTable);
    }

    public function addAndGetLookupByName($lookupName, $lookupTable)
    {
        if (empty($this->cmsModel->existsLookupByName($lookupName, $lookupTable))) {
            $this->cmsModel->addLookupByName($lookupName, $lookupTable);
        }

        return $this->cmsModel->getLookupByName($lookupName, $lookupTable);
    }
}