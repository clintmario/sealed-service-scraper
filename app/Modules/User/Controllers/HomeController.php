<?php

namespace App\Modules\User\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Modules\User\Services\UserService;
use App\Modules\Core\Entities\Core;
use App\Modules\Core\Entities\Log;

class HomeController extends Controller
{
    protected $userService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->userService = Core::getService(UserService::class);
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Log::info('User hit home page - ' . Auth::user()->email);

        if (!$this->userService->isUserWebUser(Auth::user()->id)) {
            return View('Fortuna.User::Auth.no-access', ['sections' => ['no-access']]);
        }
        return View('home', ['isUserAdmin' => $this->userService->isUserAdmin(Auth::user()->id)]);
    }

    public function admin()
    {
        if (!$this->userService->isUserAdmin(Auth::user()->id)) {
            return View('Fortuna.User::Auth.no-access', ['sections' => ['no-access']]);
        }
        return View('home', ['isUserAdmin' => $this->userService->isUserAdmin(Auth::user()->id)]);
    }
}
