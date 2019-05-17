<?php
namespace App\Modules\CMS\Entities\Element;

use App\Modules\Core\Entities\Config;
use App\Modules\Core\Entities\Log;
use App\Modules\CMS\Models\CMSModel;
use App\Modules\CMS\Entities\Base\CMSObject;
use App\Modules\CMS\Entities\Lesson\CMSLesson;

class CMSTrack extends CMSElement
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
                'attr1bool' => 'is_deleted',
                'attr1ts' => 'deleted_at',
                'attr2ts' => 'released_at',
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
            'lessons' => [
                'types' => [
                    self::CMS_ENTITY_TYPES['Lesson'],
                ],
                'structure' => 'array',
            ],
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
                'label' => 'Track Name',
                'required' => true,
                'validation' => 'required|max:255',
            ],
            'description' => [
                'type' => 'textarea',
                'label' => 'Track Description',
                'required' => true,
                'validation' => 'required',
            ],
            'type' => [
                'type' => 'hidden',
                'label' => 'Track Type',
                'required' => true,
                'validation' => 'required',
            ],
            'short_description' => [
                'type' => 'textarea',
                'label' => 'Track Short Description',
                'required' => true,
                'validation' => 'required',
            ],
            'lesson' => [
                'type' => 'array',
                'label' => 'Lessons',
                'function' => 'addGenericEntity',
                'fieldName' => 'lesson',
                'className' => 'cm-generic-entity-add-Lesson',
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
            'text' => [
                'lesson_html' => [
                    'type' => 'textarea',
                    'label' => 'Lesson HTML',
                ],
            ],
            'image' => [
                'file' => [
                    'type' => 'file',
                    'label' => 'Image File',
                ],
                'identifier' => [
                    'type' => 'text',
                    'label' => 'Image Identifier',
                ],
                'transcript' => [
                    'type' => 'textarea',
                    'label' => 'Image Transcript',
                ],
            ],
            'video' => [
                'file' => [
                    'type' => 'file',
                    'label' => 'Video File',
                ],
                'identifier' => [
                    'type' => 'text',
                    'label' => 'Video Identifier',
                ],
                'transcript' => [
                    'type' => 'textarea',
                    'label' => 'Video Transcript',
                ],
                'duration' => [
                    'type' => 'text',
                    'label' => 'Video Duration',
                ]
            ],
            'lesson' => [
                'lesson' => [
                    'type' => 'array',
                    'label' => 'Lessons',
                    'function' => 'addGenericEntity',
                    'fieldName' => 'lesson',
                    'className' => 'cm-generic-entity-add-Lesson',
                ],
            ],
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

        $this->objectName = 'cms_obj_tracks';
        $this->objectType = CMSObject::CMS_ENTITY_TYPES['Track'];

        parent::__construct($cmsModel, $this->objectStructure, $this->objectComposition, $this->objectName, $this->objectType, $baseObject);

        $this->filterByTags = [
            'tag_sat' => 'SAT',
        ];
    }

    public function save($objectIds = [])
    {
        $objectId = parent::save($objectIds);

        return $objectId;
    }

    public function saveObject()
    {
        $baseObject = $this->getBaseObject();

        // Lessons
        /*if (!empty($baseObject->has->lessons)) {
            foreach ($baseObject->has->lessons as $lessonIndex => $lesson) {
                $lessonObj = new CMSLesson($this->cmsModel, $lesson);
                $baseObject->has->lessons[$lessonIndex] = $lessonObj->getBaseObject();
            }
        }*/

        // Tags
        // Nothing to do for Tags

        $this->setBaseObject($baseObject);
        $this->save();
    }

    public function getCMSObjectByIdLean($objectId)
    {
        $object = parent::getCMSObjectByIdLean($objectId);

        $baseObject = $object->getBaseObject();
        if (empty($baseObject->id)) {
            return null;
        }

        $baseObject->has->lessons = [];
        $lessons = $this->cmsModel->getTrackLessons($objectId);
        if (!empty($lessons)) {
            foreach ($lessons as $lesson) {
                $lessonObj = new \stdClass();
                $lessonObj->id = $lesson->id;
                array_push($baseObject->has->lessons, $lessonObj);
            }
        }

        $tags = $this->cmsModel->getTagsForTrack($objectId);
        foreach ($tags as $tag) {
            array_push($baseObject->has->tags, $tag);
        }

        return $object;
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

        $tags = $request->input('tag');
        if (empty($tags['tag'])) {
            $error = [];
            $error['field'] = 'tag';
            $error['message'] = 'Tags cannot be empty.';
            array_push($errors, $error);
        }

        return $errors;
    }

    public function getObjectById($objectId, $objects = [])
    {
        if (empty($objectId)) {
            return null;
        }

        if (!empty($objects[$objectId])) {
            return $objects[$objectId];
        }

        $object = $this->getCMSObjectByIdLean($objectId);
        $object = $object->getCMSObjectByIdLean($objectId);

        $baseObject = $object->getBaseObject();
        if (!empty($baseObject->id)) {
            $objects[$baseObject->id] = $object;
        }

        return $object;
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
        $baseObject->status = $request->input($niceType . "-status");
        $baseObject->seo_meta_title = $request->input($niceType . "-seo_meta_title");
        $baseObject->seo_meta_description = $request->input($niceType . "-seo_meta_description");
        $baseObject->seo_meta_keywords = $request->input($niceType . "-seo_meta_keywords");
        $baseObject->has = new \stdClass();


        // Lessons
        $inputLesson = $request->input('lesson');
        $baseObject->has->lessons = [];
        if (!empty($inputLesson['lesson']) && is_array($inputLesson['lesson'])) {
            foreach ($inputLesson['lesson'] as $lessonIndex => $lesson) {
                $less = new \stdClass();
                $less->id = $lesson['lesson_id'];
                if (!empty($less->id)) {
                    $less = $this->getObjectById($less->id)->getBaseObject();
                }
                array_push($baseObject->has->lessons, $less);
            }

        }

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

    public function getTrackLessonsForDisplay()
    {
        $lessons = [];
        if (!empty($this->getBaseObject()->has->lessons) && is_array($this->getBaseObject()->has->lessons)) {
            //Log::info(print_r($this->getBaseObject()->has->lessons, true));
            foreach ($this->getBaseObject()->has->lessons as $lessonObj) {
                $lesson = [];
                $lesson['lesson_id'] = $lessonObj->id ?? '';
                $lesson['tag_id'] = !empty($lessonObj->id) ? $this->getPrimaryTagForLesson($lessonObj->id) : '';
                $lesson['tag_group_id'] = !empty($lesson['tag_id']) ? $this->getPrimaryTagGroupForTag($lesson['tag_id']) : '';
                //Log::info("Lesson: " . print_r($lesson, true));
                //exit;
                array_push($lessons, $lesson);
            }
        }

        return $lessons;
    }
}