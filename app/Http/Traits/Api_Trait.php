<?php
namespace App\Http\Traits;

trait Api_Trait
{

    public function getCurrentLang()
    {
        return app()->getLocale();
    }

    public function returnError($message,$status = 400)
    {
        return response()->json([
            'data' => null,
            'message' => $message,
            'status' => $status,
        ],200);
    }

    public function returnErrorValidation($message,$status = 403)
    {
        return response()->json([
            'data' => null,
            'message' => $message,
            'status' => $status,
        ],200);
    }

    public function returnErrorNotFound($message,$status = 404)
    {
        return response()->json([
            'data' => null,
            'message' => $message,
            'status' => $status,
        ],200);
    }



    public function returnData( $data, $message,$status=200)
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $status,
        ],200);
    }


    public function returnSuccessMessage($message,$status=200){
        return response()->json([
            'data' => null,
            'message' => $message,
            'status' => $status,
        ],200);
    }

    public function returnExceptionError($e){

        return response()->json([
            'data' => null,
            'message' => 'error line: ' . $e->getLine() . ' - ' . $e->getMessage(),
            'status' => 500,
        ],200);
    }

    // ***********************************************
    // ***********************************************
    public function paginationData($collection){

        return [
            'current_page' => $collection->currentPage(),
            'last_page' => $collection->lastPage(),
            'per_page' => $collection->perPage(),
            'total' => $collection->total(),
            'next_page_url' => $collection->nextPageUrl(),
            'prev_page_url' => $collection->previousPageUrl(),
        ];
    }

}
