<?php
namespace App\Modules\CMS\Entities\Element;

use App\Modules\Core\Entities\Util;
use App\Modules\Core\Entities\Config;
use App\Modules\Core\Entities\Log;
use App\Modules\CMS\Services\CMSService;
use App\Modules\CMS\Models\CMSModel;
use App\Modules\CMS\Entities\Base\CMSObject;
use App\Modules\CMS\Entities\Tag\CMSTag;

class CMSQuiz extends CMSElement
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
                'created_by' => 'created_by',
                'revision' => 'revision',
                'is_dirty' => 'is_dirty',
                'updated_by' => 'updated_by',
                'created_at' => 'created_at',
                'updated_at' => 'updated_at',
            ],
        ];

        $this->objectComposition = [
            'questions' => [
                'types' => [
                    self::CMS_ENTITY_TYPES['Question'],
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
                'label' => 'Quiz Name',
                'required' => true,
                'validation' => 'required|max:255',
            ],
            'description' => [
                'type' => 'textarea',
                'label' => 'Quiz Description',
                'required' => true,
                'validation' => 'required',
            ],
            'type' => [
                'type' => 'hidden',
                'label' => 'Quiz Type',
                'required' => true,
                'validation' => 'required',
            ],
            'quiz' => [
                'type' => 'object',
                'label' => 'Quiz',
                'function' => 'addGenericEntity',
                'fieldName' => 'quiz',
                'className' => 'cm-generic-entity-add-Question',
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
            'quiz' => [
                'question' => [
                    'type' => 'array',
                    'label' => 'Quiz',
                    'function' => 'addGenericEntity',
                    'fieldName' => 'question',
                    'className' => 'cm-generic-entity-add-Question',
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

        $this->objectName = 'cms_obj_quizzes';
        $this->objectType = CMSObject::CMS_ENTITY_TYPES['Quiz'];

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

        // Quiz
        if (!empty($baseObject->has->questions)) {
            //$quizObj = new CMSQuiz($this->cmsModel, $baseObject);
            foreach ($baseObject->has->questions as $questionIndex => $question) {
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
                $baseObject->has->questions[$questionIndex] = $questionObj->getBaseObject();
            }

            $quizObj = new CMSQuiz($this->cmsModel, $baseObject);
            $quizObj->save();
            $baseObject = $quizObj->getBaseObject();
        }

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

        $baseObject->has->questions = [];
        $questions = $this->cmsModel->getQuizQuestions($objectId);
        if (!empty($questions)) {
            foreach ($questions as $question) {
                $questionObj = new \stdClass();
                $questionObj->id = $question->id;
                array_push($baseObject->has->questions, $questionObj);
            }
        }

        $tags = $this->cmsModel->getTagsForQuiz($objectId);
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

        $quiz = $request->input('quiz');
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
        }

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
        $baseObject->status = $request->input($niceType . "-status");
        $baseObject->seo_meta_title = $request->input($niceType . "-seo_meta_title");
        $baseObject->seo_meta_description = $request->input($niceType . "-seo_meta_description");
        $baseObject->seo_meta_keywords = $request->input($niceType . "-seo_meta_keywords");
        $baseObject->has = new \stdClass();


        // Quiz
        $inputQuiz = $request->input('quiz');
        /*$quiz = new \stdClass();
        $quiz->id = $inputQuiz['id'];
        if (!empty($quiz->id)) {
            $quiz = $this->getObjectById($quiz->id)->getBaseObject();
        }
        $quiz->type = CMSObject::CMS_ENTITY_TYPES['Quiz'];
        $quiz->name = $baseObject->name;
        $quiz->description = $baseObject->description;
        $quiz->has = new \stdClass();
        $quiz->has->questions = [];*/
        $baseObject->has->questions = [];
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
                array_push($baseObject->has->questions, $ques);
            }

            //$baseObject->has->questions = $quiz->has->questions;
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
}