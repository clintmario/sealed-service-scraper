<?php
namespace App\Modules\CMS\Entities\Tag;

use App\Modules\Core\Entities\Util;
use App\Modules\Core\Entities\Config;
use App\Modules\Core\Entities\Log;
use App\Modules\CMS\Services\CMSService;
use App\Modules\CMS\Models\CMSModel;
use App\Modules\CMS\Entities\Base\CMSObject;
use App\Modules\CMS\Entities\Tag\CMSTag;

class CMSTagGroup extends CMSObject
{
    protected $cmsModel;

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
            'tags' => [
                'types' => [
                    self::CMS_ENTITY_TYPES['Tag'],
                ],
                'structure' => 'array',
            ],
        ];

        $this->objectDefinition = [
            'id' => [
                'type' => 'hidden',
                'label' => 'Id',
            ],
            'name' => [
                'type' => 'text',
                'label' => 'Tag Group Name',
                'required' => true,
                'validation' => 'required|max:255',
            ],
            'description' => [
                'type' => 'textarea',
                'label' => 'Tag Group Description',
            ],
            'type' => [
                'type' => 'hidden',
                'label' => 'Tag Group Type',
                'required' => true,
                'validation' => 'required',
            ],
            'slug' => [
                'type' => 'text',
                'label' => 'Slug',
                'validation' => 'required|max:255',
            ],
            'tags' => [
                'type' => 'array',
                'label' => 'Tags',
                'function' => 'addGenericEntity',
                'fieldName' => 'tag',
                'className' => 'cm-generic-entity-add-Tag',
                'required' => true,
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
            'tags' => [
                'tag' => [
                    'type' => 'array',
                    'label' => 'Tags',
                    'function' => 'addGenericEntity',
                    'fieldName' => 'tag',
                    'className' => 'cm-generic-entity-add-Tag',
                    'required' => true,
                ],
            ],
        ];

        $this->objectName = 'cms_obj_tag_groups';
        $this->objectType = CMSObject::CMS_ENTITY_TYPES['TagGroup'];

        parent::__construct($cmsModel, $this->objectStructure, $this->objectComposition, $this->objectName, $this->objectType);

        $this->setBaseObject($baseObject);
    }

    public function getCMSObjectByIdLean($objectId)
    {
        $object = parent::getCMSObjectByIdLean($objectId);

        $baseObject = $object->getBaseObject();
        if (empty($baseObject->id)) {
            return null;
        }

        $tags = $this->cmsModel->getTagsForTagGroup($objectId);
        foreach ($tags as $tag) {
            array_push($baseObject->has->tags, $tag);
        }

        return $object;
    }

    public function saveObject()
    {
        $baseObject = $this->getBaseObject();

        // Tags
        // Nothing to do for Tags

        $this->setBaseObject($baseObject);
        $this->save();
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
        $result = [];
        $tags = $this->cmsModel->getTagsInTagGroup($tagGroupId);
        foreach($tags as $tag) {
            $result[$tag->id] = $tag->tag;
        }

        return $result;
    }

    public function validateForm($request)
    {
        $errors = [];

        /*$tags = $request->input('tag');
        if (empty($tags['tag'])) {
            $error = [];
            $error['field'] = 'tag';
            $error['message'] = 'Tags cannot be empty.';
            array_push($errors, $error);
        }*/

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
        $baseObject->type = $request->input($niceType . "-type");
        $baseObject->slug = $request->input($niceType . "-slug");
        $baseObject->status = $request->input($niceType . "-status");
        $baseObject->seo_meta_title = $request->input($niceType . "-seo_meta_title");
        $baseObject->seo_meta_description = $request->input($niceType . "-seo_meta_description");
        $baseObject->seo_meta_keywords = $request->input($niceType . "-seo_meta_keywords");
        $baseObject->has = new \stdClass();

        // Tags
        $inputTags = $request->input('tag');
        $baseObject->has->tags = [];
        if (!empty($inputTags['tag'] && is_array($inputTags['tag']))) {
            foreach ($inputTags['tag'] as $tag) {
                $tg = new \stdClass();
                $tg->id = $tag['tag_id'];
                if (!empty($tg->id)) {
                    $tg = $this->getObjectById($tg->id)->getBaseObject();
                }
                $tg->type = CMSObject::CMS_ENTITY_TYPES['Tag'];
                array_push($baseObject->has->tags, $tg);
            }
        }

        return $baseObject;
    }
}