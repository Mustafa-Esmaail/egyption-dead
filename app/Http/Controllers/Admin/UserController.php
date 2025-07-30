<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Http\Traits\ResponseTrait;
use App\Http\Traits\Upload_Files;
use App\Models\City;
use App\Models\Club;
use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    //
    use Upload_Files, ResponseTrait;

    protected $permission = 'users';
    protected $view_path  = 'Admin.CRUDS.user';

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
            $admins = User::with('country', 'city', 'club')->latest()->get();
            return DataTables::of($admins)
                ->addColumn('action', function ($admin) {

                    $edit   = '';
                    $delete = '';
                    $chat   = '';

                    if (! auth('admin')->user()->can('edit users')) {
                        $edit = 'hidden';
                    }

                    if (! auth('admin')->user()->can('delete users')) {
                        $delete = 'hidden';
                    }

                    // if (!auth('admin')->user()->can('chat users'))
                    //     $chat = 'hidden';

                    return '
                            <button ' . $edit . '  class="editBtn btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                                    data-id="' . $admin->id . '"
                          	<span class="svg-icon svg-icon-3">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
									<path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="black" />
									<path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="black" />
								</svg>
							</span>
                            </button>
                            <a ' . $chat . ' href="' . route('admin.chat.show', $admin->id) . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                <span class="svg-icon svg-icon-3">
            <!-- Chat SVG Icon (e.g., speech bubble) -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" height="24" width="24" viewBox="0 0 24 24">
                <path fill="black" d="M20 2H4C2.897 2 2 2.897 2 4v14c0 1.103 0.897 2 2 2h14l4 4V4c0-1.103-0.897-2-2-2z"/>
            </svg>
        </span>
                            </a>
                            <button ' . $delete . '  class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm delete"
                                    data-id="' . $admin->id . '">
                            <span class="svg-icon svg-icon-3">
							   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
							   	<path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="black" />
							   	<path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="black" />
							   	<path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="black" />
							   </svg>
							</span>
                            </button>
                       ';

                })

                ->editColumn('is_active', function ($row) {
                    $active    = '';
                    $operation = '';

                    $operation = '';

                    if ($row->is_active == 1) {
                        $active = 'checked';
                    }

                    return '<div class="form-check form-switch">
                               <input ' . $operation . '  class="form-check-input activeBtn" data-id="' . $row->id . ' " type="checkbox" role="switch" id="flexSwitchCheckChecked" ' . $active . '  >
                            </div>';
                })
                ->editColumn('image', function ($admin) {
                    return '
                              <a data-fancybox="" href="' . get_file($admin->image) . '">
                                <img height="60px" src="' . get_file($admin->image) . '">
                            </a>
                             ';
                })
                ->editColumn('created_at', function ($admin) {
                    return date('Y/m/d', strtotime($admin->created_at));
                })

                ->editColumn('name', function ($admin) {
                    return $admin->first_name . ' ' . $admin->last_name;
                })->editColumn('country', function ($admin) {
                return $admin->country->title ?? helperTrans('admin.N/A');
            })->editColumn('city', function ($admin) {
                return $admin->city->title ?? helperTrans('admin.N/A');
            })->editColumn('club', function ($admin) {
                return $admin->club->title ?? helperTrans('admin.N/A');
            })->editColumn('email', function ($admin) {
                return $admin->email ?? helperTrans('admin.N/A');
            })
                ->escapeColumns([])
                ->make(true);

        }
        $permission = $this->permission;

        return view($this->view_path . '.index', compact('permission'));
    }

    public function create()
    {
        $data['countries'] = Country::with('cities')->get();
        $data['clubs']     = Club::get();
        return view($this->view_path . '.parts.create', compact('data'));
    }

    public function store(UserRequest $request)
    {

        $data = $request->validationData();
        if ($request->image) {
            $data["image"] = $this->uploadFiles('users', $request->file('image'), null);
        }

        $data['password'] = bcrypt($request->password);

        User::create($data);

        return $this->addResponse();

    }

    public function edit($id)
    {

        $user              = User::with('city')->findOrfail($id);
        $data['countries'] = Country::with('cities')->get();
        $data['cities']    = City::get();
        $data['clubs']     = Club::get();

        return view($this->view_path . '.parts.edit', compact('user', 'data'));

    }

    public function update(UserRequest $request, $id)
    {

        $row  = User::findOrFail($id);
        $data = $request->validationData();
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        } else {
            $data['password'] = $row->password;
        }
        if ($request->image) {
            $data["image"] = $this->uploadFiles('admins', $request->file('image'), 'yes');

        }

        $row->update($data);

        return $this->updateResponse();

    }

    public function destroy($id)
    {
        $row = User::findOrFail($id);

        if (file_exists($row->image)) {
            unlink($row->image);
        }

        $row->delete();

        return $this->deleteResponse();
    } //end fun

    public function activate(Request $request)
    {

        $user = User::findOrFail($request->id);
        if ($user->is_active == true) {
            $user->is_active = 0;
            $user->save();
        } else {
            $user->is_active = 1;
            $user->save();
        }

        return $this->successResponse();
    } //end fun

}
