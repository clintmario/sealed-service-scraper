<?php
namespace App\Modules\CMS\Entities\Base;

use App\Modules\Core\Entities\Config;
use App\Modules\Core\Entities\Log;
use App\Modules\CMS\Models\CMSModel;
use App\Modules\CMS\Entities\Lesson\CMSLesson;
use App\Modules\CMS\Entities\Tag\CMSTagGroup;
use App\Modules\CMS\Entities\Tag\CMSTag;
use App\Modules\CMS\Entities\Element\CMSText;
use App\Modules\CMS\Entities\Element\CMSImage;
use App\Modules\CMS\Entities\Element\CMSVideo;
use App\Modules\CMS\Entities\Element\CMSQuiz;
use App\Modules\CMS\Entities\Element\CMSQuestion;
use App\Modules\CMS\Entities\Element\CMSAnswer;
use App\Modules\CMS\Entities\Element\CMSTrack;
use App\Modules\CMS\Entities\Element\CMSAssignment;
use App\Modules\CMS\Entities\Element\CMSTest;
use Illuminate\Support\Facades\Schema;

class CMSObject
{
    const CMS_ENTITY_TYPES = [
        'Lesson'            => 1,
        'Tag'               => 2,
        'TagGroup'          => 3,
        'Text'              => 4,
        'Image'             => 5,
        'Video'             => 6,
        'Document'          => 7,
        'Presentation'      => 8,
        'Spreadsheet'       => 9,
        'Quiz'              => 10,
        'Question'          => 11,
        'Answer'            => 12,
        'Test'              => 13,
        'Track'             => 14,
        'Assignment'        => 15,
    ];

    const CMS_ENTITY_CLASSES = [
        1 => CMSLesson::class,
        2 => CMSTag::class,
        3 => CMSTagGroup::class,
        4 => CMSText::class,
        5 => CMSImage::class,
        6 => CMSVideo::class,
        10 => CMSQuiz::class,
        11 => CMSQuestion::class,
        12 => CMSAnswer::class,
        13 => CMSTest::class,
        14 => CMSTrack::class,
        15 => CMSAssignment::class,
    ];

    const CMS_MAX_ATTRIBUTE_ROWS = 5;
    const CMS_ATTRIBUTE_FIELD_START_INDEX = 1000;

    protected $cmsModel;
    protected $objectStructure;
    protected $objectComposition;
    protected $objectName;
    protected $objectType;
    protected $relations;
    protected $baseObject;
    protected $savedObjectIds = [];
    protected $filterByTags;

    public function __construct(CMSModel $cmsModel, $objectStructure, $objectComposition, $objectName, $objectType)
    {
        $this->cmsModel = $cmsModel;
        $this->objectStructure = $objectStructure;
        $this->objectName = $objectName;
        $this->objectType = $objectType;
        $this->objectComposition = $objectComposition;
        $this->createBaseObject();
        $this->savedObjectIds = [];
        $this->fetchedObjectIds = [];
        $this->filterByTags = [];
    }

    public function createBaseObject()
    {
        $baseObject = new \stdClass();

        foreach($this->objectStructure as $index => $parts) {
            foreach ($parts as $columnName => $columnAlias) {
                $baseObject->$columnAlias = null;
                if ($columnName == 'attr1bool') {
                    $baseObject->$columnAlias = 0;
                }
            }
        }

        $baseObject->has = new \stdClass();

        foreach($this->objectComposition as $objectKey => $structure) {
            if ($structure['structure'] == 'array') {
                $baseObject->has->$objectKey = [];
            }
            else {
                $baseObject->has->$objectKey = null;
            }
        }

        $this->baseObject = $baseObject;
    }

    public function getBaseObject()
    {
        return $this->baseObject;
    }

    public function setBaseObject($baseObject)
    {
        $baseObject = (object) array_merge((array) $this->baseObject, (array) $baseObject);
        $this->baseObject = $baseObject;
    }

    public function getAttributeTypeId($index)
    {
        if ($index == 0) {
            return $this->objectType;
        }

        return self::CMS_ATTRIBUTE_FIELD_START_INDEX + (($this->objectType - 1) * self::CMS_MAX_ATTRIBUTE_ROWS) + $index;
    }

    public function getObjectName() {
        return $this->objectName;
    }

    public function scratch()
    {
        foreach($this->objectStructure as $index => $parts) {
            $columns = [];
            $joins = [];
            $wheres = [];

            $schemaName = $this->objectName;
            if($index > 0) {
                $schemaName = $schemaName . "_meta" . $index;
            }

            foreach ($parts as $columnName => $columnAlias) {
                $columns[] = $schemaName . "." . $columnName . " AS " . $columnAlias;
            }

            $joins[] =  "FROM cms_objects " . $schemaName;

            $wheres[] = $schemaName . ".attr1sint = " . $this->getAttributeTypeId($index);

            $columnSQL = implode("\n, ", $columns);
            $joinSQL = implode("\n", $joins);
            $whereSQL = implode("\nAND ", $wheres);

            $viewSQL = "CREATE OR REPLACE VIEW {$schemaName} AS\n";
            $viewSQL .= "SELECT $columnSQL\n";
            $viewSQL .= $joinSQL . "\n";
            $viewSQL .= "WHERE " . $whereSQL . "\n";

            Log::info($viewSQL);

            $this->cmsModel->createView($viewSQL);
        }
    }

    public function existsObjectById($objectId)
    {
        return $this->cmsModel->existsObjectById($objectId, $this->objectName);
    }

    public function save($objectIds = [])
    {
        $object = &$this->baseObject;
        $this->savedObjectIds = $objectIds;

        if (!empty($object->id)) {
            if (isset($this->savedObjectIds[$object->id])) {
                return $object->id;
            } else {
                $objectId = $this->updateObject($object);
                $this->savedObjectIds[$objectId] = 1;
                $object->id = $objectId;

                $this->saveComposition();

                return $objectId;
            }
        }

        $objectId = $this->insertObject($object);
        $this->savedObjectIds[$objectId] = 1;
        $object->id = $objectId;

        $this->saveComposition();

        return $objectId;
    }

    public function saveComposition()
    {
        $object = &$this->baseObject;

        $hasIds = [];
        foreach ($this->objectComposition as $element => $structure) {
            if (isset($object->has->$element)) {
                if (is_array($object->has->$element)) {
                    foreach ($object->has->$element as $index => &$component) {
                        $hasId = $this->saveHasRelationForObject($component, ($index + 1));
                        if (!empty($hasId)) {
                            array_push($hasIds, $hasId);
                            $component->id = $hasId;
                        }
                    }
                }
                elseif (!empty($object->has->$element)) {
                    $hasId = $this->saveHasRelationForObject($object->has->$element);
                    if (!empty($hasId)) {
                        array_push($hasIds, $hasId);
                        $object->has->$element->id = $hasId;
                    }
                }
            }
        }

        $hasIdsString = implode(',', $hasIds);
        if (empty($hasIdsString)) {
            $hasIdsString = "0";
        }

        $this->removeHasRelationsForObject($hasIdsString);
    }

    public function removeHasRelationsForObject($hasIdsString)
    {
        foreach ($this->objectComposition as $element => $structure) {
            foreach ($structure['types'] as $type) {
                $element = new \stdClass();
                $element->type = $type;
                $hasClass = self::CMS_ENTITY_CLASSES[$type];
                $hasObject = new $hasClass($this->cmsModel, $element);

                if (CMSRelation::existsRelation($this, $hasObject, CMSRelation::CMS_RELATION_TYPES['has'])) {
                    $relation = new CMSRelation($this->cmsModel, $this, $hasObject, CMSRelation::CMS_RELATION_TYPES['has']);
                    $relation->removeRelationsNotInIds($hasIdsString);
                }
            }
        }
    }

    public function saveHasRelationForObject(&$element, $order = NULL)
    {
        if (empty($element->type) || empty($this->baseObject->id)) {
            return null;
        }

        $objectId = $this->baseObject->id;

        $hasClass = self::CMS_ENTITY_CLASSES[$element->type];
        $hasObject = new $hasClass($this->cmsModel, $element);
        if (empty($element->id)) {
            $hasId = $hasObject->save($this->savedObjectIds);
            $element->id = $hasId;
        }
        else {
            $hasId = $element->id;
        }

        if (!empty($objectId) && !empty($hasId)) {
            $relation = new CMSRelation($this->cmsModel, $this, $hasObject, CMSRelation::CMS_RELATION_TYPES['has'], $order);
            $relation->save();
            return $hasId;
        }

        return null;
    }

    public function delete()
    {
        $object = &$this->baseObject;

        if (!empty($object->id)) {
            return $this->deleteObject($object);
        }
    }

    public function setHasComposition($field, CMSObject $compositeObject)
    {
        if (empty($this->baseObject->has)) {
            $this->baseObject->has = new \stdClass();
        }

        $this->baseObject->has->$field = $compositeObject->getBaseObject();
    }

    public function insertObject($object)
    {
        $objectId = null;
        $parentId = null;
        foreach($this->objectStructure as $index => $parts) {
            if ($index == 0) {
                $schema = $this->objectName;
                $baseSchema = $schema;
            }
            else {
                $schema = $this->objectName . "_meta" . $index;
                $metaField = "meta" . $index . "_type";
                $object->$metaField = $this->getAttributeTypeId($index);
            }

            $partObjectId = $this->cmsModel->insertObjectPart($parts, $object, $schema);

            if ($index == 0) {
                $objectId = $partObjectId;
            }
            else {
                $this->cmsModel->updateObjectMeta($baseSchema, $objectId, $partObjectId, $index);
            }
        }

        return $objectId;
    }

    public function updateObject($object)
    {
        $objectId = null;
        if (empty($object->id) || !$this->existsObjectById($object->id)) {
            return null;
        }

        foreach($this->objectStructure as $index => $parts) {
            if ($index == 0) {
                $schema = $this->objectName;
            }
            else {
                $schema = $this->objectName . "_meta" . $index;
                $metaField = "meta" . $index . "_type";
                $object->$metaField = $this->getAttributeTypeId($index);
            }

            $this->cmsModel->updateObjectPart($parts, $object, $schema, $index);
        }

        return $object->id;
    }

    public function deleteObject($object)
    {
        if (!empty($object->id) && $this->existsObjectById($object->id)) {
            return $this->cmsModel->deleteObject($object, $this->objectName);
        }

        return null;
    }

    public function getCMSObjectByIdLean($objectId)
    {
        $objectType = $this->cmsModel->getObjectTypeById($objectId);
        if (empty($objectType)) {
            return null;
        }

        $objectClass = self::CMS_ENTITY_CLASSES[$objectType];
        $objectDefinition = new $objectClass($this->cmsModel, new \stdClass());

        $baseObject = $this->cmsModel->getObjectById($objectId, $objectDefinition->objectName);
        if (empty($baseObject->id)) {
            return null;
        }

        $object = new $objectClass($this->cmsModel, $baseObject);

        return $object;
    }

    public function getObjectById($objectId, $objects=[])
    {
        if (empty($objectId)) {
            return null;
        }

        if (!empty($objects[$objectId])) {
            return $objects[$objectId];
        }

        $object = $this->getCMSObjectByIdLean($objectId);
        if (in_array($object->objectType, [ self::CMS_ENTITY_TYPES['Question']
            , self::CMS_ENTITY_TYPES['Answer']
        ])) {
            $object = $object->getCMSObjectByIdLean($objectId);
        }
        //$object = $object->getCMSObjectByIdLean($objectId);

        $baseObject = $object->getBaseObject();
        if (!empty($baseObject->id)) {
            $objects[$baseObject->id] = $object;

            if (!empty($baseObject->has)) {
                $hasObjects = (array)$baseObject->has;
                foreach ($hasObjects as $key => $entities) {
                    if (is_array($entities)) {
                        foreach ($entities as $entityIndex => $entity) {
                            if (!empty($entity->id)) {
                                $hasObject = $this->getObjectById($entity->id, $objects);
                                $hasBaseObject = $hasObject->getBaseObject();
                                if (!empty($hasBaseObject->id)) {
                                    $baseObject->has->$key[$entityIndex] = $hasBaseObject;
                                }
                            }
                        }
                    }
                    else {
                       if (!empty($entities->id)) {
                           $hasObject = $this->getObjectById($entities->id, $objects);
                           $hasBaseObject = $hasObject->getBaseObject();
                           if (!empty($hasBaseObject->id)) {
                               $baseObject->has->$key = $hasBaseObject;
                           }
                       }
                    }
                }
            }

            $objects[$baseObject->id] = $object;
        }

        return $object;
    }

    public function getActiveObjectsLean($start, $limit, $selectedFilters = [])
    {
        return $this->cmsModel->getActiveObjectsLean($this->objectName, $selectedFilters, $start, $limit);
    }

    public function getActiveObjectsLeanCount($selectedFilters = [])
    {
        return $this->cmsModel->getActiveObjectsLeanCount($this->objectName, $selectedFilters);
    }

    public function getDeletedObjectsLean($start, $limit, $selectedFilters = [])
    {
        return $this->cmsModel->getDeletedObjectsLean($this->objectName, $selectedFilters, $start, $limit);
    }

    public function getDeletedObjectsLeanCount($selectedFilters = [])
    {
        return $this->cmsModel->getDeletedObjectsLeanCount($this->objectName, $selectedFilters);
    }

    public static function getMenuItems()
    {
        $menuItems = self::CMS_ENTITY_TYPES;
        unset($menuItems['Question']);
        unset($menuItems['Answer']);

        return $menuItems;
    }

    public function getNiceType()
    {
        $types = array_flip(self::CMS_ENTITY_TYPES);
        return $types[$this->objectType];
    }

    public function getStatusOptions()
    {
        return [
            0 => 'Inactive',
            1 => 'Active',
        ];
    }

    public function getDefinition()
    {
        return $this->objectDefinition;
    }

    public function getExtraDefinitions()
    {
        return $this->extraPresentation;
    }

    public function getElementTypes()
    {
        return [
            self::CMS_ENTITY_TYPES['Text'] => 'Text',
            self::CMS_ENTITY_TYPES['Image'] => 'Image',
            self::CMS_ENTITY_TYPES['Video'] => 'Video',
        ];
    }

    public function getElementFields($objectType)
    {
        $elements = $this->getElementTypes();
        $elementIndex = strtolower($elements[$objectType]);
        return [
            'element' => $elementIndex,
            'fields' => $this->extraPresentation[$elementIndex],
        ];
    }

    public function getPrimaryElementFromBaseObject($baseObject)
    {
        $primaryElement = null;
        if (!empty($baseObject->has->primary_element->type)) {
            switch ($baseObject->has->primary_element->type) {
                case CMSObject::CMS_ENTITY_TYPES['Text']:
                    $primaryElement = new CMSText($this->cmsModel, $baseObject->has->primary_element);
                    break;
                case CMSObject::CMS_ENTITY_TYPES['Image']:
                    $primaryElement = new CMSImage($this->cmsModel, $baseObject->has->primary_element);
                    break;
                case CMSObject::CMS_ENTITY_TYPES['Video']:
                    $primaryElement = new CMSVideo($this->cmsModel, $baseObject->has->primary_element);
                    break;
                default:
                    break;
            }
        }

        return $primaryElement;
    }

    public function getPrimaryElementBaseObject($baseObject, $inputPrimaryElement)
    {
        $primaryElement = new \stdClass();
        $primaryElement->id = $inputPrimaryElement['id'] ?? '';
        if (!empty($primaryElement->id)) {
            $primaryElement = $this->getObjectById($primaryElement->id)->getBaseObject();
        }
        $primaryElement->type = $inputPrimaryElement['type'] ?? '';

        $primaryElement->name = $baseObject->name ?? '';
        $primaryElement->description = $baseObject->description ?? '';
        $primaryElement->short_description = $baseObject->short_description ?? '';

        if ($primaryElement->type == CMSObject::CMS_ENTITY_TYPES['Text']) {
            //$primaryElement->lesson_html = '';
        }
        elseif ($primaryElement->type == CMSObject::CMS_ENTITY_TYPES['Image']) {
            $primaryElement->file_name = $inputPrimaryElement['image']['identifier'] ?? '';
            $primaryElement->transcript = $inputPrimaryElement['image']['transcript'] ?? '';
        }
        elseif ($primaryElement->type == CMSObject::CMS_ENTITY_TYPES['Video']) {
            $primaryElement->file_name = $inputPrimaryElement['video']['identifier'] ?? '';
            $primaryElement->duration = !empty($inputPrimaryElement['video']['duration']) ? (int) $inputPrimaryElement['video']['duration'] :  0;
            $primaryElement->transcript = $inputPrimaryElement['video']['transcript'] ?? '';
        }

        return $primaryElement;
    }

    public function getPrimaryElement($baseObject)
    {
        $primaryElement = [];
        $primaryElement['id'] = $baseObject->has->primary_element->id ?? '';
        $primaryElement['type'] = $baseObject->has->primary_element->type ?? '';

        if ($primaryElement['type'] == CMSObject::CMS_ENTITY_TYPES['Text']) {
            $primaryElement['text'] = [];
            $primaryElement['text']['lesson_html'] = ''; // $baseObject->has->primary_element->lesson_html;
        }
        elseif ($primaryElement['type'] == CMSObject::CMS_ENTITY_TYPES['Image']) {
            $primaryElement['image'] = [];
            $primaryElement['image']['file'] = '';
            $primaryElement['image']['identifier'] = $baseObject->has->primary_element->file_name ?? '';
            $primaryElement['image']['transcript'] = $baseObject->has->primary_element->transcript ?? '';
        }
        elseif ($primaryElement['type'] == CMSObject::CMS_ENTITY_TYPES['Video']) {
            $primaryElement['video'] = [];
            $primaryElement['video']['file'] = '';
            $primaryElement['video']['identifier'] = $baseObject->has->primary_element->file_name ?? '';
            $primaryElement['video']['transcript'] = $baseObject->has->primary_element->transcript ?? '';
            $primaryElement['video']['duration'] = !empty($baseObject->has->primary_element->duration) ? (int) $baseObject->has->primary_element->duration : 0;
        }

        return $primaryElement;
    }

    public function getPrimaryElementForDisplay()
    {
        $primaryElement = [];
        if (!empty($this->getBaseObject()->has->primary_element->id)) {
            $primaryElement = $this->getPrimaryElement($this->getBaseObject());
        }

        return $primaryElement;
    }

    public function getLessonQuizForDisplay()
    {
        $quiz = [];
        if (!empty($this->getBaseObject()->has->quiz->has->questions)) {
            $questions = $this->getBaseObject()->has->quiz->has->questions;
            $quiz['id'] = $this->getBaseObject()->has->quiz->id;
            $quiz['question'] = [];
            foreach ($questions as $question) {
                $ques = [];
                $ques['id'] = $question->id;
                $ques['answer'] = [];
                $ques['primary_element'] = $this->getPrimaryElement($question);;
                $ques['stem'] = $question->has->primary_element->description ?? '';
                if (!empty($question->has->answers)) {
                    foreach ($question->has->answers as $answer) {
                        $ans = [];
                        $ans['id'] = $answer->id;
                        $ans['is_correct'] = $answer->is_correct;
                        $ans['primary_element'] = $this->getPrimaryElement($answer);
                        $ans['stem'] = $answer->has->primary_element->description ?? '';
                        array_push($ques['answer'], $ans);
                    }
                }
                array_push($quiz['question'], $ques);
            }
        }
        elseif(!empty($this->getBaseObject()->has->questions)) {
            $questions = $this->getBaseObject()->has->questions;
            $quiz['id'] = $this->getBaseObject()->id;
            $quiz['question'] = [];
            foreach ($questions as $question) {
                $ques = [];
                $ques['id'] = $question->id;
                $ques['answer'] = [];
                $ques['primary_element'] = $this->getPrimaryElement($question);;
                $ques['stem'] = $question->has->primary_element->description ?? '';
                if (!empty($question->has->answers)) {
                    foreach ($question->has->answers as $answer) {
                        $ans = [];
                        $ans['id'] = $answer->id;
                        $ans['is_correct'] = $answer->is_correct;
                        $ans['primary_element'] = $this->getPrimaryElement($answer);
                        $ans['stem'] = $answer->has->primary_element->description ?? '';
                        array_push($ques['answer'], $ans);
                    }
                }
                array_push($quiz['question'], $ques);
            }
        }

        return $quiz;
    }

    public function getLessonTagsForDisplay()
    {
        $tags = [];
        if (!empty($this->getBaseObject()->has->tags) && is_array($this->getBaseObject()->has->tags)) {
            foreach ($this->getBaseObject()->has->tags as $tagObj) {
                $tag = [];
                $tag['tag_id'] = $tagObj->id ?? '';
                $tag['tag_group_id'] = $tagObj->tag_group_id ?? '';
                array_push($tags, $tag);
            }
        }

        return $tags;
    }

    public function getValidationArray()
    {
        $niceType = $this->getNiceType();
        $validationArray = [];

        foreach ($this->objectDefinition as $field => $definition) {
            if(isset($definition['validation'])) {
                $validationArray[$niceType . "-" . $field] = $definition['validation'];
            }
        }

        return $validationArray;
    }

    public function getPrimaryTagForLesson($lessonId)
    {
        return $this->cmsModel->getPrimaryTagForLesson($lessonId);
    }

    public function getPrimaryTagGroupForTag($tagId)
    {
        return $this->cmsModel->getPrimaryTagGroupForTag($tagId);
    }

    public function getPrimaryTagForTrack($trackId)
    {
        return $this->cmsModel->getPrimaryTagForTrack($trackId);
    }

    public function getPrimaryTagForQuiz($quizId)
    {
        return $this->cmsModel->getPrimaryTagForQuiz($quizId);
    }

    public function getLessonsInTag($tagId)
    {
        return $this->cmsModel->getLessonsInTag($tagId);
    }

    public function getTracksInTag($tagId)
    {
        return $this->cmsModel->getTracksInTag($tagId);
    }

    public function getQuizzesInTag($tagId)
    {
        return $this->cmsModel->getQuizzesInTag($tagId);
    }

    public function getTagsInTagGroupByName($tagGroup)
    {
        $result = [];
        $tags = $this->cmsModel->getTagsInTagGroupByName($tagGroup);
        foreach($tags as $tag) {
            $result[$tag->id] = $tag->tag;
        }

        return $result;
    }

    public function getFilterByTags()
    {
        return $this->filterByTags;
    }
}