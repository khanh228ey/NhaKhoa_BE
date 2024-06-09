<?php
namespace App\Commons\Responses;


class JsonResponse{
    public static function handle($status, $message, $data, $code) 
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    Public static function error($status,$message,$code){
        return response()->json([
            'status' => $status,
            'message' => $message,
        ],$code);
    }
}