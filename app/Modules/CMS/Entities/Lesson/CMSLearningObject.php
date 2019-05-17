<?php
namespace App\Modules\CMS\Entities\Lesson;

use App\Modules\Core\Entities\Util;
use App\Modules\Core\Entities\Config;
use App\Modules\Core\Entities\Log;
use App\Modules\CMS\Services\CMSService;
use App\Modules\CMS\Models\CMSModel;
use App\Modules\CMS\Entities\Base\CMSObject;
use App\Modules\CMS\Entities\Tag\CMSTag;

class CMSLearningObject extends CMSObject
{
    protected $cmsModel;

    public function __construct(CMSModel $cmsModel, $objectStructure, $objectComposition, $objectName, $objectType, $baseObject)
    {
        parent::__construct($cmsModel, $objectStructure, $objectComposition, $objectName, $objectType);

        $this->setBaseObject($baseObject);
    }
}