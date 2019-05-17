<?php
namespace App\Modules\CMS\Entities\Lesson;

use App\Modules\CMS\Entities\Element\CMSQuiz;
use App\Modules\CMS\Entities\Element\CMSQuestion;
use App\Modules\CMS\Entities\Element\CMSAnswer;
use App\Modules\CMS\Entities\Element\CMSText;
use App\Modules\CMS\Entities\Element\CMSImage;
use App\Modules\CMS\Entities\Element\CMSVideo;
use App\Modules\CMS\Entities\Tag\CMSTagGroup;
use App\Modules\CMS\Entities\Tag\CMSTag;
use App\Modules\Core\Entities\Config;
use App\Modules\Core\Entities\Log;
use App\Modules\CMS\Models\CMSModel;
use App\Modules\CMS\Entities\Base\CMSObject;

class CMSLesson extends CMSObject
{
    protected $element;
    protected $objectDefinition;
    protected $extraPresentation;

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
                //'attr4int' => 'quiz_id',
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
            'primary_element' => [
                'types' => [
                    self::CMS_ENTITY_TYPES['Text'],
                    self::CMS_ENTITY_TYPES['Image'],
                    self::CMS_ENTITY_TYPES['Video'],
                ],
                'structure' => 'object',
            ],
            'tags' => [
                'types' => [
                    self::CMS_ENTITY_TYPES['Tag'],
                ],
                'structure' => 'array',
            ],
            /*'quiz' => [
                'types' => [
                    self::CMS_ENTITY_TYPES['Quiz'],
                ],
                'structure' => 'object',
            ],*/
            'quizzes' => [
                'types' => [
                    self::CMS_ENTITY_TYPES['Quiz'],
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
                'label' => 'Lesson Name',
                'required' => true,
                'validation' => 'required|max:200',
            ],
            'description' => [
                'type' => 'textarea',
                'label' => 'Lesson Description',
                'required' => true,
                'validation' => 'required',
            ],
            'type' => [
                'type' => 'hidden',
                'label' => 'Lesson Type',
                'required' => true,
                'validation' => 'required',
            ],
            'short_description' => [
                'type' => 'textarea',
                'label' => 'Lesson Short Description',
                'required' => true,
                'validation' => 'required',
            ],
            'primary_element' => [
                'type' => 'object',
                'label' => 'Primary Element',
                'required' => true,
                'validation' => 'required',
                'onchange' => 'addElement',
                'function' => 'getElementTypes',
                'fieldName' => 'primary_element',
            ],
            /*'quiz' => [
                'type' => 'object',
                'label' => 'Quiz',
                'function' => 'addGenericEntity',
                'fieldName' => 'quiz',
                'className' => 'cm-generic-entity-add-Question',
            ],*/
            'lesson_quiz' => [
                'type' => 'array',
                'label' => 'Quizzes',
                'function' => 'addGenericEntity',
                'fieldName' => 'lesson_quiz',
                'className' => 'cm-generic-entity-add-Lesson_quiz',
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
            /*'status' => [
                'type' => 'select',
                'label' => 'Lesson Status',
                'function' => 'getStatusOptions',
                'required' => true,
                'validation' => 'required',
                'selectType' => 'integer',
            ],*/
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
            /*'quiz' => [
                'question' => [
                    'type' => 'array',
                    'label' => 'Quiz',
                    'function' => 'addGenericEntity',
                    'fieldName' => 'question',
                    'className' => 'cm-generic-entity-add-Question',
                ],
            ],*/
            'lesson_quiz' => [
                'lesson_quiz' => [
                    'type' => 'array',
                    'label' => 'Quizzes',
                    'function' => 'addGenericEntity',
                    'fieldName' => 'lesson_quiz',
                    'className' => 'cm-generic-entity-add-Lesson_quiz',
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

        $this->objectName = 'cms_obj_lessons';
        $this->objectType = CMSObject::CMS_ENTITY_TYPES['Lesson'];

        parent::__construct($cmsModel, $this->objectStructure, $this->objectComposition, $this->objectName, $this->objectType, $baseObject);
        $this->setBaseObject($baseObject);

        $this->filterByTags = [
            'tag_sat' => 'SAT',
            'tag_broad_objective' => 'Broad Objective',
            'tag_browse_category' => 'Browse Category',
            'tag_product_line' => 'Product Line',
            'tag_language' => 'Language',
            'tag_platform' => 'Platform',
            'tag_primary_skill' => 'Primary Skill',
            'tag_skill_level' => 'Skill Level',
            'tag_learning_resource_type' => 'Learning Resource Type',
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
            /*if (!empty($this->getBaseObject()->has->quiz->id)) {
                $hasId = $this->getBaseObject()->has->quiz->id;
                $this->cmsModel->saveField($this->getBaseObject()->id, 'quiz_id', $hasId, $this->objectName);
            }*/
        }

        return $objectId;
    }

    public function saveObject()
    {
        $baseObject = $this->getBaseObject();

        // Primary Element
        $primaryElement = $this->getPrimaryElementFromBaseObject($baseObject);
        if (!empty($primaryElement)) {
            $primaryElement->save();
            $baseObject->has->primary_element = $primaryElement->getBaseObject();
        }

        // Quiz
        /*if (!empty($baseObject->has->quiz)) {
            $quizObj = new CMSQuiz($this->cmsModel, $baseObject->has->quiz);
            foreach ($baseObject->has->quiz->has->questions as $questionIndex => $question) {
                $questionPrimaryElement = $this->getPrimaryElementFromBaseObject($question);
                $questionPrimaryElement->save();
                $question->has->primary_element = $questionPrimaryElement->getBaseObject();
                $questionObj = new CMSQuestion($this->cmsModel, $question);
                $questionObj->save();
                foreach ($question->has->answers as $answerIndex => $answer) {
                    $answerPrimaryElement = $this->getPrimaryElementFromBaseObject($answer);
                    $answerPrimaryElement->save();
                    $answer->has->primary_element = $answerPrimaryElement->getBaseObject();
                    $answer->has->question = $questionObj->getBaseObject();
                    $answerObj = new CMSAnswer($this->cmsModel, $answer);
                    $answerObj->save();

                    $question->has->answers[$answerIndex] = $answerObj->getBaseObject();
                }

                $questionObj = new CMSQuestion($this->cmsModel, $question);
                $questionObj->save();
                $baseObject->has->quiz->has->questions[$questionIndex] = $questionObj->getBaseObject();
            }

            $quizObj->save();
            $baseObject->has->quiz = $quizObj->getBaseObject();
        }*/

        // Tags
        // Nothing to do for Tags

        $this->setBaseObject($baseObject);
        $this->save();
    }

    /*public function getPrimaryElementFromBaseObject($baseObject)
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
    }*/

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
        /*if (!empty($baseObject->quiz_id)) {
            $baseObject->has->quiz = new \StdClass();
            $baseObject->has->quiz->id = $baseObject->quiz_id;
        }*/
        $baseObject->has->quizzes = [];
        $quizzes = $this->cmsModel->getLessonQuizzes($objectId);
        if (!empty($quizzes)) {
            foreach ($quizzes as $quiz) {
                $quizObj = new \stdClass();
                $quizObj->id = $quiz->id;
                array_push($baseObject->has->quizzes, $quizObj);
            }
        }
        $tags = $this->cmsModel->getTagsForLesson($objectId);
        foreach ($tags as $tag) {
            array_push($baseObject->has->tags, $tag);
        }

        return $object;
    }

    /*public function getDefinition()
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
    }*/

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

    /*public function getValidationArray()
    {
        $niceType = $this->getNiceType();
        $validationArray = [];

        foreach ($this->objectDefinition as $field => $definition) {
            if(isset($definition['validation'])) {
                $validationArray[$niceType . "-" . $field] = $definition['validation'];
            }
        }

        return $validationArray;
    }*/

    public function validateForm($request)
    {
        $errors = [];

        $niceType = $this->getNiceType();
        $tags = $request->input('tag');
        if (empty($tags['tag'])) {
            $error = [];
            $error['field'] = 'tag';
            $error['message'] = 'Tags cannot be empty.';
            array_push($errors, $error);
        }

        /*$quiz = $request->input('quiz');
        if (!empty($quiz['question']) && is_array($quiz['question'])) {
            foreach ($quiz['question'] as $questionIndex => $question) {
                if (!empty($question['answer']) && is_array($question['answer'])) {
                    $isCorrectChosen = 0;
                    foreach ($question['answer'] as $answerIndex => $answer) {
                        $answer['is_correct'] = !empty($answer['is_correct']) ? 1 : 0;
                        if($answer['is_correct'] == 1) {
                            $isCorrectChosen++;
                        }
                    }
                    if ($isCorrectChosen > 1) {
                        $error = [];
                        $error['field'] = 'quiz';
                        $error['message'] = 'Only one answer can be marked correct for Question ' . ($questionIndex + 1);
                        array_push($errors, $error);
                    }
                    if ($isCorrectChosen == 0) {
                        $error = [];
                        $error['field'] = 'quiz';
                        $error['message'] = 'At least one answer should be marked correct for Question ' . ($questionIndex + 1);
                        array_push($errors, $error);
                    }
                }
            }
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
        $baseObject->short_description = $request->input($niceType . "-short_description");
        $baseObject->type = $request->input($niceType . "-type");
        $baseObject->status = $request->input($niceType . "-status");
        $baseObject->seo_meta_title = $request->input($niceType . "-seo_meta_title");
        $baseObject->seo_meta_description = $request->input($niceType . "-seo_meta_description");
        $baseObject->seo_meta_keywords = $request->input($niceType . "-seo_meta_keywords");
        $baseObject->has = new \stdClass();

        // Primary Element
        $inputPrimaryElement = $request->input($niceType . "-primary_element");
        if (!empty($inputPrimaryElement) && is_array($inputPrimaryElement)) {
            $baseObject->has->primary_element = $this->getPrimaryElementBaseObject($baseObject, $inputPrimaryElement);
        }

        // Quiz
        /*$inputQuiz = $request->input('quiz');
        $quiz = new \stdClass();
        $quiz->id = $inputQuiz['id'];
        if (!empty($quiz->id)) {
            $quiz = $this->getObjectById($quiz->id)->getBaseObject();
        }
        $quiz->type = CMSObject::CMS_ENTITY_TYPES['Quiz'];
        $quiz->name = $baseObject->name;
        $quiz->description = $baseObject->description;
        $quiz->has = new \stdClass();
        $quiz->has->questions = [];
        if (!empty($inputQuiz['question']) && is_array($inputQuiz['question'])) {
            foreach ($inputQuiz['question'] as $questionIndex => $question) {
                $ques = new \stdClass();
                $ques->id = $question['id'];
                if (!empty($ques->id)) {
                    $ques = $this->getObjectById($ques->id)->getBaseObject();
                }
                $ques->description = $question['stem'];
                $ques->type = CMSObject::CMS_ENTITY_TYPES['Question'];
                $ques->has = new \stdClass();
                $ques->has->answers = [];
                $ques->has->primary_element = $this->getPrimaryElementBaseObject($ques, $question['primary_element']);
                if (!empty($question['answer']) && is_array($question['answer'])) {
                    foreach ($question['answer'] as $answerIndex => $answer) {
                        $ans = new \stdClass();
                        $ans->id = $answer['id'];
                        if (!empty($ans->id)) {
                            $ans = $this->getObjectById($ans->id)->getBaseObject();
                        }
                        $ans->description = $answer['stem'];
                        $ans->type = CMSObject::CMS_ENTITY_TYPES['Answer'];
                        $answer['is_correct'] = !empty($answer['is_correct']) ? 1 : 0;
                        $ans->is_correct = $answer['is_correct'];
                        $ans->question = &$ques;
                        $ans->has = new \stdClass();
                        $ans->has->primary_element = $this->getPrimaryElementBaseObject($ans, $answer['primary_element']);
                        array_push($ques->has->answers, $ans);
                    }
                }
                array_push($quiz->has->questions, $ques);
            }
        }
        $baseObject->has->quiz = $quiz;
        */

        // Quizzes
        $inputTest = $request->input('lesson_quiz');
        $baseObject->has->quizzes = [];
        if (!empty($inputTest['lesson_quiz']) && is_array($inputTest['lesson_quiz'])) {
            foreach ($inputTest['lesson_quiz'] as $quizIndex => $quiz) {
                $qz = new \stdClass();
                $qz->id = $quiz['quiz_id'];
                if (!empty($qz->id)) {
                    $qz = $this->getObjectById($qz->id)->getBaseObject();
                }
                array_push($baseObject->has->quizzes, $qz);
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

    public function getLessonQuizzesForDisplay()
    {
        $quizzes = [];
        if (!empty($this->getBaseObject()->has->quizzes) && is_array($this->getBaseObject()->has->quizzes)) {
            foreach ($this->getBaseObject()->has->quizzes as $quizObj) {
                $quiz = [];
                $quiz['quiz_id'] = $quizObj->id ?? '';
                $quiz['tag_id'] = !empty($quizObj->id) ? $this->getPrimaryTagForQuiz($quizObj->id) : '';
                $quiz['tag_group_id'] = !empty($quiz['tag_id']) ? $this->getPrimaryTagGroupForTag($quiz['tag_id']) : '';
                array_push($quizzes, $quiz);
            }
        }

        return $quizzes;
    }

    /*public function getPrimaryElementBaseObject($baseObject, $inputPrimaryElement)
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
    }*/
}
