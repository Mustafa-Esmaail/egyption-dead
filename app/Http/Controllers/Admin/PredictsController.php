<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Http\Requests\Admin\PredictsRequest;
use App\Http\Traits\Upload_Files;

use App\Models\Predict;

class PredictsController extends Controller
{
    //
    use ResponseTrait,Upload_Files;

    private $view_path = 'Admin.CRUDS.predicts.';
    protected $permission = 'predicts';

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
            $admins = Predict::query()->latest();
            return DataTables::of($admins)
                ->addColumn('action', function ($admin) {
                    return $this->crud_action_btns($admin->id, $this->permission);
                })
                ->editColumn('title', function ($admin) {
                    return $admin->title;
                })->editColumn('desc', function ($admin) {
                    return $admin->desc;
                })->editColumn('image', function ($admin) {
                    return $this->show_table_image($admin->image);
                })
                ->escapeColumns([])
                ->make(true);
        }
    
        $permission = $this->permission;
        return view($this->view_path . 'index', compact('permission'));
    }
    


    public function create()
    {

        return view($this->view_path.'parts.create');
    }

    public function store(PredictsRequest $request)
    {

        try{

            $data = $request->validationData();

            DB::beginTransaction();

            if ($request->image)
            $data["image"] = $this->uploadFiles('predict', $request->file('image'), null);

            $row = Predict::create($data);

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

        $row=Predict::findOrFail($id);
       
        return view($this->view_path.'parts.edit', compact('row'));

    }

    public function update(PredictsRequest $request, $id)
    {

        try{

            $data = $request->validationData();


            DB::beginTransaction();

            $row=Predict::findOrFail($id);

            if ($request->image)
            $data["image"] = $this->uploadFiles('predict', $request->file('image'), 'yes');


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

        Predict::findOrFail($id)->delete();

        return $this->deleteResponse();
    }//end fun

}
