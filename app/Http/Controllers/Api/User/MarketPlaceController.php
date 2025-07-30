<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCategoryResource;
use App\Http\Resources\ProductResource;
use App\Http\Traits\Api_Trait;

use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\Notification;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\Upload_Files;

class MarketPlaceController extends Controller
{
    use Api_Trait,Upload_Files;

    //
    public function get_product_categories()
    {
        try{

            $lang= session_lang();
            $categories = CategoryProduct::get();
            $data[]=['id'=>null,'title'=>helperTrans('api.All'),'image'=>null];

            foreach($categories as $category){
                $data[]=[
                    'id'=>$category->id,
                    'name'=>$category->title,
                    'image'=>get_file($category->image)
                ];
            }

            return $this->returnData($data, [helperTrans('api.product categories Data')]);

        }catch(Exception $e){

        }
    }

    // *******************************************************************
    // *******************************************************************
    public function get_products($categoryId = null)
    {
        try{

            $products = Product::where('status','2')->get();

            if($categoryId){
                $products = $products->where('product_category_id', $categoryId);
            }
            return $this->returnData(ProductResource::collection($products), [helperTrans('api.products  Data')]);

        }catch(Exception $e){
            return $this->returnError([helperTrans('api.products  Data')]);
        }
    }
    // ***********************************************************
    // ***********************************************************
    public function get_all_products()
    {
        try{

            $products = Product::get();

            return $this->returnData(ProductResource::collection($products), [helperTrans('api.products  Data')]);

        }catch(Exception $e){

        }
    }

    // *********************************************************************
    // *********************************************************************
    public function store_product(Request $request)
    {
        try{

            $lang = $request->header('lang') ?? session_lang();
            $rules = [
                'product_category_id' => 'required|exists:product_categories,id',
                'image' => 'required|image',
                "title"=> 'required|string',
                "phone"=> 'required|numeric|min_digits:11',
                'price' => 'required|numeric',
                "address"=> 'nullable',
                "desc"=> 'nullable',
            ];
            $validator = Validator::make($request->all(),$rules);
            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1),403);
            }

            $user = userApi()->user(); 
            $data=$request->all();
            
            $data["title.$lang"] = $request->title;
            $data["desc.$lang"] = $request->desc;
            $data["address.$lang"] = $request->address;
            $data["user_id"] = $user->id;
            if ($request->image)
            $data["image"] = $this->uploadFiles('products', $request->file('image'), null);
            if ($request->video)
            $data["video"] = $this->uploadFiles('products/video', $request->file('video'), null);

            DB::beginTransaction();

            $row = Product::create($data);

            increment_User_points('add new product',dynamicSetting('upload_product'));

            $notificationData = [
                'action_id'=>$row->id ,
                'model_name'=>'product',
                'message'=>helperTrans('api.product Uploaded Successfuly,waiting for approve'),
            ];
            Notification::storeNotification($notificationData);

            DB::commit();

            return $this->returnData(ProductResource::make($row), [helperTrans('api.new product Data')]);


        }catch(Exception $e){
            DB::rollback();
            $message = 'Error Line: '. $e->getLine().' - ' . $e->getMessage();
            return $this->errorResponse($message);
        }

    }

    // *********************************************************************
    // *********************************************************************

    public function show_product($id){

        try{
            $validator = Validator::make(['id' => $id], [
                'id' =>'required|exists:products,id',
            ]);

            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }

            $product = Product::find($id);

            return $this->returnData(ProductResource::make($product), [helperTrans('api.single product  Data')]);

        }catch(Exception $e){
            DB::rollback();
            $message = 'Error Line: '. $e->getLine().' - ' . $e->getMessage();
            return $this->errorResponse($message);
        }
    }
    // *********************************************************************
    // *********************************************************************
    public function update_product(Request $request)
    {
        try{

            $lang = $request->header('lang') ?? session_lang();
            $rules = [
                'product_category_id' => 'required|exists:product_categories,id',
                'image' => 'nullable|image',
                'product_id' => 'required|exists:products,id',
                "title"=> 'required|string',
                "phone"=> 'required|numeric|min_digits:11',
                'price' => 'required|numeric',
                "address"=> 'nullable',
                "desc"=> 'nullable',
            ];
            $validator = Validator::make($request->all(),$rules);
            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1),403);
            }
            
            $user = userApi()->user();  
            $product = Product::find($request->product_id);

            if ($product->user_id != $user->id) {

                return $this->returnError([helperTrans('api.This product does not belong to you.')]);
            }

            $data=$request->all();

            $data["title.$lang"] = $request->title;
            $data["desc.$lang"] = $request->desc;
            $data["address.$lang"] = $request->address;
            
            unset($data['product_id']);
            if ($request->image)
            $data["image"] = $this->uploadFiles('products', $request->file('image'), null);
            if ($request->video)
            $data["video"] = $this->uploadFiles('products/video', $request->file('video'), null);

            DB::beginTransaction();
            $data["status"] = '1';
            $product->update($data);

            $notificationData = [
                'action_id'=>$request->product_id ,
                'model_name'=>'product',
                'message'=>helperTrans('api.product updated Successfuly,waiting for approve'),
            ];
            Notification::storeNotification($notificationData);

            DB::commit();

            return $this->returnData(ProductResource::make($product), [helperTrans('api.product updated successfuly')]);


        }catch(Exception $e){
            DB::rollback();
            $message = 'Error Line: '. $e->getLine().' - ' . $e->getMessage();
            return $this->errorResponse($message);
        }

    }
    
    // *********************************************************************
    // *********************************************************************
    public function deleteProduct($id){

        try{

            $validator = Validator::make(['id' => $id], [
                'id' =>'required|exists:products,id',
            ]);

            if ($validator->fails()) {
                return $this->returnErrorValidation(collect($validator->errors())->flatten(1), 403);
            }

            $user = userApi()->user();  

            DB::beginTransaction();

            $product = Product::find($id);

            if ($product->user_id != $user->id) {

                return $this->returnError([helperTrans('api.This product does not belong to you.')]);
            }

            $product->delete();

            increment_User_points('delete product',dynamicSetting('upload_product'),0);

            DB::commit();

            return $this->returnSuccessMessage([helperTrans("api.product deleted Successfully")]);

        }catch (\Exception $e) {

            DB::rollBack();
            return $this->returnExceptionError($e);
        }
    }
// ********************************************************************
// ********************************************************************
public function get_user_products(){
                
    try{
        $user = userApi()->user();

        $userProducts = Product::where('user_id' , $user->id)->get();
        
        return $this->returnData(ProductResource::collection($userProducts),[helperTrans('api.user products Data')]);

    }catch (\Exception $e) {
        DB::rollBack();
        return $this->returnExceptionError($e);
    }
}





}