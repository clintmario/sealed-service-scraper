<?php
namespace App\Modules\Application\Services;

use App\Modules\Application\Models\ApplicationModel;
use App\Modules\Application\Models\LibraryModel;
use App\Modules\Core\Services\BaseService;
use Illuminate\Contracts\Foundation\Application;
use App\Modules\Application\Services\LibraryService;
use App\Modules\Core\Services\EventService;
use App\Modules\Core\Entities\Core;
use App\Modules\Core\Entities\Log;
use Illuminate\Support\Facades\Mail;
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

class ApplicationService extends BaseService
{
    protected $applicationModel;
    protected $libraryModel;
    protected $libraryService;
    protected $eventService;

    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->applicationModel = new ApplicationModel();
        $this->libraryModel = new LibraryModel();
        $this->libraryService = Core::getService(LibraryService::class);
        $this->eventService = Core::getService(EventService::class);
        $this->eventService->subscribe(EventService::EVENT_USER_WATCHED_LESSON, EventService::EVENT_TYPE_ASYNC, $this, 'onWatchedLesson');
        $this->eventService->subscribe(EventService::EVENT_CONTACT_US, EventService::EVENT_TYPE_ASYNC, $this, 'sendContactEmail');
    }

    public function getPendingAssignments($userId)
    {
        $subjects = $this->libraryService->getTagsInTagGroupByName('Broad Objective');
        $assignmentsArray = [];
        foreach ($subjects as $subject) {
            $assignments = $this->applicationModel->getPendingAssignmentsInSubject($subject->tag_name, $userId);
            foreach ($assignments as &$assignment) {
                $assignment->subject = $subject->tag_name;
                $assignment->stats = $this->libraryModel->getAssignmentStatistics($subject->tag_name, $assignment->assignment_id);
                $assignment->statsString = $assignment->stats->num_tracks . " tracks, " . $assignment->stats->num_lessons . " lessons";

                array_push($assignmentsArray, $assignment);
            }
        }

        return $assignmentsArray;
    }

    public function getCompletedAssignments($userId)
    {
        $subjects = $this->libraryService->getTagsInTagGroupByName('Broad Objective');
        $assignmentsArray = [];
        foreach ($subjects as $subject) {
            $assignments = $this->applicationModel->getCompletedAssignmentsInSubject($subject->tag_name, $userId);
            foreach ($assignments as &$assignment) {
                $assignment->subject = $subject->tag_name;
                $assignment->stats = $this->libraryModel->getAssignmentStatistics($subject->tag_name, $assignment->assignment_id);
                $assignment->statsString = $assignment->stats->num_tracks . " tracks, " . $assignment->stats->num_lessons . " lessons";

                array_push($assignmentsArray, $assignment);
            }
        }

        return $assignmentsArray;
    }

    public function getAssignmentTracksAndLessons($assignmentId, $lessonId, $userId)
    {
        $assignment = new \StdClass();
        $track = new \stdClass();
        $isNextLesson = false;
        $assignment->next_lesson_id = null;

        $trackId = null;
        $lessons = $this->applicationModel->getAssignmentTracksAndLessons($assignmentId, $userId);
        foreach ($lessons as $lesson) {
            if (empty($assignment->assignment_id)) {
                $assignment->assignment_id = $lesson->assignment_id;
                $assignment->assignment_name = $lesson->assignment_name;
                $assignment->progress = $lesson->progress;
                $assignment->tracks = [];
            }

            if (!$this->isTrackInAssignment($assignment, $lesson->track_id)) {
                $track = new \stdClass();
                $track->track_id = $lesson->track_id;
                $track->track_name = $lesson->track_name;
                $track->has_current_lesson = false;
                $track->lessons = [];
                array_push($assignment->tracks, $track);
            }

            if (!$this->isLessonInTrack($track, $lesson->lesson_id)) {
                $lessonObj = new \stdClass();
                $lessonObj->lesson_id = $lesson->lesson_id;
                $lessonObj->lesson_name = $lesson->lesson_name;
                $lessonObj->is_current = false;
                if ($isNextLesson == true) {
                    $assignment->next_lesson_id = $lesson->lesson_id;
                    $isNextLesson = false;
                }
                if ($lesson->lesson_id == $lessonId) {
                    $track->has_current_lesson = true;
                    $lessonObj->is_current = true;
                    $isNextLesson = true;
                }
                $lessonObj->has_watched_lesson = $this->applicationModel->hasUserWatchedLesson($lesson->lesson_id, $userId);
                array_push($track->lessons, $lessonObj);
            }
        }
        return $assignment;
    }

    public function isTrackInAssignment($assignment, $trackId)
    {
        foreach($assignment->tracks as $track) {
            if ($track->track_id == $trackId) {
                return true;
            }
        }

        return false;
    }

    public function isLessonInTrack($track, $lessonId)
    {
        foreach($track->lessons as $lesson) {
            if ($lesson->lesson_id == $lessonId) {
                return true;
            }
        }

        return false;
    }

    public function getLesson($lessonId)
    {
        $lesson = $this->applicationModel->getLesson($lessonId);
        $lesson->tags = $this->applicationModel->getLessonTags($lessonId);

        return $lesson;
    }

    public function watchLesson($lessonId, $userId)
    {
        $eventObject = new \stdClass();
        $eventObject->lesson_id = $lessonId;
        $eventObject->user_id = $userId;
        $this->eventService->fire(EventService::EVENT_USER_WATCHED_LESSON, $eventObject);
    }

    public function onWatchedLesson($eventObject)
    {
        $lessonId = $eventObject->lesson_id;
        $userId = $eventObject->user_id;

        $this->applicationModel->insertLessonView($lessonId, $userId);

        $assignments = $this->applicationModel->getAssignmentsForLesson($lessonId);
        foreach ($assignments as $assignment) {
            $progress = $this->applicationModel->getAssignmentProgress($assignment->assignment_id, $userId);
            $numLessonsWatched = $progress->num_lessons_watched ?? 0;
            $totalLessons = $progress->total_lessons ?? 0;
            $this->applicationModel->insertAssignmentEnrollment($assignment->assignment_id, $userId);
            $assignmentEnrollment = $this->applicationModel->getAssignmentEnrollment($assignment->assignment_id, $userId);
            if (!empty($totalLessons)) {
                $percentProgress = floor($numLessonsWatched / $totalLessons * 100);
                if (empty($assignmentEnrollment->is_completed) && $percentProgress == 100) {
                    $this->applicationModel->markAssignmentAsCompleted($assignment->assignment_id, $userId);
                }
                $this->applicationModel->updateAssignmentEnrollmentProgress($assignment->assignment_id, $userId, $percentProgress);
            }
        }
    }

    public function nextLesson($assignmentId, $userId, $request)
    {
        $lesson = $this->applicationModel->getFirstUnwatchedLessonInAssignment($assignmentId, $userId);
        $firstLesson = $this->applicationModel->getFirstLessonInAssignment($assignmentId);
        if (!empty($lesson->lesson_id)) {
            if ($lesson->lesson_id == $firstLesson->lesson_id) {
                $assignment = $this->applicationModel->getAssignmentById($assignmentId);
                $message = "You are starting a new assignment: " . $assignment->name;
                $request->session()->flash('alert-success', $message);
            }
            return redirect('lesson?assignment_id=' . $assignmentId . '&lesson_id=' . $lesson->lesson_id);
        }

        if (!empty($firstLesson->lesson_id)) {
            return redirect('lesson?assignment_id=' . $assignmentId . '&lesson_id=' . $firstLesson->lesson_id);
        }

        return redirect('home');
    }

    public function nextLessonInAssignment($assignmentId, $lessonId, $userId, $request)
    {
        if (!empty($lessonId)) {
            return redirect('lesson?assignment_id=' . $assignmentId . '&lesson_id=' . $lessonId);
        }

        $assignmentEnrollment = $this->applicationModel->getAssignmentEnrollment($assignmentId, $userId);
        $assignment = $this->applicationModel->getAssignmentById($assignmentId);
        if (!empty($assignmentEnrollment->is_completed)) {
            $message = "You have completed the assignment: " . $assignment->name . ". It is now available in your " .
                "Completed Assignments section.";
            $request->session()->flash('alert-success', $message);
            return redirect('home');
        }

        $lesson = $this->applicationModel->getFirstUnwatchedLessonInAssignment($assignmentId, $userId);
        if (!empty($lesson->lesson_id)) {
            return redirect('lesson?assignment_id=' . $assignmentId . '&lesson_id=' . $lesson->lesson_id);
        }

        return redirect('home');
    }

    public function contactUs($name, $email, $subject, $message)
    {
        $this->applicationModel->addMessage($name, $email, $subject, $message);
        $contact = [
            'name' => $name,
            'email' => $email,
            'subject' => $subject,
            'message' => $message,
        ];
        $this->eventService->fire(EventService::EVENT_CONTACT_US, $contact);
    }

    public function sendContactEmail($contact)
    {
        $contact = (array) $contact;
        $email = 'info@bogex.com';

        Mail::send("Fortuna::email-application-contact-us", ['contact' => $contact], function($message) use ($contact, $email) {
            $message->subject("[Contact Message on ClassesMasses] " . $contact['subject'])
                ->to($email)
                ->replyTo('tech@bogex.com');
        });
    }
}