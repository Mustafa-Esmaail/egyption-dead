<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LanguageRequest;
use App\Http\Traits\ResponseTrait;
use App\Models\Language;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LanguageController extends Controller
{
    //
    use  ResponseTrait;

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $admins = Language::query()->latest();
            return DataTables::of($admins)
                ->addColumn('action', function ($admin) {

                    $edit = '';
                    $delete = '';


                    return '
                            <button ' . $edit . '  class="editBtn btn rounded-pill btn-primary waves-effect waves-light"
                                    data-id="' . $admin->id . '"
                            <span class="svg-icon svg-icon-3">
                                <span class="svg-icon svg-icon-3">
                                    <i class="las la-edit"></i>
                                </span>
                            </span>
                            </button>
                            <button ' . $delete . '  class="btn rounded-pill btn-danger waves-effect waves-light delete"
                                    data-id="' . $admin->id . '">
                            <span class="svg-icon svg-icon-3">
                                <span class="svg-icon svg-icon-3">
                                    <i class="las la-trash-alt"></i>
                                </span>
                            </span>
                            </button>
                       ';


                })

                ->editColumn('status', function ($row) {
                    $active = '';
                    $operation = '';

                    $operation = '';


                    if ($row->status == 1)
                        $active = 'checked';

                    return '<div class="form-check form-switch">
                               <input ' . $operation . '  class="form-check-input activeBtn" data-id="' . $row->id . ' " type="checkbox" role="switch" id="flexSwitchCheckChecked" ' . $active . '  >
                            </div>';
                })


                ->editColumn('created_at', function ($admin) {
                    return date('Y/m/d', strtotime($admin->created_at));
                })
                ->escapeColumns([])
                ->make(true);


        }
        return view('Admin.CRUDS.languages.index');
    }


    public function create()
    {


        return view('Admin.CRUDS.languages.parts.create');
    }

    public function store(LanguageRequest $request)
    {
        $data = $request->validationData();
        Language::create($data);
        return $this->addResponse();

    }


    public function show($id)
    {


        //
    }


    public function edit($id )
    {


        $row=Language::findOrFail($id);


        return view('Admin.CRUDS.languages.parts.edit', compact('row'));

    }

    public function update(LanguageRequest $request, $id )
    {

        $row=Language::findOrFail($id);
        $data = $request->validationData();
        $row->update($data);

        return $this->updateResponse();


    }


    public function destroy($id)
    {
        $row = Language::findOrFail($id);

        $row->delete();

        return $this->deleteResponse();
    }//end fun

    public function activate(Request $request)
    {
        $row = Language::findOrFail($request->id);
        if ($row->status == true) {
            $row->status = 0;
            $row->save();
        } else {
            $row->status = 1;
            $row->save();
        }

        return $this->successResponse();
    }//end fun

}
