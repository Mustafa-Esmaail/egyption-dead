<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Http\Requests\Admin\AcademyRequest;
use App\Http\Traits\Upload_Files;

use App\Models\Academy;
use App\Models\City;
use App\Models\Country;

class AcademyController extends Controller
{
    //
    use ResponseTrait,Upload_Files;

    private $view_path = 'Admin.CRUDS.academy.';
    protected $permission = 'academy';

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
            $admins = Academy::query()->latest();
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
                ->editColumn('city', function ($admin) {
                    return $admin->city ? $admin->city->title : 'N/A';
                })
                ->editColumn('country', function ($admin) {
                    return $admin->country ? $admin->country->title : 'N/A';
                })
                ->escapeColumns([])
                ->make(true);
        }

        $permission = $this->permission;
        return view($this->view_path . '.index', compact('permission'));
    }



    public function create()
    {

        $cities = City::all();
        $countries = Country::all();

        return view($this->view_path.'parts.create', compact('cities', 'countries'));
    }

    public function store(AcademyRequest $request)
    {

        try{

            $data = $request->validationData();

            DB::beginTransaction();

            if ($request->image)
            $data["image"] = $this->uploadFiles('academy', $request->file('image'), null);

            $row = Academy::create($data);

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

        $row=Academy::findOrFail($id);

        $cities = City::all();
        $countries = Country::all();

        return view($this->view_path.'parts.edit', compact('row', 'cities', 'countries'));

    }

    public function update(AcademyRequest $request, $id)
    {

        try{

            $data = $request->validationData();


            DB::beginTransaction();

            $row=Academy::findOrFail($id);

            if ($request->image)
                $data["image"] = $this->uploadFiles('academy', $request->file('image'), 'yes');


            $row->update($data);

            // dd($row);
            DB::commit();

            return $this->updateResponse();

        }catch(\Exception $e){
            DB::rollback();
            $message = 'Error Line: '. $e->getLine().' - ' . $e->getMessage();
            return $this->errorResponse($message);
        }


    }


    public function destroy($id)
    {

        Academy::findOrFail($id)->delete();

        return $this->deleteResponse();
    }//end fun

}
