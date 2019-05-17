<?php
namespace App\Modules\CMS\Services;

use App\Modules\CMS\Models\CMSSeedModel;
use App\Modules\Core\Services\BaseService;
use Illuminate\Contracts\Foundation\Application;
use App\Modules\CMS\Models\CMSModel;
use App\Modules\Core\Services\EventService;
use App\Modules\Core\Entities\Core;
use App\Modules\Core\Entities\Log;
use App\Modules\CMS\Entities\Base\CMSLookup;
use App\Modules\CMS\Entities\Base\CMSObject;
use App\Modules\CMS\Entities\Base\CMSRelation;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use App\Modules\CMS\Entities\Base\CMSRevisioning;
use App\Modules\CMS\Models\CMSRevisioningModel;

class CMSService extends BaseService
{
    protected $cmsModel;
    protected $eventService;
    protected $cmsLookup;
    protected $seedModel;
    protected $seedData;

    protected $revisioning;
    protected $revModel;

    public function __construct(Application $app)
    {
        parent::__construct($app);
        //Core::setService(CMSService::class, $this);

        $this->cmsModel = new CMSModel();
        $this->eventService = Core::getService(EventService::class);
        $this->cmsLookup = new CMSLookup($this->cmsModel);

        $this->seedModel = new CMSSeedModel();
        $this->seedData = [];

        $this->revModel = new CMSRevisioningModel();
        $this->revisioning = new CMSRevisioning($this->revModel);

        //$this->eventService->subscribe(EventService::EVENT_USER_LOGGED_IN, EventService::EVENT_TYPE_ASYNC, $this, 'insertLogin');
    }

    public function scratch()
    {
        $tg = new CMSTagGroup($this->cmsModel, new \stdClass());
        $tg->scratch();
        $tn = new CMSTag($this->cmsModel, new \stdClass());
        $tn->scratch();
        $ca1 = new CMSAnswer($this->cmsModel, new \stdClass());
        $ca1->scratch();
        $ca2 = new CMSQuestion($this->cmsModel, new \stdClass());
        $ca2->scratch();
        $cq = new CMSQuiz($this->cmsModel, new \stdClass());
        $cq->scratch();
        $ca3 = new CMSText($this->cmsModel, new \stdClass());
        $ca3->scratch();
        $ca4 = new CMSImage($this->cmsModel, new \stdClass());
        $ca4->scratch();
        $ca5 = new CMSVideo($this->cmsModel, new \stdClass());
        $ca5->scratch();
        $cl = new CMSLesson($this->cmsModel, new \stdClass());
        $cl->scratch();
        $ct = new CMSTrack($this->cmsModel, new \stdClass());
        $ct->scratch();
        $ca6 = new CMSAssignment($this->cmsModel, new \stdClass());
        $ca6->scratch();
        $relation = new CMSRelation($this->cmsModel, $ca6, $ct, CMSRelation::CMS_RELATION_TYPES['has'], null, true);
        $relation = new CMSRelation($this->cmsModel, $ca6, $tn, CMSRelation::CMS_RELATION_TYPES['has'], null, true);
        $ca7 = new CMSTest($this->cmsModel, new \stdClass());
        $ca7->scratch();
        $relation = new CMSRelation($this->cmsModel, $ca7, $cq, CMSRelation::CMS_RELATION_TYPES['has'], null, true);
        $relation = new CMSRelation($this->cmsModel, $ca7, $tn, CMSRelation::CMS_RELATION_TYPES['has'], null, true);
        //cms_rel_cms_obj_tags_has_cms_obj_tag_groups
        $relation = new CMSRelation($this->cmsModel, $tn, $tg, CMSRelation::CMS_RELATION_TYPES['has'], null, true);
        //cms_rel_cms_obj_tag_groups_has_cms_obj_tags
        $relation = new CMSRelation($this->cmsModel, $tg, $tn, CMSRelation::CMS_RELATION_TYPES['has'], null, true);
        //cms_rel_cms_obj_lessons_has_cms_obj_videos
        $relation = new CMSRelation($this->cmsModel, $cl, $ca5, CMSRelation::CMS_RELATION_TYPES['has'], null, true);
        //cms_rel_cms_obj_lessons_has_cms_obj_tags
        $relation = new CMSRelation($this->cmsModel, $cl, $tn, CMSRelation::CMS_RELATION_TYPES['has'], null, true);
        //cms_rel_cms_obj_lessons_has_cms_obj_quizzes
        $relation = new CMSRelation($this->cmsModel, $cl, $cq, CMSRelation::CMS_RELATION_TYPES['has'], null, true);
        //cms_rel_cms_obj_questions_has_cms_obj_texts
        $relation = new CMSRelation($this->cmsModel, $ca2, $ca3, CMSRelation::CMS_RELATION_TYPES['has'], null, true);
        //cms_rel_cms_obj_quizzes_has_cms_obj_questions
        $relation = new CMSRelation($this->cmsModel, $cq, $ca2, CMSRelation::CMS_RELATION_TYPES['has'], null, true);
        //cms_rel_cms_obj_answers_has_cms_obj_texts
        $relation = new CMSRelation($this->cmsModel, $ca1, $ca3, CMSRelation::CMS_RELATION_TYPES['has'], null, true);
        //cms_rel_cms_obj_answers_has_cms_obj_questions
        $relation = new CMSRelation($this->cmsModel, $ca1, $ca2, CMSRelation::CMS_RELATION_TYPES['has'], null, true);
        //cms_rel_cms_obj_questions_has_cms_obj_answers
        $relation = new CMSRelation($this->cmsModel, $ca2, $ca1, CMSRelation::CMS_RELATION_TYPES['has'], null, true);
        //cms_rel_cms_obj_tracks_has_cms_obj_lessons
        $relation = new CMSRelation($this->cmsModel, $ct, $cl, CMSRelation::CMS_RELATION_TYPES['has'], null, true);
        //cms_rel_cms_obj_answers_has_cms_obj_images
        $relation = new CMSRelation($this->cmsModel, $ca1, $ca4, CMSRelation::CMS_RELATION_TYPES['has'], null, true);
        //cms_rel_cms_obj_answers_has_cms_obj_videos
        $relation = new CMSRelation($this->cmsModel, $ca1, $ca5, CMSRelation::CMS_RELATION_TYPES['has'], null, true);
        //cms_rel_cms_obj_questions_has_cms_obj_images
        $relation = new CMSRelation($this->cmsModel, $ca2, $ca4, CMSRelation::CMS_RELATION_TYPES['has'], null, true);
        //cms_rel_cms_obj_lessons_has_cms_obj_images
        $relation = new CMSRelation($this->cmsModel, $cl, $ca4, CMSRelation::CMS_RELATION_TYPES['has'], null, true);
        //cms_rel_cms_obj_quizzes_has_cms_obj_tags
        $relation = new CMSRelation($this->cmsModel, $cq, $tn, CMSRelation::CMS_RELATION_TYPES['has'], null, true);
        //cms_rel_cms_obj_tag_groups_has_cms_obj_tag_groups
        $relation = new CMSRelation($this->cmsModel, $tg, $tg, CMSRelation::CMS_RELATION_TYPES['has'], null, true);
        //cms_rel_cms_obj_tracks_has_cms_obj_tags
        $relation = new CMSRelation($this->cmsModel, $ct, $tn, CMSRelation::CMS_RELATION_TYPES['has'], null, true);
        //cms_rel_cms_obj_assignments_has_cms_obj_tracks
        $relation = new CMSRelation($this->cmsModel, $ca6, $ct, CMSRelation::CMS_RELATION_TYPES['has'], null, true);
        //cms_rel_cms_obj_assignments_has_cms_obj_tags
        $relation = new CMSRelation($this->cmsModel, $ca6, $tn, CMSRelation::CMS_RELATION_TYPES['has'], null, true);
        //cms_rel_cms_obj_tests_has_cms_obj_quizzes
        $relation = new CMSRelation($this->cmsModel, $ca7, $cq, CMSRelation::CMS_RELATION_TYPES['has'], null, true);
        //cms_rel_cms_obj_tests_has_cms_obj_tags
        $relation = new CMSRelation($this->cmsModel, $ca7, $tn, CMSRelation::CMS_RELATION_TYPES['has'], null, true);
        //cms_rel_cms_obj_lessons_has_cms_obj_texts
        $relation = new CMSRelation($this->cmsModel, $cl, $ca3, CMSRelation::CMS_RELATION_TYPES['has'], null, true);
    }

    public function seeder()
    {
        set_time_limit(0);
        ini_set('memory_limit', '12G');

        $startTime = microtime(true);
        $this->scratch();

        /*$this->seedTags();
        $this->seedLessons();
        $this->seedTracks();*/
        $endTime = microtime(true);

        echo "Time taken: " . round($endTime - $startTime, 2) . "s\n";
    }

    public function seedTags()
    {
        $tagGroups = $this->seedModel->getTagGroups();
        $this->seedData['tagGroups'] = [];
        $this->seedData['tags'] = [];

        foreach ($tagGroups as $tagGroup) {
            $this->seedData['tagGroups'][$tagGroup->id] = $tagGroup;
            $cmTagGroup = new \stdClass();
            $cmTagGroup->name = $tagGroup->tag_group;
            $cmTagGroup->type = CMSObject::CMS_ENTITY_TYPES['TagGroup'];
            echo "Creating Tag Group: " . $tagGroup->tag_group . "\n";
            $tgObj = new CMSTagGroup($this->cmsModel, $cmTagGroup);
            $tgObj->save();
            $this->seedData['tagGroups'][$tagGroup->id]->cmObj = $tgObj->getBaseObject();
            $tgObj->getBaseObject()->has = new \stdClass();
            $tgObj->getBaseObject()->has->tags = [];

            $tags = $this->seedModel->getTagsInTagGroups($tagGroup->id);
            foreach ($tags as $tag) {
                $cmTag = new \stdClass();
                $cmTag->name = $tag->tag;
                $cmTag->type = CMSObject::CMS_ENTITY_TYPES['Tag'];
                $cmTag->description = $tag->description;
                $cmTag->slug = $tag->slug;
                $cmTag->weight = $tag->weight;
                $cmTag->short_description = $tag->short_description;
                $cmTag->asset_name = $tag->cms_asset_name;
                $cmTag->seo_meta_title = $tag->seo_meta_title;
                $cmTag->seo_meta_description = $tag->seo_meta_description;
                $cmTag->seo_meta_keywords = $tag->seo_meta_keywords;
                $cmTag->has = new \stdClass();
                $cmTag->has->tag_group = $tgObj->getBaseObject();
                echo "Saving Tag: " . $tag->tag . "\n";
                $tagObj = new CMSTag($this->cmsModel, $cmTag);
                $tagObj->save();
                $this->seedData['tags'][$tag->id] = $tag;
                $this->seedData['tags'][$tag->id]->cmObj = $tagObj->getBaseObject();
                array_push($tgObj->getBaseObject()->has->tags, $tagObj->getBaseObject());
            }

            echo "Saving Tag Group: " . $tagGroup->tag_group . "\n";
            $tgObj->save();
        }
    }

    public function seedLessons()
    {
        $lessons = $this->seedModel->getLessons();
        $this->seedData['lessons'] = [];
        $this->seedData['lesson_objects'] = [];
        foreach ($lessons as $lesson)
        {
            if (empty($this->seedData['lessons'][$lesson->id])) {
                $this->seedData['lessons'][$lesson->id] = [];
                if (!empty($lesson->quiz_id)) {
                    $quiz = new CMSQuiz($this->cmsModel, (object)['name' => 'Quiz on ' . $lesson->LessonName, 'type' => CMSObject::CMS_ENTITY_TYPES['Quiz']]);
                    $quiz->save();
                }
                else {
                    $quiz = null;
                }

                $video = new CMSVideo($this->cmsModel, (object) [
                    'name' => $lesson->LessonName,
                    'description' => $lesson->LessonDescription,
                    'short_description' => $lesson->LessonShortDescription,
                    'file_name' => $lesson->VideoFileName,
                    'duration' => $lesson->VideoDuration,
                    'transcript' => $lesson->LessonTranscript,
                    'type' => CMSObject::CMS_ENTITY_TYPES['Video'],
                ]);
                $video->save();

                $releaseDate = null;
                $releaseTime = strtotime($lesson->LessonReleaseDate);
                if ($releaseTime > 0) {
                    $releaseDate = date("Y-m-d", $releaseTime);
                }

                $lessonObj = new CMSLesson($this->cmsModel, (object) [
                    'name' => $lesson->LessonName,
                    'description' => $lesson->LessonDescription,
                    'short_description' => $lesson->LessonShortDescription,
                    'slug' => $lesson->LessonSlug,
                    'released_at' => $releaseDate,
                    'seo_meta_title' => $lesson->SEOTitle,
                    'seo_meta_description' => $lesson->SEODescription,
                    'seo_meta_keywords' => $lesson->SEOKeyWords,
                    'type' => CMSObject::CMS_ENTITY_TYPES['Lesson'],
                ]);


                $tags = $this->seedModel->getTagsForLesson($lesson->id);
                foreach($tags as $tag) {
                    if (isset($this->seedData['tags'][$tag->id])) {
                        array_push($lessonObj->getBaseObject()->has->tags, $this->seedData['tags'][$tag->id]->cmObj);
                    }
                }

                if (!empty($lesson->quiz_id)) {
                    $lessonObj->getBaseObject()->has->quiz = $quiz->getBaseObject();
                }
                $lessonObj->getBaseObject()->has->primary_element = $video->getBaseObject();

                echo "Saving Lesson: " . $lesson->LessonName . "\n";
                $lessonObj->save();
                $this->seedData['lesson_objects'][$lesson->id] = new \stdClass();
                $this->seedData['lesson_objects'][$lesson->id]->cmObj = $lessonObj->getBaseObject();
            }

            if (empty($this->seedData['lessons'][$lesson->id]['questions'])) {
                $this->seedData['lessons'][$lesson->id]['questions'] = [];
            }

            if (empty($this->seedData['lessons'][$lesson->id]['questions'][$lesson->question_id]) && $lesson->QuestionStem != null) {
                $this->seedData['lessons'][$lesson->id]['questions'][$lesson->question_id] = [];

                $questionText = new CMSText($this->cmsModel, (object) ['description' => $lesson->QuestionStem, 'type' => CMSObject::CMS_ENTITY_TYPES['Text']]);
                $questionText->save();

                $question = new CMSQuestion($this->cmsModel, (object) ['name' => 'Question for ' . $lesson->LessonName, 'type' => CMSObject::CMS_ENTITY_TYPES['Question']]);
                $question->getBaseObject()->has->primary_element = $questionText->getBaseObject();
                $question->save();

                array_push($quiz->getBaseObject()->has->questions, $question->getBaseObject());
                $quiz->save();
            }

            if (empty($this->seedData['lessons'][$lesson->id]['questions'][$lesson->question_id]['answers'])) {
                $this->seedData['lessons'][$lesson->id]['questions'][$lesson->question_id]['answers'] = [];
            }

            if (empty($this->seedData['lessons'][$lesson->id]['questions'][$lesson->question_id][$lesson->question_id]['answers'][$lesson->answer_id]) && $lesson->AnswerStem != null) {
                $this->seedData['lessons'][$lesson->id]['questions'][$lesson->question_id][$lesson->question_id]['answers'][$lesson->answer_id] = [];

                $answerText = new CMSText($this->cmsModel, (object) ['description' => $lesson->AnswerStem, 'type' => CMSObject::CMS_ENTITY_TYPES['Text']]);
                $answerText->save();

                $answer = new CMSAnswer($this->cmsModel, (object) ['name' => 'Answer for ' . $lesson->LessonName, 'is_correct' => $lesson->IsAnswerCorrect, 'type' => CMSObject::CMS_ENTITY_TYPES['Answer']]);
                $answer->getBaseObject()->has->question = $question->getBaseObject();
                $answer->getBaseObject()->has->primary_element = $answerText->getBaseObject();
                $answer->save();

                array_push($question->getBaseObject()->has->answers, $answer->getBaseObject());
                $question->save();
            }
        }
    }

    public function seedTracks()
    {
        $tracks = $this->seedModel->getTracks();
        $this->seedData['tracks'] = [];
        foreach ($tracks as $track) {
            if (empty($this->seedData['tracks'][$track->id])) {
                $this->seedData['tracks'][$track->id] = [];

                $trackObj = new CMSTrack($this->cmsModel, (object)[
                    'name' => $track->TrackName,
                    'description' => $track->TrackDescription,
                    'short_description' => $track->TrackShortDescription,
                    'slug' => $track->TrackSlug,
                    'seo_meta_title' => $track->SEOTitle,
                    'seo_meta_description' => $track->SEODescription,
                    'seo_meta_keywords' => $track->SEOKeyWords,
                    'type' => CMSObject::CMS_ENTITY_TYPES['Track'],
                ]);


                $lessons = $this->seedModel->getLessonsInTrack($track->id);
                foreach ($lessons as $lesson) {
                    if (isset($this->seedData['lesson_objects'][$lesson->id])) {
                        array_push($trackObj->getBaseObject()->has->lessons, $this->seedData['lesson_objects'][$lesson->id]->cmObj);
                    }
                }
                echo "Saving Track: " . $track->TrackName . "\n";
                $trackObj->save();
            }
        }
    }

    public function addLookups()
    {
        $this->addEntityLookups();

        //DB::statement("ALTER TABLE cms_objects ADD FULLTEXT INDEX cms_objects_fulltext_index(name, description, attr1str, attr2str, attr1text)");

        /*DB::statement("ALTER TABLE cms_objects ADD COLUMN created_by INT(10) UNSIGNED DEFAULT 2");
        DB::statement("ALTER TABLE cms_objects ADD COLUMN updated_by INT(10) UNSIGNED DEFAULT 2");
        DB::statement("ALTER TABLE cms_objects ADD INDEX IX_created_by(attr1sint, created_by)");
        DB::statement("ALTER TABLE cms_objects ADD INDEX IX_updated_by(attr1sint, updated_by)");
        DB::statement("ALTER TABLE cms_objects ADD INDEX IX_updated_at(attr1sint, updated_at)");
        DB::statement("ALTER TABLE cms_objects ADD COLUMN revision SMALLINT(5) UNSIGNED DEFAULT 1");
        DB::statement("ALTER TABLE cms_objects ADD COLUMN revision_at TIMESTAMP NULL DEFAULT NULL");
        DB::statement("ALTER TABLE cms_objects ADD INDEX IX_revision(attr1sint, revision)");
        DB::statement("ALTER TABLE cms_objects ADD INDEX IX_revision_at(attr1sint, revision_at)");
        DB::statement("ALTER TABLE cms_objects ADD COLUMN is_dirty BOOL DEFAULT 0");
        DB::statement("ALTER TABLE cms_objects ADD INDEX IX_is_dirty(is_dirty)");
        DB::statement("CREATE TABLE lib_objects LIKE cms_objects");

        DB::statement("ALTER TABLE cms_relations ADD INDEX IX_from_id(attr1int)");
        DB::statement("ALTER TABLE cms_relations ADD INDEX IX_to_id(attr2int)");
        DB::statement("ALTER TABLE cms_relations ADD COLUMN is_dirty BOOL DEFAULT 0");
        DB::statement("ALTER TABLE cms_relations ADD INDEX IX_is_dirty(is_dirty)");
        DB::statement("CREATE TABLE lib_relations LIKE cms_relations");*/

        /*Schema::create('sessions', function ($table) {
            $table->string('id')->unique();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('payload');
            $table->integer('last_activity');
        });*/

        /*DB::statement("INSERT IGNORE INTO cms_rel_cms_obj_quizzes_has_cms_obj_tags(from_id, to_id, type, item_order, is_dirty, created_at, updated_at)
            SELECT lq.to_id
                , lt.to_id
                , (SELECT id
                    FROM cms_lu_relation_types
                    WHERE name = 'cms_rel_cms_obj_quizzes_has_cms_obj_tags'
                )
                , lt.item_order
                , 1
                , lt.created_at
                , lt.updated_at
            FROM cms_rel_cms_obj_lessons_has_cms_obj_quizzes lq
            JOIN cms_rel_cms_obj_lessons_has_cms_obj_tags lt ON lq.from_id = lt.from_id;");*/

        $this->seeder();
        /*$objectId = 961;
        $co = new CMSLesson($this->cmsModel, new \stdClass);
        $l1 = $co->getCMSObjectByIdLean($objectId);
        print_r($l1);*/
        return;

        //$this->addRelationLookups();
        /*$object = new \stdClass();
        //$object = $lo->getBaseObject();
        $object->name = "HFPBB DJPBB HSPBB";
        $object->description = "HFPBCM DJPBCM HSPBCM";
        $object->type = CMSObject::CMS_ENTITY_TYPES['Lesson'];
        $object->is_deleted = 0;
        $object->slug = "slug5";
        $object->search_data = "search data";
        $object->title = "title5";
        $object->short_description = "short description";
        $object->is_published = 1;
        $object->published_at = date("Y-m-d H:i:s", time());
        $object->status = 1;
        $object->seo_meta_title = "seo title5";
        $object->seo_meta_description = "seo description5";
        $object->seo_meta_keywords = "seo keywords5";
        $object->has = new \stdClass();
        $object->has->tags = [];
        //$object->id = 16;
        //$id = $lo->delete($object);
        $tagGroup = new \stdClass();
        $tagGroup->name = "Skill Level";
        $tagGroup->type = CMSObject::CMS_ENTITY_TYPES['TagGroup'];
        $tagGroup->has = new \stdClass();
        $tagGroup->has->tags = [];
        $tag1 = new \stdClass();
        $tag1->name = "Easy";
        $tag1->has = new \stdClass();
        $tag1->has->tag_group = null;
        $tag1->type = CMSObject::CMS_ENTITY_TYPES['Tag'];
        $tag2 = new \stdClass();
        $tag2->name = "Medium";
        $tag2->has = new \stdClass();
        $tag2->has->tag_group = null;
        $tag2->type = CMSObject::CMS_ENTITY_TYPES['Tag'];
        $tag3 = new \stdClass();
        $tag3->name = "Hard";
        $tag3->type = CMSObject::CMS_ENTITY_TYPES['Tag'];
        $tag3->has = new \stdClass();
        $tag3->has->tag_group = null;

        $tg = new CMSTagGroup($this->cmsModel, $tagGroup);
        $tg->scratch();
        $tn = $tag1;
        $tn = new CMSTag($this->cmsModel, $tn);
        $tn->scratch();

        $tg->save();
        $tag1->has->tag_group = $tg->getBaseObject();
        $tag2->has->tag_group = $tg->getBaseObject();
        $tag3->has->tag_group = $tg->getBaseObject();

        $t1 = new CMSTag($this->cmsModel, $tag1);
        $t2 = new CMSTag($this->cmsModel, $tag2);
        $t3 = new CMSTag($this->cmsModel, $tag3);

        $t1->save();
        $t2->save();
        $t3->save();

        print_r($tag1);

        $tg->getBaseObject()->has->tags = [
            $t1->getBaseObject(),
            $t2->getBaseObject(),
            $t3->getBaseObject(),
        ];
        $tg->save();

        $a11text = new CMSText($this->cmsModel, (object) ['name' => 'a11', 'type' => CMSObject::CMS_ENTITY_TYPES['Text']]);
        $a12text = new CMSText($this->cmsModel, (object) ['name' => 'a12', 'type' => CMSObject::CMS_ENTITY_TYPES['Text']]);
        $a11text->scratch();
        $a11text->save();
        $a12text->save();

        $a21text = new CMSText($this->cmsModel, (object) ['name' => 'a21', 'type' => CMSObject::CMS_ENTITY_TYPES['Text']]);
        $a22text = new CMSText($this->cmsModel, (object) ['name' => 'a22', 'type' => CMSObject::CMS_ENTITY_TYPES['Text']]);
        $a21text->save();
        $a22text->save();

        $a11 = new CMSAnswer($this->cmsModel, (object) ['name' => 'answer 11', 'is_correct' => 1, 'type' => CMSObject::CMS_ENTITY_TYPES['Answer']]);
        $a11->scratch();
        $a11->getBaseObject()->has->primary_element = $a11text->getBaseObject();

        $a12 = new CMSAnswer($this->cmsModel, (object) ['name' => 'answer 12', 'is_correct' => 0, 'type' => CMSObject::CMS_ENTITY_TYPES['Answer']]);
        $a12->getBaseObject()->has->primary_element = $a12text->getBaseObject();

        $a21 = new CMSAnswer($this->cmsModel, (object) ['name' => 'answer 21', 'is_correct' => 0, 'type' => CMSObject::CMS_ENTITY_TYPES['Answer']]);
        $a21->getBaseObject()->has->primary_element = $a21text->getBaseObject();

        $a22 = new CMSAnswer($this->cmsModel, (object) ['name' => 'answer 22', 'is_correct' => 1, 'type' => CMSObject::CMS_ENTITY_TYPES['Answer']]);
        $a22->getBaseObject()->has->primary_element = $a22text->getBaseObject();

        $a11->save();
        $a12->save();
        $a21->save();
        $a22->save();

        $q1text = new CMSText($this->cmsModel, (object) ['name' => 'q1', 'type' => CMSObject::CMS_ENTITY_TYPES['Text']]);
        $q2text = new CMSText($this->cmsModel, (object) ['name' => 'q2', 'type' => CMSObject::CMS_ENTITY_TYPES['Text']]);
        $q1text->save();
        $q2text->save();

        $q1 = new CMSQuestion($this->cmsModel, (object) ['name' => 'question 1', 'type' => CMSObject::CMS_ENTITY_TYPES['Question']]);
        $q1->scratch();
        $q1->getBaseObject()->has->answers = [
            $a11->getBaseObject(),
            $a12->getBaseObject(),
        ];
        $q1->getBaseObject()->has->primary_element = $q1text->getBaseObject();
        $q1->save();

        $q2 = new CMSQuestion($this->cmsModel, (object) ['name' => 'question 2', 'type' => CMSObject::CMS_ENTITY_TYPES['Question']]);
        $q2->getBaseObject()->has->answers = [
            $a21->getBaseObject(),
            $a22->getBaseObject(),
        ];
        $q2->getBaseObject()->has->primary_element = $q2text->getBaseObject();
        $q2->save();

        $a11->getBaseObject()->has->question = $q1->getBaseObject();
        $a12->getBaseObject()->has->question = $q1->getBaseObject();
        $a21->getBaseObject()->has->question = $q2->getBaseObject();
        $a22->getBaseObject()->has->question = $q2->getBaseObject();

        $a11->save();
        $a12->save();
        $a21->save();
        $a22->save();


        $quiz1 = new CMSQuiz($this->cmsModel, (object) ['name' => 'quiz 1', 'type' => CMSObject::CMS_ENTITY_TYPES['Quiz']]);
        $quiz1->scratch();
        $quiz1->getBaseObject()->has->questions = [
            $q1->getBaseObject(),
            $q2->getBaseObject(),
        ];
        $quiz1->save();

        $v1 = new CMSVideo($this->cmsModel, (object) ['name' => 'video 1', 'type' => CMSObject::CMS_ENTITY_TYPES['Video']]);
        $v1->scratch();
        $v1->save();


        $lo = new CMSLesson($this->cmsModel, $object);
        $lo->scratch();

        $lo->getBaseObject()->has->tags = [
            $t1->getBaseObject(),
            $t2->getBaseObject(),
            $t3->getBaseObject(),
        ];

        $lo->getBaseObject()->has->quiz = $quiz1->getBaseObject();
        $lo->getBaseObject()->has->primary_element = $v1->getBaseObject();

        $id = $lo->save();

        echo "ID: " . $id . "\n";
        */
    }

    public function addEntityLookups()
    {
        $lookupTable = 'cms_lu_object_types';
        foreach (CMSObject::CMS_ENTITY_TYPES as $lookupName => $lookupId) {
            $this->addAndGetLookup($lookupId, $lookupName, $lookupTable);
        }
    }

    public function addRelationLookups()
    {
        $lookupTable = 'cms_lu_relation_types';
        foreach (CMSRelation::CMS_RELATION_TYPES as $lookupName => $lookupId) {
            $this->addAndGetLookup($lookupId, $lookupName, $lookupTable);
        }
    }

    public function addAndGetLookup($lookupId, $lookupName, $lookupTable)
    {
        $this->cmsLookup->addAndGetLookupById($lookupId, $lookupName, $lookupTable);
    }

    public function getMenuItems()
    {
        return CMSObject::getMenuItems();
    }

    public function getNumPages($totalRecords, $pageSize)
    {
        if ($pageSize <= 0) {
            return 0;
        }

        $numPages = ceil(($totalRecords / $pageSize));

        return $numPages;
    }

    public function getPageNumbers($pageNum, $numPages)
    {
        $pageNos = [];
        if ($pageNum > 5 && $pageNum < $numPages - 5) {
            $pageNos = range($pageNum - 5, min($numPages, $pageNum - 5 + 9));
        }
        elseif ($pageNum > 5 && $pageNum >= $numPages - 5) {
            $pageNos = range(max(1, $numPages - 9), $numPages);
        }
        elseif ($pageNum <= 5) {
            $pageNos = range(1, min(10, $numPages));
        }

        return $pageNos;
    }

    public function listObjects($objectType, $selectedFilters, $start, $limit)
    {
        $objects = [];
        $objectTypeId = !empty(CMSObject::CMS_ENTITY_TYPES[$objectType]) ? CMSObject::CMS_ENTITY_TYPES[$objectType] : null;
        if (empty($objectTypeId)) {
            return $objects;
        }

        $objectClass = CMSObject::CMS_ENTITY_CLASSES[$objectTypeId];
        $object = new $objectClass($this->cmsModel, new \stdClass());
        $objects = $object->getActiveObjectsLean(($start - 1) * $limit, $limit, $selectedFilters);
        $numObjects = $object->getActiveObjectsLeanCount($selectedFilters);

        $numPages = $this->getNumPages($numObjects, $limit);
        $pageNos = $this->getPageNumbers($start, $numPages);

        $returnData = [
            'objects' => $objects,
            'num_objects' => $numObjects,
            'page_num' => $start,
            'num_pages' => $numPages,
            'page_number_array' => $pageNos,
        ];

        return $returnData;
    }

    public function deletedObjects($objectType, $selectedFilters, $start, $limit)
    {
        $objects = [];
        $objectTypeId = !empty(CMSObject::CMS_ENTITY_TYPES[$objectType]) ? CMSObject::CMS_ENTITY_TYPES[$objectType] : null;
        if (empty($objectTypeId)) {
            return $objects;
        }

        $objectClass = CMSObject::CMS_ENTITY_CLASSES[$objectTypeId];
        $object = new $objectClass($this->cmsModel, new \stdClass());
        $objects = $object->getDeletedObjectsLean(($start - 1) * $limit, $limit, $selectedFilters);
        $numObjects = $object->getDeletedObjectsLeanCount($selectedFilters);

        $numPages = $this->getNumPages($numObjects, $limit);
        $pageNos = $this->getPageNumbers($start, $numPages);

        $returnData = [
            'objects' => $objects,
            'num_objects' => $numObjects,
            'page_num' => $start,
            'num_pages' => $numPages,
            'page_number_array' => $pageNos,
        ];

        return $returnData;
    }

    public function getObject($objectType, $objectId = null)
    {
        $objectTypeId = !empty(CMSObject::CMS_ENTITY_TYPES[$objectType]) ? CMSObject::CMS_ENTITY_TYPES[$objectType] : null;
        if (empty($objectTypeId)) {
            return null;
        }

        $objectClass = CMSObject::CMS_ENTITY_CLASSES[$objectTypeId];
        $object = new $objectClass($this->cmsModel, new \stdClass());
        if (!empty($objectId)) {
            $object = $object->getObjectById($objectId);
        }
        return $object;
    }

    public function getBaseObjectFromRequest($objectType, $request)
    {
        $objectTypeId = !empty(CMSObject::CMS_ENTITY_TYPES[$objectType]) ? CMSObject::CMS_ENTITY_TYPES[$objectType] : null;
        if (empty($objectTypeId)) {
            return null;
        }

        $objectClass = CMSObject::CMS_ENTITY_CLASSES[$objectTypeId];
        $object = new $objectClass($this->cmsModel, new \stdClass());
        return $object->getBaseObjectFromRequest($request);
    }

    public function saveObject($objectType, $baseObject)
    {
        $objectTypeId = !empty(CMSObject::CMS_ENTITY_TYPES[$objectType]) ? CMSObject::CMS_ENTITY_TYPES[$objectType] : null;
        if (empty($objectTypeId)) {
            return null;
        }

        $objectClass = CMSObject::CMS_ENTITY_CLASSES[$objectTypeId];
        $object = new $objectClass($this->cmsModel, $baseObject);
        return $object->saveObject();
    }

    public function deleteObject($objectType, $objectId = null)
    {
        $objectTypeId = !empty(CMSObject::CMS_ENTITY_TYPES[$objectType]) ? CMSObject::CMS_ENTITY_TYPES[$objectType] : null;
        if (empty($objectTypeId)) {
            return null;
        }

        $objectClass = CMSObject::CMS_ENTITY_CLASSES[$objectTypeId];
        $object = new $objectClass($this->cmsModel, new \stdClass());
        if (!empty($objectId)) {
            $object = $object->getObjectById($objectId);
            $object->delete();
        }
    }

    public function getValidationArray($objectType)
    {
        $objectTypeId = !empty(CMSObject::CMS_ENTITY_TYPES[$objectType]) ? CMSObject::CMS_ENTITY_TYPES[$objectType] : null;
        if (empty($objectTypeId)) {
            return [];
        }

        $objectClass = CMSObject::CMS_ENTITY_CLASSES[$objectTypeId];
        $object = new $objectClass($this->cmsModel, new \stdClass());
        return $object->getValidationArray();
    }

    public function validateForm($objectType, $request)
    {
        $objectTypeId = !empty(CMSObject::CMS_ENTITY_TYPES[$objectType]) ? CMSObject::CMS_ENTITY_TYPES[$objectType] : null;
        if (empty($objectTypeId)) {
            return [];
        }

        $objectClass = CMSObject::CMS_ENTITY_CLASSES[$objectTypeId];
        $object = new $objectClass($this->cmsModel, new \stdClass());
        return $object->validateForm($request);
    }

    public function getTagsInTagGroup($tagGroupId)
    {
        $object = new CMSTag($this->cmsModel, new \stdClass());
        $objects = $object->getTagsInTagGroup($tagGroupId);

        return $objects;
    }

    public function getLessonsInTag($tagId)
    {
        $object = new CMSTag($this->cmsModel, new \stdClass());
        $objects = $object->getLessonsInTag($tagId);

        return $objects;
    }

    public function getTracksInTag($tagId)
    {
        $object = new CMSTag($this->cmsModel, new \stdClass());
        $objects = $object->getTracksInTag($tagId);

        return $objects;
    }

    public function getQuizzesInTag($tagId)
    {
        $object = new CMSTag($this->cmsModel, new \stdClass());
        $objects = $object->getQuizzesInTag($tagId);

        return $objects;
    }

    public function getFilterByTags($objectType)
    {
        $objectTypeId = !empty(CMSObject::CMS_ENTITY_TYPES[$objectType]) ? CMSObject::CMS_ENTITY_TYPES[$objectType] : null;
        if (empty($objectTypeId)) {
            return [];
        }

        $objectClass = CMSObject::CMS_ENTITY_CLASSES[$objectTypeId];
        $object = new $objectClass($this->cmsModel, new \stdClass());
        return $object->getFilterByTags();
    }

    public function getTagsInTagGroupByName($objectType, $tagGroup)
    {
        $objectTypeId = !empty(CMSObject::CMS_ENTITY_TYPES[$objectType]) ? CMSObject::CMS_ENTITY_TYPES[$objectType] : null;
        if (empty($objectTypeId)) {
            return [];
        }

        $objectClass = CMSObject::CMS_ENTITY_CLASSES[$objectTypeId];
        $object = new $objectClass($this->cmsModel, new \stdClass());
        return $object->getTagsInTagGroupByName($tagGroup);
    }

    public function listCommits($commitType)
    {
        $objects = [];
        if ($commitType == 'my') {
            $objects = $this->revisioning->getMyDirtyObjects();
        }
        else {
            $objects = $this->revisioning->getAllDirtyObjects();
        }

        $returnData = [
            'objects' => $objects,
            'num_objects' => count($objects),
        ];

        return $returnData;
    }

    public function saveCommits($committedObjectIds)
    {
        if (count($committedObjectIds) > 0) {
            $this->revisioning->saveCommits($committedObjectIds);
        }
    }

    public function publishObjects()
    {
        return $this->revisioning->publishObjects();
    }
}