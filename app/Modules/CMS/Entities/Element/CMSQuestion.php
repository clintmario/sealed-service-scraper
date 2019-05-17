<?php
namespace App\Modules\CMS\Entities\Element;

use App\Modules\Core\Entities\Util;
use App\Modules\Core\Entities\Config;
use App\Modules\Core\Entities\Log;
use App\Modules\CMS\Services\CMSService;
use App\Modules\CMS\Models\CMSModel;
use App\Modules\CMS\Entities\Base\CMSObject;
use App\Modules\CMS\Entities\Tag\CMSTag;

class CMSQuestion extends CMSElement
{
    protected $element;

    public function __construct(CMSModel $cmsModel, $baseObject)
    {
        $this->objectStructure = [
            [
                'id' => 'id',
                'name' => 'name',
                'description' => 'description',
                'attr1sint' => 'type',
                'attr2sint' => 'status',
                'attr1int' => 'meta1_id',
                'attr2int' => 'meta2_id',
                'attr3int' => 'primary_element_id',
                'attr1bool' => 'is_deleted',
                'attr1ts' => 'deleted_at',
                'attr1text' => 'search_data',
                'attr1str' => 'slug',
                'revision' => 'revision',
                'is_dirty' => 'is_dirty',
                'created_by' => 'created_by',
                'updated_by' => 'updated_by',
                'created_at' => 'created_at',
                'updated_at' => 'updated_at',
            ],
            [
                'id' => 'meta1_id',
                'attr1sint' => 'meta1_type',
                'name' => 'title',
                'description' => 'short_description',
                'attr1bool' => 'is_published',
                'attr1ts' => 'published_at',
                'revision' => 'revision',
                'is_dirty' => 'is_dirty',
                'created_by' => 'created_by',
                'updated_by' => 'updated_by',
                'created_at' => 'created_at',
                'updated_at' => 'updated_at',
            ],
            [
                'id' => 'meta2_id',
                'attr1sint' => 'meta2_type',
                'name' => 'seo_meta_title',
                'description' => 'seo_meta_description',
                'attr1str' => 'seo_meta_keywords',
                'revision' => 'revision',
                'is_dirty' => 'is_dirty',
                'created_by' => 'created_by',
                'updated_by' => 'updated_by',
                'created_at' => 'created_at',
                'updated_at' => 'updated_at',
            ],
        ];

        $this->objectComposition = [
            'primary_element' => [
                'types' => [
                    self::CMS_ENTITY_TYPES['Text'],
                    self::CMS_ENTITY_TYPES['Image'],
                    self::CMS_ENTITY_TYPES['Video'],
                ],
                'structure' => 'object',
            ],
            'answers' => [
                'types' => [
                    self::CMS_ENTITY_TYPES['Answer'],
                ],
                'structure' => 'array',
            ],
        ];

        $this->objectName = 'cms_obj_questions';
        $this->objectType = CMSObject::CMS_ENTITY_TYPES['Question'];

        parent::__construct($cmsModel, $this->objectStructure, $this->objectComposition, $this->objectName, $this->objectType, $baseObject);

        $this->filterByTags = [
            'tag_sat' => 'SAT',
        ];
    }

    public function save($objectIds = [])
    {
        $objectId = parent::save($objectIds);
        if (!empty($this->getBaseObject()->id)) {
            $hasId = null;
            if (!empty($this->getBaseObject()->has->primary_element->id)) {
                $hasId = $this->getBaseObject()->has->primary_element->id;
                $this->cmsModel->saveField($this->getBaseObject()->id, 'primary_element_id', $hasId, $this->objectName);
            }
        }

        return $objectId;
    }

    public function getCMSObjectByIdLean($objectId)
    {
        $object = parent::getCMSObjectByIdLean($objectId);

        $baseObject = $object->getBaseObject();
        if (empty($baseObject->id)) {
            return null;
        }

        if (!empty($baseObject->primary_element_id)) {
            $baseObject->has->primary_element = new \StdClass();
            $baseObject->has->primary_element->id = $baseObject->primary_element_id;
        }

        $baseObject->has->answers = [];
        $answers = $this->cmsModel->getQuestionAnswers($objectId);
        if (!empty($answers)) {
            foreach ($answers as $answer) {
                $answerObj = new \stdClass();
                $answerObj->id = $answer->id;
                array_push($baseObject->has->answers, $answerObj);
            }
        }

        return $object;
    }
}