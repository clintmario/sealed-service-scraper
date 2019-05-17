<?php
namespace App\Modules\CMS\Entities\Base;

use App\Modules\Core\Entities\Util;
use App\Modules\Core\Entities\Config;
use App\Modules\Core\Entities\Log;
use App\Modules\Core\Services\EventService;
use App\Modules\CMS\Models\CMSModel;
use App\Modules\CMS\Entities\Base\CMSLookup;
use Illuminate\Support\Facades\Schema;

class CMSRelation
{
    const CMS_RELATION_TYPES = [
        'has' => 1,
    ];
    
    protected $cmsModel;
    protected $object1;
    protected $object2;
    protected $objectName;
    protected $relationType;
    protected $order;

    public function __construct(CMSModel $cmsModel, CMSObject $object1, CMSObject $object2, $relationType, $order = NULL, $forceScratch = false)
    {
        $lookup = new CMSLookup($cmsModel);
        $lookupTable = 'cms_lu_relation_types';
        $this->cmsModel = $cmsModel;
        $flippedRelations = array_flip(self::CMS_RELATION_TYPES);
        $relationName = $flippedRelations[$relationType];
        $this->objectName = 'cms_rel_' . $object1->getObjectName() . "_" . $relationName . "_" . $object2->getObjectName();
        $lookup = $lookup->addAndGetLookupByName($this->objectName, $lookupTable);
        $this->objectType = !empty($lookup->id) ? $lookup->id : null;
        $this->relationType = $relationType;
        $this->object1 = $object1;
        $this->object2 = $object2;
        $this->order = $order;
        if (!Schema::hasTable($this->objectName) || $forceScratch) {
            echo "Relation " . $this->objectName . " does not exist.\n";
            $this->scratch();
        }
    }

    public function scratch()
    {
       $viewSQL = "CREATE OR REPLACE VIEW {$this->objectName} AS
            SELECT attr1int AS from_id
                , attr2int AS to_id
                , attr1sint AS type
                , attr2sint AS item_order
                , attr1bool AS is_deleted
                , attr1ts AS deleted_at
                , is_dirty
                , created_at AS created_at
                , updated_at AS updated_at
            FROM cms_relations
            WHERE attr1sint = " . $this->objectType;

        Log::info($viewSQL);

        $this->cmsModel->createView($viewSQL);
    }

    public static function existsRelation(CMSObject $object1, CMSObject $object2, $relationType)
    {
        $flippedRelations = array_flip(self::CMS_RELATION_TYPES);
        $relationName = $flippedRelations[$relationType];
        $schemaName = 'cms_rel_' . $object1->getObjectName() . "_" . $relationName . "_" . $object2->getObjectName();
        return Schema::hasTable($schemaName);
    }

    public function exists()
    {
        if (empty($this->object1->getBaseObject()->id) || empty($this->object2->getBaseObject()->id)) {
            return null;
        }

        if (empty($this->object1->existsObjectById($this->object1->getBaseObject()->id))
            || empty($this->object2->existsObjectById($this->object2->getBaseObject()->id))) {
            return null;
        }

        return $this->cmsModel->existsRelation($this->object1->getBaseObject()->id, $this->object2->getBaseObject()->id, $this->objectType, $this->objectName);
    }

    public function save()
    {
        //if (!$this->exists()) {
            $this->cmsModel->saveRelation($this->object1->getBaseObject()->id, $this->object2->getBaseObject()->id, $this->objectType, $this->objectName, $this->order);
        //}
    }

    public function delete()
    {
        if ($this->exists()) {
            $this->cmsModel->deleteRelation($this->object1->getBaseObject()->id, $this->object2->getBaseObject()->id, $this->objectType, $this->objectName);
        }
    }

    public function removeRelationsNotInIds($idsString)
    {
        $this->cmsModel->removeRelationsNotInIds($this->object1->getBaseObject()->id, $idsString, $this->objectType, $this->objectName);
    }
}