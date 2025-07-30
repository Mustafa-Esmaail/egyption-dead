<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Http\Requests\Admin\TeamPlayerRequest;

use App\Models\TeamPlayer;
use App\Models\Player;
use App\Models\Team;
use App\Models\City;
use App\Models\Country;
use App\Http\Traits\Upload_Files;

class TeamPlayerController extends Controller
{
    //
    use ResponseTrait,Upload_Files;
    private $view_path = 'Admin.CRUDS.team.players.';

    protected $permission = 'teamPlayers';

    public function __construct()
    {
        $this->middleware("permission:show $this->permission")->only('index');
        $this->middleware("permission:add $this->permission")->only(['create', 'store']);
        $this->middleware("permission:edit $this->permission")->only(['edit', 'update']);
        $this->middleware("permission:delete $this->permission")->only('destroy');
    }


    public function index(Request $request)
    {

        if ($request->ajax()) {

            $admins = TeamPlayer::query();

            if($request->id){
                $admins->where('team_id',$request->id);
            }

            $admins->get();

            return DataTables::of($admins)
                ->addColumn('action', function ($admin) {
                    return $this->crud_action_btns($admin->id, $this->permission);
                })
                ->editColumn('created_at', function ($admin) {
                    return date('Y/m/d', strtotime($admin->created_at));
                })
                ->editColumn('title', function ($admin) {
                    return $admin->title;
                })->editColumn('number', function ($admin) {
                    return $admin->number;
                })->editColumn('image', function ($admin) {
                    return $this->show_table_image($admin->image);

                })->editColumn('team', function ($admin) {
                    return $admin->team ? $admin->team->title : 'N/A';
                })
                ->escapeColumns([])
                ->make(true);
        }

        $permission = $this->permission;
        return view($this->view_path.'.index',compact('permission'));
    }


    public function create()
    {   

        $data['teams'] = Team::get();
        // $data['cities'] = City::get();
        // $data['countries'] = Country::get();

        return view($this->view_path.'parts.create',compact('data'));
    }

    public function store(TeamPlayerRequest $request)
    {
        try{

            $data = $request->validationData();
            
            DB::beginTransaction();

            $data['image'] = '';
            if($request->has('image')){
                $data['image'] = $this->uploadFiles('teamPlayers',$request->file('image'));
            }

            $row = TeamPlayer::create($data);

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

        $data['player'] = TeamPlayer::findOrfail($id);
        $data['teams'] = Team::get();
        
        return view($this->view_path.'parts.edit', compact('data'));

    }

    public function update(TeamPlayerRequest $request, $id)
    {

        try{

            $data = $request->validationData();

            if($request->has('image')){
                $data['image'] = $this->uploadFiles('teamPlayers',$request->file('image'),'yes');
            }
            DB::beginTransaction();

            $row=TeamPlayer::findOrFail($id);



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
        TeamPlayer::findOrFail($id)->delete();

        return $this->deleteResponse();
    }//end fun

}
