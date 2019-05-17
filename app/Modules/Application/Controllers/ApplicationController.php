<?php

namespace App\Modules\Application\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Modules\Home\Models\ContactModel;
use App\Modules\User\Services\UserService;
use App\Modules\Application\Services\ApplicationService;
use App\Modules\Application\Services\LibraryService;
use App\Modules\Core\Entities\Core;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    protected $userService;
    protected $applicationService;
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
        $this->applicationService = Core::getService(ApplicationService::class);
        $this->libraryService = Core::getService(LibraryService::class);
        $this->middleware('auth');
    }

    public function index()
    {
        $assignments = $this->applicationService->getPendingAssignments(Auth::user()->id);

        return View('Fortuna.Application::Base.landing', [
            'menuItems' => [],
            'sections' => ['home'],
            'isUserAdmin' => $this->userService->isUserAdmin(Auth::user()->id ?? 0),
            'libraryService' => $this->libraryService,
            'assignments' => $assignments,
        ]);
    }

    public function completedAssignments()
    {
        $assignments = $this->applicationService->getCompletedAssignments(Auth::user()->id);

        return View('Fortuna.Application::Base.landing', [
            'menuItems' => [],
            'sections' => ['home-completed-assignments'],
            'isUserAdmin' => $this->userService->isUserAdmin(Auth::user()->id ?? 0),
            'libraryService' => $this->libraryService,
            'assignments' => $assignments,
        ]);
    }

    public function lesson(Request $request)
    {
        $assignmentId = $request->input("assignment_id");
        $lessonId = $request->input("lesson_id");

        if (empty($assignmentId) || empty($lessonId)) {
            return View('Fortuna.User::Auth.no-access', ['sections' => ['no-access']]);
        }

        $lesson = $this->applicationService->getLesson($lessonId);
        $assignment = $this->applicationService->getAssignmentTracksAndLessons($assignmentId, $lessonId, Auth::user()->id);

        if (empty($assignment->assignment_id) || empty($lesson->lesson_id)) {
            return View('Fortuna.User::Auth.no-access', ['sections' => ['no-access']]);
        }

        return View('Fortuna.Application::Base.landing', [
            'menuItems' => [],
            'sections' => ['lesson'],
            'isUserAdmin' => $this->userService->isUserAdmin(Auth::user()->id ?? 0),
            'libraryService' => $this->libraryService,
            'assignment' => $assignment,
            'lesson' => $lesson,
        ]);
    }

    public function watchLesson(Request $request)
    {
        $returnData = [
            'status' => 0,
        ];

        $userId = Auth::user()->id;
        $lessonId = $request->input('lesson_id');

        if (!empty($lessonId)) {
            $returnData['status'] = 1;

            $this->applicationService->watchLesson($lessonId, $userId);
        }

        return response()->json($returnData);
    }

    public function nextLesson(Request $request)
    {
        $assignmentId = $request->input("assignment_id");
        $userId = Auth::user()->id;

        return $this->applicationService->nextLesson($assignmentId, $userId, $request);
    }

    public function nextLessonInAssignment(Request $request)
    {
        $assignmentId = $request->input("assignment_id");
        $lessonId = $request->input("lesson_id");
        $userId = Auth::user()->id;

        return $this->applicationService->nextLessonInAssignment($assignmentId, $lessonId, $userId, $request);
    }

    public function contactUs(Request $request)
    {
        if (!$this->userService->isUserWebUser(Auth::user()->id)) {
            return View('Fortuna.User::Auth.no-access', ['sections' => ['no-access']]);
        }

        $name = $request->input('name');
        $email = $request->input('email');
        $subject = $request->input('subject');
        $message = $request->input('message');

        $posted = !empty($message);
        if (!empty($posted)) {
            $this->applicationService->contactUs($name, $email, $subject, $message);
            $request->session()->flash('alert-success', 'Thanks for reaching out to us. We will be in touch soon.');
        }

        return View('Fortuna.Application::Base.landing', [
            'menuItems' => [], //$this->cmsService->getMenuItems(),
            'sections' => ['contact-us'],
            'isUserAdmin' => $this->userService->isUserAdmin(Auth::user()->id),
            'applicationService' => $this->applicationService,
            'posted' => $posted,
        ]);
    }
}
