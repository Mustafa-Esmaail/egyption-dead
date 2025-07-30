<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Http\Requests\Admin\VoteChoicesRequest;

use App\Models\VoteChoice;
use App\Models\Vote;
use App\Http\Traits\Upload_Files;

class VoteChoiceController extends Controller
{
    //
    use ResponseTrait,Upload_Files;

    private $view_path = 'Admin.CRUDS.vote.choices.';

    protected $permission = 'voteChoices';

    public function __construct()
    {
        $this->middleware("permission:show $this->permission")->only('index');
        $this->middleware("permission:add $this->permission")->only(['create', 'store']);
        $this->middleware("permission:edit $this->permission")->only(['edit', 'update']);
        $this->middleware("permission:delete $this->permission")->only('destroy');
    }


    public function index(Request $request)
    {

        // dd($request->ajax(),$request->query('id'));
        // dd();
        if ($request->ajax()) {

            $admins = VoteChoice::query();

            
            if($request->query('id')){  


                $id = $request->query('id');
                $admins->where('vote_id',$id);
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
                })
                ->addColumn('rate', function ($admin) {
                    return '';
                })->editColumn('image', function ($admin) {

                    return $this->show_table_image($admin->image);
                })->editColumn('vote', function ($admin) {
                    return $admin->vote ? $admin->vote->title : 'N/A';
                })
                ->escapeColumns([])
                ->make(true);
        }

        $permission = $this->permission;
        return view($this->view_path.'.index',compact('permission'));
    }


    public function create()
    {   

        $data['votes'] = Vote::get();
        // $data['cities'] = City::get();
        // $data['countries'] = Country::get();

        return view($this->view_path.'parts.create',compact('data'));
    }

    public function store(VoteChoicesRequest $request)
    {
        try{

            $data = $request->validationData();
            
            DB::beginTransaction();

            $data['image'] = '';
            if($request->has('image')){
                $data['image'] = $this->uploadFiles('voteChoices',$request->file('image'));
            }

            $row = VoteChoice::create($data);

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
        $data['choice'] = VoteChoice::findOrfail($id);
        $data['votes'] = Vote::get();
        
        return view($this->view_path.'parts.edit', compact('data'));
    }

    public function update(VoteChoicesRequest $request, $id)
    {

        try{

            $data = $request->validationData();

            if($request->has('image')){
                $data['image'] = $this->uploadFiles('voteChoices',$request->file('image'),'yes');
            }
            DB::beginTransaction();

            $row=VoteChoice::findOrFail($id);

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
        VoteChoice::findOrFail($id)->delete();

        return $this->deleteResponse();
    }//end fun

}
