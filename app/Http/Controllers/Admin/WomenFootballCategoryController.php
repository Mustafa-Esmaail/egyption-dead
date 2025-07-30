<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

use App\Models\WomenFootballCategory;

class WomenFootballCategoryController extends Controller
{
    //
    use ResponseTrait;

    private $view_path = 'Admin.CRUDS.womenFootballCategory.';
    protected $permission = 'womenFootballCategory';

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
            $admins = WomenFootballCategory::query()->latest();
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

    public function store(Request $request)
    {
        try{

            foreach (languages() as $language) {
                $rules["title.$language->abbreviation"] = 'required';
            }

            $data = $request->validate($rules);

            DB::beginTransaction();

            $row = WomenFootballCategory::create($data);

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

        $row=WomenFootballCategory::findOrFail($id);
       
        return view($this->view_path.'parts.edit', compact('row'));

    }

    public function update(Request $request, $id)
    {

        try{

            foreach (languages() as $language) {
                $rules["title.$language->abbreviation"] = 'required';
            }

            $data = $request->validate($rules);

            DB::beginTransaction();

            $row=WomenFootballCategory::findOrFail($id);



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
        WomenFootballCategory::findOrFail($id)->delete();

        return $this->deleteResponse();
    }//end fun

}
