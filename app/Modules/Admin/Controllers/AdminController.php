<?php

namespace App\Modules\Admin\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Modules\User\Services\UserService;
use App\Modules\Admin\Services\AdminService;
use App\Modules\CMS\Services\CMSService;
use App\Modules\Core\Entities\Core;
use App\Modules\Core\Entities\Log;
use App\Modules\Core\Entities\Config;
use Illuminate\Support\Facades\Session;
use App\Modules\Core\Services\EventService;
use App\User;

class AdminController extends Controller
{
    protected $userService;
    protected $adminService;
    protected $cmsService;
    protected $eventService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->userService = Core::getService(UserService::class);
        $this->adminService = Core::getService(AdminService::class);
        $this->cmsService = Core::getService(CMSService::class);
        $this->eventService = Core::getService(EventService::class);
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!$this->userService->isUserAdmin(Auth::user()->id)) {
            return View('Fortuna.User::Auth.no-access', ['sections' => ['no-access']]);
        }

        return redirect('admin/actions');
    }

    public function actions()
    {
        if (!$this->userService->isUserAdmin(Auth::user()->id)) {
            return View('Fortuna.User::Auth.no-access', ['sections' => ['no-access']]);
        }

        return View('Fortuna.Admin::Base.admin', [
            'menuItems' => $this->cmsService->getMenuItems(),
            'sections' => ['actions'],
            'isUserAdmin' => $this->userService->isUserAdmin(Auth::user()->id),
        ]);
    }

    public function report_queries(Request $request)
    {
        if (!$this->userService->isUserAdmin(Auth::user()->id)) {
            return View('Fortuna.User::Auth.no-access', ['sections' => ['no-access']]);
        }

        $isQueryReportingEnabled = $request->input('report-query');
        if (!empty($isQueryReportingEnabled)) {
            Session::put(AdminService::ADMIN_SESSION_REPORT_QUERY_KEY, true);
        }
        else {
            Session::forget(AdminService::ADMIN_SESSION_REPORT_QUERY_KEY);
        }

        return redirect('admin');
    }

    public function get_force_login()
    {
        if (!$this->userService->isUserAdmin(Auth::user()->id)) {
            return View('Fortuna.User::Auth.no-access', ['sections' => ['no-access']]);
        }

        return View('Fortuna.Admin::Base.admin', [
            'menuItems' => $this->cmsService->getMenuItems(),
            'sections' => ['force-login'],
            'isUserAdmin' => $this->userService->isUserAdmin(Auth::user()->id),
        ]);
    }

    public function force_login(Request $request)
    {
        if (!$this->userService->isUserAdmin(Auth::user()->id)) {
            return View('Fortuna.User::Auth.no-access', ['sections' => ['no-access']]);
        }

        $forceLoginEmail = $request->input('email');
        $user = User::where('email', $forceLoginEmail)->first();
        if (!empty($user->id)) {
            Session::flush();
            Auth::loginUsingId($user->id);
            return redirect()->to('home');
        }
        else {
            $request->session()->flash('alert-warning', 'Could not find user with email: ' . $forceLoginEmail);
            return redirect()->to('admin/force_login');
        }
    }

    public function get_error_emails()
    {
        if (!$this->userService->isUserAdmin(Auth::user()->id)) {
            return View('Fortuna.User::Auth.no-access', ['sections' => ['no-access']]);
        }

        return View('Fortuna.Admin::Base.admin', [
            'menuItems' => $this->cmsService->getMenuItems(),
            'sections' => ['error-emails'],
            'isUserAdmin' => $this->userService->isUserAdmin(Auth::user()->id),
        ]);
    }

    public function send_error_emails(Request $request)
    {
        if (!$this->userService->isUserAdmin(Auth::user()->id)) {
            return View('Fortuna.User::Auth.no-access', ['sections' => ['no-access']]);
        }

        $this->adminService->throwSampleError();
    }
}
