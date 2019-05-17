<?php
namespace App\Modules\CMS\Entities\Tag;

use App\Modules\CMS\Entities\Tag\CMSTagGroup;
use App\Modules\Core\Entities\Util;
use App\Modules\Core\Entities\Config;
use App\Modules\Core\Entities\Log;
use App\Modules\CMS\Services\CMSService;
use App\Modules\CMS\Models\CMSModel;
use App\Modules\CMS\Entities\Base\CMSObject;

class CMSTag extends CMSObject
{
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
                'attr3int' => 'tag_group_id',
                'attr1bool' => 'is_deleted',
                'attr1ts' => 'deleted_at',
                'attr1text' => 'search_data',
                'attr1str' => 'slug',
                'attr2str' => 'asset_name',
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
                'description' => 'short_description',
                'attr2sint' => 'weight',
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
            'tag_group' => [
                'types' => [
                    self::CMS_ENTITY_TYPES['TagGroup'],
                ],
                'structure' => 'object',
            ],
        ];

        $this->objectDefinition = [
            'id' => [
                'type' => 'hidden',
                'label' => 'Id',
            ],
            'name' => [
                'type' => 'text',
                'label' => 'Tag Name',
                'required' => true,
                'validation' => 'required|max:255',
            ],
            'description' => [
                'type' => 'textarea',
                'label' => 'Tag Description',
                'required' => true,
                'validation' => 'required',
            ],
            'type' => [
                'type' => 'hidden',
                'label' => 'Tag Type',
                'required' => true,
                'validation' => 'required',
            ],
            'slug' => [
                'type' => 'text',
                'label' => 'Slug',
                'validation' => 'required|max:255',
            ],
            'tag_group_id' => [
                'type' => 'select',
                'label' => 'Tag Group',
                'function' => 'getTagGroups',
                'required' => true,
                'validation' => 'required',
                'selectType' => 'string',
            ],
            'seo_meta_title' => [
                'type' => 'text',
                'label' => 'SEO Meta Title',
            ],
            'seo_meta_description' => [
                'type' => 'textarea',
                'label' => 'SEO Meta Description',
            ],
            'seo_meta_keywords' => [
                'type' => 'text',
                'label' => 'SEO Meta Keywords',
            ],
        ];

        $this->extraPresentation = [
        ];

        $this->objectName = 'cms_obj_tags';
        $this->objectType = CMSObject::CMS_ENTITY_TYPES['Tag'];

        parent::__construct($cmsModel, $this->objectStructure, $this->objectComposition, $this->objectName, $this->objectType);

        $this->setBaseObject($baseObject);

        /*$this->filterByTags = [
            'tag_sat' => 'SAT',
        ];*/
    }

    public function save($objectIds = [])
    {
        $objectId = parent::save($objectIds);
        if (!empty($this->getBaseObject()->id)) {
            $hasId = null;
            if (!empty($this->getBaseObject()->has->tag_group->id)) {
                $hasId = $this->getBaseObject()->has->tag_group->id;
            }
            $this->cmsModel->saveField($this->getBaseObject()->id, 'tag_group_id', $hasId, $this->objectName);
        }

        return $objectId;
    }

    public function saveObject()
    {
        $this->save();
        $baseObject = $this->getObjectById($this->getBaseObject()->id)->getBaseObject();

        if (!empty($baseObject->has->tag_group->id)) {
            $tagGroupObject = $this->getObjectById($baseObject->has->tag_group->id);
            $tagIds = array_map(function($tag) {
                return $tag->id;
            }, $tagGroupObject->getBaseObject()->has->tags);
            if (!in_array($baseObject->id, $tagIds)) {
                array_push($tagGroupObject->getBaseObject()->has->tags, $baseObject);
                $tagGroupObject->save();
            }
        }
    }

    public function getCMSObjectByIdLean($objectId)
    {
        $object = parent::getCMSObjectByIdLean($objectId);

        $baseObject = $object->getBaseObject();
        if (empty($baseObject->id)) {
            return null;
        }

        if (!empty($baseObject->tag_group_id)) {
            $baseObject->has->tag_group = new \StdClass();
            $baseObject->has->tag_group->id = $baseObject->tag_group_id;
        }

        $this->setBaseObject($baseObject);
        return $object;
    }

    public function validateForm($request)
    {
        $errors = [];

        return $errors;
    }

    public function getBaseObjectFromRequest($request)
    {
        $niceType = $this->getNiceType();
        $baseObject = new \stdClass();

        // Base Attributes
        $baseObject->id = $request->input($niceType . "-id");
        if (!empty($baseObject->id)) {
            $baseObject = $this->getObjectById($baseObject->id)->getBaseObject();
        }
        $baseObject->name = $request->input($niceType . "-name");
        $baseObject->description = $request->input($niceType . "-description");
        $baseObject->short_description = $request->input($niceType . "-short_description");
        $baseObject->type = $request->input($niceType . "-type");
        $baseObject->slug = $request->input($niceType . "-slug");
        $baseObject->status = $request->input($niceType . "-status");
        $baseObject->seo_meta_title = $request->input($niceType . "-seo_meta_title");
        $baseObject->seo_meta_description = $request->input($niceType . "-seo_meta_description");
        $baseObject->seo_meta_keywords = $request->input($niceType . "-seo_meta_keywords");
        $baseObject->has = new \stdClass();

        // TagGroup
        $inputTagGroupId = $request->input($niceType . "-tag_group_id");
        $tagGroup = new \stdClass();
        $tagGroup->id = $inputTagGroupId;
        if (!empty($inputTagGroupId)) {
            $tagGroup = $this->getObjectById($inputTagGroupId)->getBaseObject();
        }
        $tagGroup->type = CMSObject::CMS_ENTITY_TYPES['TagGroup'];
        $baseObject->has->tag_group = $tagGroup;

        return $baseObject;
    }

    public function getTagGroups()
    {
        $objects = [];
        $objectType = 'TagGroup';
        $objectTypeId = !empty(CMSObject::CMS_ENTITY_TYPES[$objectType]) ? CMSObject::CMS_ENTITY_TYPES[$objectType] : null;
        if (empty($objectTypeId)) {
            return $objects;
        }

        $objectClass = CMSObject::CMS_ENTITY_CLASSES[$objectTypeId];
        $object = new $objectClass($this->cmsModel, new \stdClass());

        $objects = $object->getActiveObjectsLean(0, 200);
        $tagGroups = [];

        foreach ($objects as $tagGroup){
            $tagGroups[$tagGroup->id] = $tagGroup->name;
        }

        $tagGroups = array_flip($tagGroups);
        ksort($tagGroups);
        $tagGroups = array_flip($tagGroups);

        return $tagGroups;
    }

    public function getTagsInTagGroup($tagGroupId)
    {
        return $this->cmsModel->getTagsInTagGroup($tagGroupId);
    }
}