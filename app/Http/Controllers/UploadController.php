<?php

namespace App\Http\Controllers;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UploadController extends Controller
{

    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return JsonResponse::error(400, $validator->messages(), 400);
        }
        try {
            $image = $request->file('image');
            $uploadedFileUrl = Cloudinary::upload($image->getRealPath(), [
                'transformation' => [
                    'width' => 800,
                    'height' => 800,
                    'crop' => 'fit'
                ]
            ])->getSecurePath();

            return JsonResponse::handle(200, ConstantsMessage::SUCCESS,['url' => $uploadedFileUrl], 200);
        } catch (\Exception $e) {
            return JsonResponse::error(500, ConstantsMessage::ERROR, 500);
            
        }
    }
    //Upload image lên Minio
    // public function uploadMinIO(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'file' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    //     ]);
    
    //     if ($validator->fails()) {
    //         return JsonResponse::error(400,$validator->messages(),400);
    //     }
    //     if ($request->hasFile('file') && $request->file('file')->isValid()) {
    //         $file = $request->file('file');
    //         $fileName = time() . '_' . $file->getClientOriginalName();
    //         $path = $file->storeAs('uploads', $fileName, 's3');
    //         $url = Storage::disk('s3')->url($path);
    //         return JsonResponse::handle(200,ConstantsMessage::SUCCESS,['url' => $url],200);
    //     }
    //     return JsonResponse::error(400,ConstantsMessage::ERROR,400);
    // }





}

