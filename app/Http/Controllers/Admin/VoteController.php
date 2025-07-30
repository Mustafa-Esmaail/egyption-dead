<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Http\Requests\Admin\VoteRequest;
use App\Http\Traits\Upload_Files;

use App\Models\Vote;

class VoteController extends Controller
{
    //
    use ResponseTrait,Upload_Files;

    private $view_path = 'Admin.CRUDS.vote.';
    protected $permission = 'votes';

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
            $votes = Vote::query()->latest();
            return DataTables::of($votes)
                ->addColumn('action', function ($vote) {
                    return $this->crud_action_btns($vote->id, $this->permission);
                })
                ->editColumn('title', function ($vote) {
                    return $vote->title;
                })->editColumn('desc', function ($vote) {
                    return $vote->desc;
                })
                
                ->addColumn('choices', function ($vote) {
                    return '
                              <a class="btn btn-light border bg-secondary" data-fancybox="" href="'.route('vote-choice.index').'?id='.$vote->id.'">
                               view Choices
                            </a>';
                })
    
                ->escapeColumns([])
                ->make(true);
        }
    
        $permission = $this->permission;
        return view($this->view_path . '.index', compact('permission'));
    }
    


    public function create()
    {

        return view($this->view_path.'parts.create');
    }

    public function store(VoteRequest $request)
    {

        try{

            $data = $request->validationData();

            DB::beginTransaction();

            // if ($request->image)
            // $data["image"] = $this->uploadFiles('team', $request->file('image'), null);

            $row = Vote::create($data);

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

        $row=Vote::findOrFail($id);
       
        return view($this->view_path.'parts.edit', compact('row'));

    }

    public function update(VoteRequest $request, $id)
    {

        try{

            $data = $request->validationData();


            DB::beginTransaction();

            $row=Vote::findOrFail($id);

            // if ($request->image)
            // $data["image"] = $this->uploadFiles('team', $request->file('image'), 'yes');


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

        Vote::findOrFail($id)->delete();

        return $this->deleteResponse();
    }//end fun

}
