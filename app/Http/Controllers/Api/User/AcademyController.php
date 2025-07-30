<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\AcademyResource;
use App\Http\Traits\Api_Trait;

use App\Models\Academy;
use App\Models\UserAcademy;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AcademyController extends Controller
{
    use Api_Trait;

    //
    public function get_academies(Request $request)
    {
        try{

            $types = Academy::with('city','country')->filter($request->all())->get();

            return $this->returnData(AcademyResource::collection($types), [helperTrans('api.academy Data')]);

        }catch(Exception $e){

        }
    }

    // *********************************************************************
    // *********************************************************************
    public function academy_details($id)
    {
        try{

                $validator = Validator::make(['academy_id' => $id], [
                    'academy_id' =>'required|exists:academies,id',
                ]);

                if ($validator->fails()) {
                    return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
                }

                $academy = Academy::find($id);

                return $this->returnData(AcademyResource::make($academy), [helperTrans('api.academy Data')]);

        }catch(Exception $e){

        }
    }

  // ************************************************************************
  // ************************************************************************
    public function toggle_subscrib_in_academy($academyId){

        try{

            $validator = Validator::make(['academy_id' => $academyId], [
                'academy_id' =>'required|exists:academies,id',
            ]);

            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }

            DB::beginTransaction();

            $message = UserAcademy::toggleAction($academyId,2) == 1  ? 'subscribed' : 'Unsubscribed';


            DB::commit();

            return $this->returnSuccessMessage(helperTrans("api.$message Successfully")); // Single resource instance

        }catch (\Exception $e) {

            DB::rollBack();
            return $this->returnExceptionError($e);
        }
    }
// ***********************************************************************
// ***********************************************************************
    public function toggle_liked_academy($academyId){

        try{

            $validator = Validator::make(['academy_id' => $academyId], [
                'academy_id' =>'required|exists:academies,id',
            ]);

            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }

            // $user = userApi()->user();

            DB::beginTransaction();

            $message = UserAcademy::toggleAction($academyId,1) == 1 ? 'Liked' : 'Unliked';

            DB::commit();

            return $this->returnSuccessMessage(helperTrans("api.$message Successfully")); // Single resource instance

        }catch (\Exception $e) {

            DB::rollBack();
            return $this->returnExceptionError($e);

        }
    }

    // ************************************************
    // ************************************************
    // public function provider_rate(Request $request)
    // {

    //     try{


    //         $validator = Validator::make($request->all(), [
    //             'provider_id' => 'required|exists:providers,id',
    //             'rate' => 'required|numeric|min:1|max:10',
    //         ]);

    //         if ($validator->fails()) {
    //             return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
    //         }

    //         DB::beginTransaction();
    //         $providerId = $request->provider_id;
    //         $area = ProviderRate::create($request->all()); // Single model instance, not a collection

    //         $providerRates = ProviderRate::where('provider_id', $providerId)
    //                         ->selectRaw('COUNT(*) as rate_count, AVG(rate) as rate_avg')
    //                         ->first();

    //         $provider = Provider::findOrFail($providerId);

    //         $provider->update([
    //             'avg_rate' => $providerRates->rate_avg,
    //             'rate_count' => $providerRates->rate_count,
    //         ]);
    //         DB::commit();
    //         return $this->returnSuccessMessage(helperTrans('api.Rate Added Successfully')); // Single resource instance

    //     }catch (\Exception $e) {

    //         DB::rollBack();
    //         return $this->returnError($e->getLine(), $e->getMessage());
    //     }
    // }



}
