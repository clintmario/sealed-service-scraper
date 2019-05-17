<?php

namespace App\Modules\Application\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Modules\Home\Models\ContactModel;
use App\Modules\User\Services\UserService;
use App\Modules\Application\Services\LibraryService;
use App\Modules\Core\Entities\Core;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{
    protected $userService;
    protected $libraryService;

    protected $fields = [
        'name',
        'email',
        'subject',
        'message',
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->userService = Core::getService(UserService::class);
        $this->libraryService = Core::getService(LibraryService::class);
        //$this->middleware('auth');
    }

    public function index()
    {
        $subjects = $this->libraryService->getSubjects();

        return View('Fortuna.Application::Base.landing', [
            'menuItems' => [],
            'sections' => ['landing'],
            'isUserAdmin' => $this->userService->isUserAdmin(Auth::user()->id ?? 0),
            'libraryService' => $this->libraryService,
            'subjects' => $subjects,
        ]);
    }

    public function courses(Request $request)
    {
        $subjectId = $request->input('subject_id');
        if (!empty($subjectId)) {
            $courses = $this->libraryService->getCoursesBySubjectId($subjectId);
        } else {
            $courses = $this->libraryService->getCourses();
        }

        return View('Fortuna.Application::Base.landing', [
            'menuItems' => [],
            'sections' => ['courses'],
            'isUserAdmin' => $this->userService->isUserAdmin(Auth::user()->id ?? 0),
            'libraryService' => $this->libraryService,
            'courses' => $courses,
        ]);
    }

    public function assignments(Request $request)
    {
        $courseId = $request->input('course_id');
        if (!empty($courseId)) {
            $assignments = $this->libraryService->getAssignmentsByCourseId($courseId);
        } else {
            $assignments = $this->libraryService->getAssignments();
        }

        return View('Fortuna.Application::Base.landing', [
            'menuItems' => [],
            'sections' => ['assignments'],
            'isUserAdmin' => $this->userService->isUserAdmin(Auth::user()->id ?? 0),
            'libraryService' => $this->libraryService,
            'assignments' => $assignments,
        ]);
    }

    public function tracks(Request $request)
    {
        $assignmentId = $request->input('assignment_id');
        if (!empty($assignmentId)) {
            $tracks = $this->libraryService->getTracksByAssignmentId($assignmentId);
        } else {
            $tracks = $this->libraryService->getTracks();
        }

        return View('Fortuna.Application::Base.landing', [
            'menuItems' => [],
            'sections' => ['tracks'],
            'isUserAdmin' => $this->userService->isUserAdmin(Auth::user()->id ?? 0),
            'libraryService' => $this->libraryService,
            'tracks' => $tracks,
        ]);
    }

    public function lessons(Request $request)
    {
        $trackId = $request->input('track_id');
        $tagId = $request->input('tag_id');
        if (!empty($trackId)) {
            $lessons = $this->libraryService->getLessonsByTrackId($trackId);
        } elseif (!empty($tagId)) {
            $lessons = $this->libraryService->getLessonsByTagId($tagId);
        } else {
            $lessons = $this->libraryService->getLessons();
        }

        return View('Fortuna.Application::Base.landing', [
            'menuItems' => [],
            'sections' => ['lessons'],
            'isUserAdmin' => $this->userService->isUserAdmin(Auth::user()->id ?? 0),
            'libraryService' => $this->libraryService,
            'lessons' => $lessons,
        ]);
    }

    public function tags(Request $request)
    {
        $tags = $this->libraryService->getTags();

        return View('Fortuna.Application::Base.landing', [
            'menuItems' => [],
            'sections' => ['tags'],
            'isUserAdmin' => $this->userService->isUserAdmin(Auth::user()->id ?? 0),
            'libraryService' => $this->libraryService,
            'tags' => $tags,
        ]);
    }
}
