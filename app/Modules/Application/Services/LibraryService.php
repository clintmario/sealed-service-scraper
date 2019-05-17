<?php
namespace App\Modules\Application\Services;

use App\Modules\Application\Models\LibraryModel;
use App\Modules\Application\Models\ApplicationModel;
use App\Modules\Core\Services\BaseService;
use Illuminate\Contracts\Foundation\Application;
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

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class LibraryService extends BaseService
{
    protected $libraryModel;
    protected $applicationModel;
    protected $eventService;

    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->libraryModel = new LibraryModel();
        $this->applicationModel = new ApplicationModel();
        $this->eventService = Core::getService(EventService::class);
        //$this->eventService->subscribe(EventService::EVENT_USER_LOGGED_IN, EventService::EVENT_TYPE_ASYNC, $this, 'insertLogin');
    }

    public function getTagsInTagGroupByName($tagGroupName)
    {
        return $this->libraryModel->getTagsInTagGroupByName($tagGroupName);
    }

    public function getTagsInTagGroupById($tagGroupId)
    {
        return $this->libraryModel->getTagsInTagGroupById($tagGroupId);
    }

    public function getSubjects()
    {
        $subjects = $this->getTagsInTagGroupByName('Broad Objective');
        foreach ($subjects as &$subject) {
            $subject->subject = $subject->tag_name;
            $subject->stats = $this->libraryModel->getSubjectStatistics($subject->tag_name);
            $subject->statsString = $subject->stats->num_courses . " courses, " . $subject->stats->num_assignments . " assignments, "
                . $subject->stats->num_tracks . " tracks, " . $subject->stats->num_lessons . " lessons";
            $subject->subject_id = $subject->stats->subject_id;
        }

        return $subjects;
    }

    public function getCourses()
    {
        $subjects = $this->getTagsInTagGroupByName('Broad Objective');
        $coursesArray = [];
        foreach ($subjects as $subject) {
            $courses = $this->getTagsInTagGroupByName($subject->tag_name);
            foreach ($courses as &$course) {
                $course->subject = $subject->tag_name;
                $course->course = $course->tag_name;
                $course->stats = $this->libraryModel->getCourseStatistics($subject->tag_name, $course->tag_name);
                $course->statsString = $course->stats->num_assignments . " assignments, "
                    . $course->stats->num_tracks . " tracks, " . $course->stats->num_lessons . " lessons";
                $course->course_id = $course->stats->course_id;

                array_push($coursesArray, $course);
            }
        }

        return $coursesArray;
    }

    public function getCoursesBySubjectId($subjectId)
    {
        $subjects = $this->getTagsInTagGroupById($subjectId);
        $coursesArray = [];
        foreach ($subjects as $subject) {
            $courses = $this->getTagsInTagGroupByName($subject->tag_group_name);
            foreach ($courses as &$course) {
                $course->subject = $subject->tag_group_name;
                $course->course = $course->tag_name;
                $course->stats = $this->libraryModel->getCourseStatistics($subject->tag_group_name, $course->tag_name);
                $course->statsString = $course->stats->num_assignments . " assignments, "
                    . $course->stats->num_tracks . " tracks, " . $course->stats->num_lessons . " lessons";
                $course->course_id = $course->stats->course_id;

                array_push($coursesArray, $course);
            }
        }

        return $coursesArray;
    }

    public function getAssignments()
    {
        $subjects = $this->getTagsInTagGroupByName('Broad Objective');
        $assignmentsArray = [];
        foreach ($subjects as $subject) {
            $assignments = $this->libraryModel->getAssignmentsInSubject($subject->tag_name);
            foreach ($assignments as &$assignment) {
                $assignment->subject = $subject->tag_name;
                $assignment->stats = $this->libraryModel->getAssignmentStatistics($subject->tag_name, $assignment->assignment_id);
                $assignment->statsString = $assignment->stats->num_tracks . " tracks, " . $assignment->stats->num_lessons . " lessons";

                array_push($assignmentsArray, $assignment);
            }
        }

        return $assignmentsArray;
    }

    public function getAssignmentsByCourseId($courseId)
    {
        $assignmentsArray = [];
        $assignments = $this->libraryModel->getAssignmentsByCourseId($courseId);

        foreach ($assignments as &$assignment) {
            $assignment->stats = $this->libraryModel->getAssignmentStatistics($assignment->subject, $assignment->assignment_id);
            $assignment->statsString = $assignment->stats->num_tracks . " tracks, " . $assignment->stats->num_lessons . " lessons";

            array_push($assignmentsArray, $assignment);
        }

        return $assignmentsArray;
    }

    public function getTracks()
    {
        $subjects = $this->getTagsInTagGroupByName('Broad Objective');
        $tracksArray = [];
        foreach ($subjects as $subject) {
            $tracks = $this->libraryModel->getTracksInSubject($subject->tag_name);
            foreach ($tracks as &$track) {
                $track->subject = $subject->tag_name;
                $track->stats = $this->libraryModel->getTrackStatistics($subject->tag_name, $track->track_id);
                $track->statsString = $track->stats->num_lessons . " lessons";

                array_push($tracksArray, $track);
            }
        }

        return $tracksArray;
    }

    public function getTracksByAssignmentId($assignmentId)
    {
        $tracksArray = [];
        $tracks = $this->libraryModel->getTracksByAssignmentId($assignmentId);

        foreach ($tracks as &$track) {
            $track->stats = $this->libraryModel->getTrackStatistics($track->subject, $track->track_id);
            $track->statsString = $track->stats->num_lessons . " lessons";

            array_push($tracksArray, $track);
        }

        return $tracksArray;
    }

    public function getLessons()
    {
        $subjects = $this->getTagsInTagGroupByName('Broad Objective');
        $lessonsArray = [];
        foreach ($subjects as $subject) {
            $lessons = $this->libraryModel->getLessonsInSubject($subject->tag_name);
            foreach ($lessons as &$lesson) {
                $lesson->subject = $subject->tag_name;
                $lesson->statsString = "";
                $assignments = $this->applicationModel->getAssignmentsForLesson($lesson->lesson_id);
                $lesson->assignment_id = $assignments[0]->assignment_id ?? null;

                array_push($lessonsArray, $lesson);
            }
        }

        return $lessonsArray;
    }

    public function getLessonsByTrackId($trackId)
    {
        $lessonsArray = [];
        $lessons = $this->libraryModel->getLessonsByTrackId($trackId);

        foreach ($lessons as &$lesson) {
            $lesson->statsString = "";
            $assignments = $this->applicationModel->getAssignmentsForLesson($lesson->lesson_id);
            $lesson->assignment_id = $assignments[0]->assignment_id ?? null;

            array_push($lessonsArray, $lesson);
        }

        return $lessonsArray;
    }

    public function getTags()
    {
        $tags = $this->libraryModel->getTags();

        foreach ($tags as &$tag) {
            if ($tag->tag_group_name == 'Broad Objective') {
                $tag->tag_group_name = 'Objective';
            }
            if ($tag->tag_group_name == 'Browse Category') {
                $tag->tag_group_name = 'Category';
            }
            $tag->stats = $this->libraryModel->getTagStatistics($tag->tag_id);
            $tag->statsString = $tag->stats->num_lessons . " lessons";
        }

        return $tags;
    }

    public function getLessonsByTagId($tagId)
    {
        $lessonsArray = [];
        $lessons = $this->libraryModel->getLessonsByTagId($tagId);

        foreach ($lessons as &$lesson) {
            $lesson->statsString = "";
            $assignments = $this->applicationModel->getAssignmentsForLesson($lesson->lesson_id);
            $lesson->assignment_id = $assignments[0]->assignment_id ?? null;

            array_push($lessonsArray, $lesson);
        }

        return $lessonsArray;
    }
}