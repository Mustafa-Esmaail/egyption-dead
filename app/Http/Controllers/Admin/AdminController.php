<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminRequest;
use App\Http\Traits\ResponseTrait;
use App\Http\Traits\Upload_Files;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class AdminController extends Controller
{
    use Upload_Files, ResponseTrait;

    public $pageUrl = 'admins';

    protected $permission = 'admins';

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

            // 
            $admins = Admin::where('id', '!=', Auth::guard('admin')->user()->id)->get();

            return Datatables::of($admins)
                ->addColumn('action', function ($admin) {

                    return $this->crud_action_btns($admin->id,$this->permission);
                })
                ->editColumn('image', function ($admin) {

                    return $this->show_table_image($admin->image);
                })
                ->editColumn('is_active', function ($row) {
                    $active = '';
                    $operation = '';

                    $operation = '';


                    if ($row->is_active == 1)
                        $active = 'checked';

                    return '<div class="form-check form-switch">
                               <input ' . $operation . '  class="form-check-input activeBtn" data-id="' . $row->id . ' " type="checkbox" role="switch" id="flexSwitchCheckChecked" ' . $active . '  >
                            </div>';
                })
                ->editColumn('created_at', function ($admin) {
                    return date('Y/m/d', strtotime($admin->created_at));
                })->editColumn('name', function ($admin) {
                    return $admin->first_name . ' ' . $admin->last_name;
                })
                ->escapeColumns([])
                ->make(true);


        }
        $permission = $this->permission;
        return view('Admin.CRUDS.admin.index',compact('permission'));
    }


    public function create()
    {

        $data['roles'] = Role::where('guard_name','admin')->get();
        $data['pageUrl'] = $this->pageUrl;

        return view('Admin.CRUDS.admin.parts.create',compact('data'));
    }

    public function store(AdminRequest $request)
    {

     
       
        $data = $request->validationData();

        if ($request->image)
            $data["image"] = $this->uploadFiles('admins', $request->file('image'), null);

        $data['password'] = bcrypt($request->password);

        unset($data['roles']);


        $admin = Admin::create($data);

        $admin->roles()->sync($request->input('roles'));


        return $this->addResponse();

    }


    public function show(Admin $admin)
    {

        $html = view('Admin.CRUDS.admin.parts.show', compact('admin'))->render();
        return response()->json([
            'code' => 200,
            'html' => $html,
        ]);

        //
    }


    public function edit($id)
    {

       
        $admin = Admin::with('roles')->findOrFail($id);

        // $data['admin_roles_ides'] = DB::table("model_has_roles")->where("model_has_roles.model_id", $admin->id)
        //     ->where('model_type','App\Models\Admin')->pluck('role_id');
        // dd($data['admin_roles_ides']);
        $data['roles'] = Role::where('guard_name','admin')->get();

        // dd($admin->roles);
        return view('Admin.CRUDS.admin.parts.edit', compact('data','admin'));

    } 
    
    public function profile()
    {

        $user = Auth::guard('admin')->user();
        $admin = Admin::with('roles')->findOrFail($user->id);

        // $data['admin_roles_ides'] = DB::table("model_has_roles")->where("model_has_roles.model_id", $admin->id)
        //     ->where('model_type','App\Models\Admin')->pluck('role_id');
        // dd($data['admin_roles_ides']);
        $data['roles'] = Role::where('guard_name','admin')->get();

        // dd($admin->roles);
        return view('Admin.CRUDS.admin.parts.profile', compact('data','admin'));

    }

    public function updateProfile(AdminRequest $request){

        // dd($request);
        $admin=Admin::findOrFail($request->id);
   
        $data = $request->validationData();
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        } else {
            $data['password'] = $admin->password;
        }
        if ($request->image) {
            $data["image"] = $this->uploadFiles('admins', $request->file('image'), 'yes');

        }

        unset($data['roles']);


        $admin->update($data);

        // $admin=Admin::findOrFail($id);

        $admin->roles()->sync($request->input('roles'));

        return redirect()->back();

    }


    public function update(AdminRequest $request, $id )
    {

        $admin=Admin::findOrFail($id);
        $data = $request->validationData();
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        } else {
            $data['password'] = $admin->password;
        }
        if ($request->image) {
            $data["image"] = $this->uploadFiles('admins', $request->file('image'), 'yes');

        }

        unset($data['roles']);


        $admin->update($data);

        $admin=Admin::findOrFail($id);

        $admin->roles()->sync($request->input('roles'));

        return $this->updateResponse();

    }


    public function destroy($id)
    {
        $row = Admin::findOrFail($id);

        if (file_exists($row->image)) {
            unlink($row->image);
        }

        $row->delete();

        return $this->deleteResponse();
    }//end fun


    public function activate(Request $request)
    {

        $admin = Admin::findOrFail($request->id);
        if ($admin->is_active == true) {
            $admin->is_active = 0;
            $admin->save();
        } else {
            $admin->is_active = 1;
            $admin->save();
        }

        return $this->successResponse();
    }//end fun

}//end class
