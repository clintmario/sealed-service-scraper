<?php

namespace App\Modules\CMS\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Modules\User\Services\UserService;
use App\Modules\CMS\Services\CMSService;
use App\Modules\Core\Entities\Core;
use App\Modules\Core\Entities\Log;
use App\Modules\Core\Entities\Config;

class CMSController extends Controller
{
    protected $userService;
    protected $cmsService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->userService = Core::getService(UserService::class);
        $this->cmsService = Core::getService(CMSService::class);
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

        return redirect('cms/list?object_type=' . urlencode('Lesson'));
    }

    public function cms()
    {
        if (!$this->userService->isUserAdmin(Auth::user()->id)) {
            return View('Fortuna.User::Auth.no-access', ['sections' => ['no-access']]);
        }

        return redirect('cms/list?object_type=' . urlencode('Lesson'));
    }

    public function listObjects(Request $request)
    {
        if (!$this->userService->isUserAdmin(Auth::user()->id)) {
            return View('Fortuna.User::Auth.no-access', ['sections' => ['no-access']]);
        }

        $objectType = urldecode($request->input('object_type'));

        $selectedFilters = [];
        foreach ($this->cmsService->getFilterByTags($objectType) as $key => $tagGroup) {
            $selectedFilter = urldecode($request->input($key));
            if (!empty($selectedFilter)) {
                $selectedFilters[$key] = $selectedFilter;
            }
        }

        $searchKeywords = urldecode($request->input('search_keywords'));
        if (!empty($searchKeywords) && strlen($searchKeywords) >= 3) {
            $selectedFilters['search_keywords'] = $searchKeywords;
        }

        $pageNum = $request->input('page_num');
        if (empty($pageNum)) {
            $pageNum = 1;
        }
        $pageSize = 100;
        $objects = $this->cmsService->listObjects($objectType, $selectedFilters, $pageNum, $pageSize);

        return View('Fortuna.CMS::Base.cms', [
            'menuItems' => $this->cmsService->getMenuItems(),
            'sections' => ['list'],
            'objects' => $objects['objects'],
            'numObjects' => $objects['num_objects'],
            'pageNumber' => $objects['page_num'],
            'numPages' => $objects['num_pages'],
            'pageNumberArray' => $objects['page_number_array'],
            'pageSize' => $pageSize,
            'objectType' => $objectType,
            'isUserAdmin' => $this->userService->isUserAdmin(Auth::user()->id),
            'cmsService' => $this->cmsService,
            'selectedFilters' => $selectedFilters,
            'searchKeywords' => $searchKeywords,
        ]);
    }

    public function deletedObjects(Request $request)
    {
        if (!$this->userService->isUserAdmin(Auth::user()->id)) {
            return View('Fortuna.User::Auth.no-access', ['sections' => ['no-access']]);
        }

        $objectType = urldecode($request->input('object_type'));

        $selectedFilters = [];
        foreach ($this->cmsService->getFilterByTags($objectType) as $key => $tagGroup) {
            $selectedFilter = urldecode($request->input($key));
            if (!empty($selectedFilter)) {
                $selectedFilters[$key] = $selectedFilter;
            }
        }

        $searchKeywords = urldecode($request->input('search_keywords'));
        if (!empty($searchKeywords) && strlen($searchKeywords) >= 3) {
            $selectedFilters['search_keywords'] = $searchKeywords;
        }

        $pageNum = $request->input('page_num');
        if (empty($pageNum)) {
            $pageNum = 1;
        }
        $pageSize = 100;
        $objects = $this->cmsService->deletedObjects($objectType, $selectedFilters, $pageNum, $pageSize);

        return View('Fortuna.CMS::Base.cms', [
            'menuItems' => $this->cmsService->getMenuItems(),
            'sections' => ['list'],
            'objects' => $objects['objects'],
            'numObjects' => $objects['num_objects'],
            'pageNumber' => $objects['page_num'],
            'numPages' => $objects['num_pages'],
            'pageNumberArray' => $objects['page_number_array'],
            'pageSize' => $pageSize,
            'objectType' => $objectType,
            'isUserAdmin' => $this->userService->isUserAdmin(Auth::user()->id),
            'cmsService' => $this->cmsService,
            'selectedFilters' => $selectedFilters,
            'searchKeywords' => $searchKeywords,
        ]);
    }

    public function getObject(Request $request)
    {
        if (!$this->userService->isUserAdmin(Auth::user()->id)) {
            return View('Fortuna.User::Auth.no-access', ['sections' => ['no-access']]);
        }

        $objectType = urldecode($request->input('object_type'));
        $objectId = $request->input('object_id');
        $object = $this->cmsService->getObject($objectType, $objectId);

        return View('Fortuna.CMS::Base.cms', [
            'menuItems' => $this->cmsService->getMenuItems(),
            'sections' => ['save'],
            'objectType' => $objectType,
            'object' => $object,
            'isUserAdmin' => $this->userService->isUserAdmin(Auth::user()->id),
        ]);
    }

    public function deleteObject(Request $request)
    {
        if (!$this->userService->isUserAdmin(Auth::user()->id)) {
            return View('Fortuna.User::Auth.no-access', ['sections' => ['no-access']]);
        }

        $objectType = urldecode($request->input('object_type'));
        $objectId = $request->input('object_id');
        $this->cmsService->deleteObject($objectType, $objectId);

        return redirect('cms/list?object_type=' . urlencode($objectType));
    }

    public function saveObject(Request $request)
    {
        if (!$this->userService->isUserAdmin(Auth::user()->id)) {
            return View('Fortuna.User::Auth.no-access', ['sections' => ['no-access']]);
        }

        $objectType = $request->input('object_type');
        $validationArray = $this->cmsService->getValidationArray($objectType);

        $validator = Validator::make($request->all(), $validationArray);
        $validator->after(function ($validator) use($objectType, $request) {
            $errors = $this->cmsService->validateForm($objectType, $request);
            if (!empty($errors) && is_array($errors)) {
                foreach ($errors as $error) {
                    $validator->errors()->add($error['field'], $error['message']);
                }
            }
        });
        $validator->validate();

        Log::info('Validation successful.');
        $baseObject = $this->cmsService->getBaseObjectFromRequest($objectType, $request);
        $this->cmsService->saveObject($objectType, $baseObject);
        return redirect('cms/list?object_type=' . urlencode($objectType));
    }

    public function listTags(Request $request)
    {
        $returnData = [
            'status' => 0,
            'objects' => [],
        ];

        if (!$this->userService->isUserAdmin(Auth::user()->id)) {
            return response()->json($returnData);
        }

        $tagGroupId = $request->input('tag_group_id');

        $tags = $this->cmsService->getTagsInTagGroup($tagGroupId);

        $returnData['status'] = 1;
        $returnData['objects'] = $tags;

        return response()->json($returnData);
    }

    public function getLessonsInTag(Request $request)
    {
        $returnData = [
            'status' => 0,
            'objects' => [],
        ];

        if (!$this->userService->isUserAdmin(Auth::user()->id)) {
            return response()->json($returnData);
        }

        $tagId = $request->input('tag_id');

        $lessons = $this->cmsService->getLessonsInTag($tagId);

        $returnData['status'] = 1;
        $returnData['objects'] = array_values($lessons);

        return response()->json($returnData);
    }

    public function getTracksInTag(Request $request)
    {
        $returnData = [
            'status' => 0,
            'objects' => [],
        ];

        if (!$this->userService->isUserAdmin(Auth::user()->id)) {
            return response()->json($returnData);
        }

        $tagId = $request->input('tag_id');

        $tracks = $this->cmsService->getTracksInTag($tagId);

        $returnData['status'] = 1;
        $returnData['objects'] = array_values($tracks);

        return response()->json($returnData);
    }

    public function getQuizzesInTag(Request $request)
    {
        $returnData = [
            'status' => 0,
            'objects' => [],
        ];

        if (!$this->userService->isUserAdmin(Auth::user()->id)) {
            return response()->json($returnData);
        }

        $tagId = $request->input('tag_id');

        $quizzes = $this->cmsService->getQuizzesInTag($tagId);

        $returnData['status'] = 1;
        $returnData['objects'] = array_values($quizzes);

        return response()->json($returnData);
    }

    public function listCommits(Request $request)
    {
        if (!$this->userService->isUserAdmin(Auth::user()->id)) {
            return View('Fortuna.User::Auth.no-access', ['sections' => ['no-access']]);
        }

        $commitType = urldecode($request->input('commit_type'));
        if (empty($commitType)) {
            $commitType = 'all';
        }
        $objects = $this->cmsService->listCommits($commitType);

        return View('Fortuna.CMS::Base.cms', [
            'menuItems' => $this->cmsService->getMenuItems(),
            'sections' => ['commits'],
            'objects' => $objects['objects'],
            'numObjects' => $objects['num_objects'],
            'commitType' => $commitType,
            'isUserAdmin' => $this->userService->isUserAdmin(Auth::user()->id),
        ]);
    }

    public function saveCommits(Request $request)
    {
        set_time_limit(0);

        if (!$this->userService->isUserAdmin(Auth::user()->id)) {
            return View('Fortuna.User::Auth.no-access', ['sections' => ['no-access']]);
        }

        $committedObjectIds = $request->input('committed_object_ids');
        if (!is_array($committedObjectIds)) {
            $committedObjectIds = [];
        }

        $this->cmsService->saveCommits($committedObjectIds);
        $request->session()->flash('alert-success', count($committedObjectIds) . ' objects committed.');
        return redirect('cms/commit');
    }

    public function publishObjects(Request $request)
    {
        set_time_limit(0);

        $returnData = [
            'status' => 0,
        ];

        if (!$this->userService->isUserAdmin(Auth::user()->id)) {
            return View('Fortuna.User::Auth.no-access', ['sections' => ['no-access']]);
        }

        $startTime = microtime(true);
        $numObjectsPublished = $this->cmsService->publishObjects();
        $endTime = microtime(true);
        $request->session()->flash('alert-success', $numObjectsPublished . ' objects published. Time taken: ' . round($endTime - $startTime, 2) . 's.');

        $returnData['status'] = 1;
        return response()->json($returnData);
    }
}
