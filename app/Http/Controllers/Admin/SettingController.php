<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Upload_Files;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use Upload_Files;


    public function __construct()
    {
        $this->middleware('permission:show settings')->only('index');
        $this->middleware('permission:edit settings')->only([ 'update']);
    }


    public function index()
    {


        $settings = Setting::firstOrNew();
        return view('Admin.CRUDS.settings.index', [
            'settings' => $settings,
        ]);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'app_name'=>'nullable',
            'logo_header'=>'nullable|image',
            'fave_icon'=>'nullable|image',
            'facebook'=>'nullable',
            'twitter'=>'nullable',
            'youTube'=>'nullable',
            'instagram'=>'nullable',
            'tiktok'=>'nullable',
            'whatsApp'=>'nullable',
            'privacy_police'=>'nullable',
        ],
        [
        ]
        );

        if ($request->logo_header)
        $data['logo_header'] =  $this->uploadFiles('settings',$request->file('logo_header'),null );
        if ($request->fave_icon)
            $data['fave_icon'] =  $this->uploadFiles('settings',$request->file('fave_icon'),null );

        Setting::firstOrNew()->update($data);

        dynamicSetting('comment_points',$request->comment_points);
        dynamicSetting('like_points',$request->like_points);
        dynamicSetting('signUp_points',$request->signUp_points);
        dynamicSetting('vote_points',$request->vote_points);
        dynamicSetting('subscribe_points',$request->subscribe_points);
        dynamicSetting('add_plan',$request->add_plan);
        dynamicSetting('favourite_team',$request->favourite_team);
        dynamicSetting('favourite_player',$request->favourite_player);
        dynamicSetting('upload_talant',$request->upload_talant);
        dynamicSetting('add_rate',$request->add_rate);
        dynamicSetting('upload_product',$request->upload_product);
        dynamicSetting('var_points',$request->var_points);

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!'
            ]);

    }


}
