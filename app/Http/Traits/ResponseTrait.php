<?php
namespace App\Http\Traits;

trait ResponseTrait {

    public function addResponse( $message = 'Data added successfully' ) {
        return [
            'message' => helperTrans( 'admin.'.$message ),
            'code'  => 200,
        ];
    }

    public function updateResponse( $message = 'The data has been modified successfully' ) {
        return [
            'message' => helperTrans( 'admin.'.$message ),
            'code'  => 200,
        ];
    }

    public function deleteResponse( $message = 'The data has been deleted successfully' ) {
        return [
            'message' => helperTrans( 'admin.'.$message ),
            'code'  => 200,
        ];
    }

    public function successResponse( $message = 'operation accomplished successfully' ) {
        return response()->json( [
            'status' => true,
            'message' => helperTrans( 'admin.'.$message ),
        ] );
    }
// ***********************************************
// ***********************************************
    public function errorResponse( $message = 'An error occurred while performing the operation') {
        return response()->json( [
            'status' => 500,
            'message' => $message,
        ] );

    }
}
