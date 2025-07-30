<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Http\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    //
    use ResponseTrait;


    protected $permission = 'roles';

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
            $admins = Role::query()->latest();
            return DataTables::of($admins)
                ->addColumn('action', function ($admin) {

                  return  $this->crud_action_btns($admin->id,$this->permission);

                })

                ->editColumn('created_at', function ($admin) {
                    return date('Y/m/d', strtotime($admin->created_at));
                })
                ->escapeColumns([])
                ->make(true);


        }

        $permission = $this->permission;
        return view('Admin.CRUDS.roles.index',compact('permission'));
    }


    public function create()
    {
        $permission=Permission::latest()->get();

        return view('Admin.CRUDS.roles.parts.create',compact('permission'));
    }

    public function store(RoleRequest $request)
    {
        $data = $request->validationData();

        unset($data['permission']);


        $row = Role::create($data);

        $role=Role::findOrFail($row->id);

        $permissionIds = $request->input('permission');

        if ($permissionIds === null) {
            // If $permissionIds is null, remove all permissions from the role
            $role->syncPermissions([]);
        } else {
            $permissions = Permission::whereIn('id', $permissionIds)->get();

            if ($permissions->count() === count($permissionIds)) {
                // Handle the case where some permissions are not found
                $role->syncPermissions($permissions);
            } else {

//                return $this->addErrorResponse('Some permissions not found');
            }
        }


        return $this->addResponse();
    }



    public function show($id)
    {


        //
    }


    public function edit($id)
    {

        $role=Role::findOrFail($id);
        $permission=Permission::orderBy('id','desc')->get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$role->id)
            ->get();

        return view('Admin.CRUDS.roles.parts.edit', compact('role','permission','rolePermissions'));

    }

    public function update(RoleRequest $request, $id)
    {

        $data = $request->validationData();

        unset($data['permission']);
        $role=Role::findOrFail($id);



        $role->update($data);


        $role=Role::findOrFail($id);

        $permissionIds = $request->input('permission');

        if (empty($permissionIds)) {
            // If $permissionIds is empty, remove all permissions from the role
            $role->syncPermissions([]);
        } else {
            // If $permissionIds is not empty, proceed with updating permissions
            $permissions = Permission::whereIn('id', $permissionIds)->get();

            if ($permissions !== null && count($permissions) == count($permissionIds)) {
                // Handle the case where some permissions are not found
                $role->syncPermissions($permissions);
            }
        }



        return $this->updateResponse();


    }


    public function destroy($id)
    {
        Role::findOrFail($id)->delete();

        return $this->deleteResponse();
    }//end fun

}
