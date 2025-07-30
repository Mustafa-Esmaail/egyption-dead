<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CityRequest;
use App\Http\Traits\ResponseTrait;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class UserCoachController extends Controller
{
    //
    use  ResponseTrait;
    private $view_path = 'Admin.CRUDS.cities';
    protected $permission = 'userCoach';

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
            $admins = City::get();
            
            return DataTables::of($admins)
        
                ->editColumn('title', function ($admin) {
                   return $admin->title??'N/A';
                })
                ->addColumn('country', function ($admin) {
                    return $admin->country ? $admin->country->title : 'N/A';
                })

                ->editColumn('created_at', function ($admin) {
                    return date('Y/m/d', strtotime($admin->created_at));
                })
                ->escapeColumns([])
                ->make(true);


        }

        $permission = $this->permission;
        return view($this->view_path.'.index',compact('permission'));
    }


    public function create()
    {
        // $data['countries'] = Country::get();

        // return view($this->view_path.'.parts.create',compact('data'));
    }

    public function store(Request $request)
    {
        // try{

        //     $rules = [
        //         'country_id' =>'required|exists:countries,id',
        //     ];
        //     foreach (languages() as $language) {
        //         $rules["title.$language->abbreviation"] = 'required';
        //     }


        //     $request->validate($rules);

        //     $data = $request->all();
            
        //     DB::beginTransaction();
    
        //     $row = City::create($data);

        //     DB::commit();

        //     return $this->addResponse();

        // }catch(Exception $e){
        //     DB::rollback();
        //     $message = 'Error Line: '. $e->getLine().' - ' . $e->getMessage();
        //     return $this->errorResponse($message);
        // }
    }





    public function edit($id)
    {

        // $data['row']=City::findOrFail($id);

        // $data['countries'] = Country::get();

        // return view($this->view_path.'.parts.edit', compact('data'));

    }

    public function update(Request $request, $id )
    {

        // try{

        //     $rules = [
        //         'country_id' =>'required|exists:countries,id',
        //     ];
        //     foreach (languages() as $language) {
        //         $rules["title.$language->abbreviation"] = 'required';
        //     }


        //     $request->validate($rules);

        //     $data = $request->all();
            
        //     DB::beginTransaction();
    
        //    $row = City::findOrfail($id);

        //     $row->update($data);
        //     DB::commit();

        //     return $this->updateResponse();

        // }catch(Exception $e){
        //     DB::rollback();
        //     $message = 'Error Line: '. $e->getLine().' - ' . $e->getMessage();
        //     return $this->errorResponse($message);
        // }



    }


    public function destroy($id)
    {
        // $row=City::findOrFail($id);
        // $row->delete();

        // return $this->deleteResponse();
    }//end fun

    public function getCities($countryId)
    {
 
        // $cities = City::where('country_id', $countryId)->get();

        // $language=app()->getLocale();

        // return response()->json(['cities'=>$cities,'language'=>$language]);
    }
    

}
