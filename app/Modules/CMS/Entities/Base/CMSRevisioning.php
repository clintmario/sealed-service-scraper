<?php
namespace App\Modules\CMS\Entities\Base;

use App\Modules\Core\Entities\Util;
use App\Modules\Core\Entities\Config;
use App\Modules\Core\Entities\Log;
use App\Modules\Core\Services\EventService;
use App\Modules\CMS\Models\CMSRevisioningModel;

class CMSRevisioning
{
    private $revModel;

    public function __construct(CMSRevisioningModel $revModel)
    {
        $this->revModel = $revModel;
    }

    public function getAllDirtyObjects()
    {
        return $this->revModel->getAllDirtyObjects();
    }

    public function getMyDirtyObjects()
    {
        return $this->revModel->getMyDirtyObjects();
    }

    public function saveCommits($committedObjectIds)
    {
        $commitId = $this->revModel->insertCommit();
        $this->revModel->saveCommits($commitId, $committedObjectIds);
        $this->revModel->markAsClean($committedObjectIds);
    }

    public function publishObjects()
    {
        $numPublishedObjects = $this->revModel->getNumUnpublishedObjects();
        $this->revModel->publishObjects();
        $this->revModel->createLibraryViews();

        return $numPublishedObjects;
    }
}