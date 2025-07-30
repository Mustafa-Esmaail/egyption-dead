<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Http\Requests\Admin\TeamGroupRequest;
use App\Http\Traits\Upload_Files;

use App\Models\TeamGroup;
use App\Models\Team;
use App\Models\Notification;
use App\Services\FirebaseNotificationService;

class TeamGroupController extends Controller
{
    //
    use ResponseTrait,Upload_Files;

    private $view_path = 'Admin.CRUDS.teamGroups.';
    protected $permission = 'team-groups';

    public function __construct()
    {
        // $permission = ;
        $this->middleware("permission:show $this->permission")->only('index');
        $this->middleware("permission:add $this->permission")->only(['create', 'store']);
        $this->middleware("permission:edit $this->permission")->only(['edit', 'update']);
        $this->middleware("permission:delete $this->permission")->only('destroy');
    }

    public function index(Request $request)
    {


        if ($request->ajax()) {

            $groups = TeamGroup::query()->latest();

            return DataTables::of($groups)
                ->addColumn('action', function ($group) {
                    return $this->crud_action_btns($group->id, $this->permission);
                })
                ->editColumn('title', function ($group) {
                    return $group->title;
                })
                ->editColumn('status', function ($admin) {
                    $status=[
                        1=>'pending',
                        2=>'aproved',
                        3=>'rejected',
                        // 4=>'hide',
                    ];
                    $html = '<select class="form-control m-2 w-50 change-status" data-id="' . $admin->id . '">';
                    foreach ($status as $key => $value) {
                        $selected = $admin->status == $key ? 'selected' : '';
                        $html .= '<option class="m-2" value="' . $key . '" ' . $selected . '>' . $value . '</option>';
                    }
                    $html .= '</select>';

                    return $html;
                })
                ->editColumn('image', function ($group) {
                    return $this->show_table_image($group->image);
                })
                ->escapeColumns([])
                ->make(true);
        }

        $permission = $this->permission;
        return view($this->view_path . '.index', compact('permission'));
    }



    public function create()
    {

        $data['teams'] = Team::get();
        return view($this->view_path.'parts.create',compact('data'));
    }

    public function store(TeamGroupRequest $request)
    {

        try{

            $data = $request->validationData();

            DB::beginTransaction();

            if ($request->image)
                $data["image"] = $this->uploadFiles('team', $request->file('image'), null);

            $data['added_by'] = '2';
            $row = TeamGroup::create($data);

            DB::commit();

            return $this->addResponse();

        }catch(Exception $e){
            DB::rollback();
            $message = 'Error Line: '. $e->getLine().' - ' . $e->getMessage();
            return $this->errorResponse($message);
        }

    }



    public function show($id)
    {


        //
    }


    public function edit($id)
    {

        $data['group']=TeamGroup::findOrFail($id);
        $data['teams']=Team::get();

        return view($this->view_path.'parts.edit', compact('data'));

    }

    public function update(TeamGroupRequest $request, $id)
    {

        try{

            $data = $request->validationData();


            DB::beginTransaction();

            $row=TeamGroup::findOrFail($id);

            if ($request->image)
                $data["image"] = $this->uploadFiles('team', $request->file('image'), 'yes');


            $row->update($data);

            DB::commit();

            return $this->updateResponse();

        }catch(Exception $e){
            DB::rollback();
            $message = 'Error Line: '. $e->getLine().' - ' . $e->getMessage();
            return $this->errorResponse($message);
        }


    }


    public function destroy($id)
    {

        Team::findOrFail($id)->delete();

        return $this->deleteResponse();
    }//end fun
    // **********************************************
    // **********************************************
    public function updateStatus(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:team_groups,id',
            'status' => 'required|in:1,2,3,4',
        ]);

        $row = TeamGroup::find($validated['id']);
        $row->status = $validated['status'];
        $row->save();

        $status = [
            1 => 'pending',
            2 => 'approved',
            3 => 'rejected',
            // 4 => 'hide',
        ];
        $message = "Team Group is " . $status[$validated['status']];

        // Store database notification
        $notificationData = [
            'action_id' => $validated['id'],
            'model_name' => 'team_group',
            'user_id' => $row->user_id,
            'message' => helperTrans("api.$message"),
        ];
        Notification::storeNotification($notificationData);

        // Send push notification if user exists
        if ($row->user) {
            $firebaseService = app(FirebaseNotificationService::class);
            $firebaseService->sendToUser(
                $row->user,
                'Team Group Status Update',
                $message,
                [
                    'team_group_id' => $row->id,
                    'status' => $validated['status'],
                    'type' => 'team_group_status_update'
                ]
            );
        }

        return $this->updateResponse();
    }



}
