<?php
namespace App\Modules\CMS\Entities\Element;

use App\Modules\Core\Entities\Config;
use App\Modules\Core\Entities\Log;
use App\Modules\CMS\Models\CMSModel;
use App\Modules\CMS\Entities\Base\CMSObject;

class CMSElement extends CMSObject
{
    public function __construct(CMSModel $cmsModel, $objectStructure, $objectComposition, $objectName, $objectType, $baseObject)
    {
        parent::__construct($cmsModel, $objectStructure, $objectComposition, $objectName, $objectType);

        $this->setBaseObject($baseObject);
    }
}