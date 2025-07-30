<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermissionRequest;
use App\Http\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class PermissionController extends Controller
{
    use ResponseTrait;

    protected $permission = 'permission';

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
            $admins = Permission::query()->latest();
            return DataTables::of($admins)
                ->addColumn('action', function ($admin) {
                    return $this->crud_action_btns($admin->id, $this->permission);
                })
                ->editColumn('created_at', function ($admin) {
                    return date('Y/m/d', strtotime($admin->created_at));
                })
                ->escapeColumns([])
                ->make(true);
        }

        $permission = $this->permission;
        return view('Admin.CRUDS.permission.index', compact('permission'));
    }

    public function create()
    {
        return view('Admin.CRUDS.permission.parts.create');
    }

    public function store(PermissionRequest $request)
    {
        $data = $request->validationData();
        $data['guard_name'] = 'admin';
        Permission::create($data);
        return $this->addResponse();
    }

    public function edit($id)
    {
        $row = Permission::findOrFail($id);
        return view('Admin.CRUDS.permission.parts.edit', compact('row'));
    }

    public function update(PermissionRequest $request, $id)
    {
        $data = $request->validationData();
        $row = Permission::findOrFail($id);
        $row->update($data);
        return $this->updateResponse();
    }

    public function destroy($id)
    {
        $row = Permission::findOrFail($id);
        $row->delete();
        return $this->deleteResponse();
    }
}
