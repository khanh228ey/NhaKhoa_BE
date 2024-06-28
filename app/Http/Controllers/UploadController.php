<?php

namespace App\Http\Controllers;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UploadController extends Controller
{
    //Upload image lên Minio
    public function uploadMinIO(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        if ($validator->fails()) {
            return JsonResponse::error(400,$validator->messages(),400);
        }
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads', $fileName, 's3');
            $url = Storage::disk('s3')->url($path);
            return JsonResponse::handle(200,ConstantsMessage::SUCCESS,['url' => $url],200);
        }
        return JsonResponse::error(400,ConstantsMessage::ERROR,400);
    }

    // Upload ảnh lên cloudinary
    // public function uploadImage(Request $request)
    // {
    //     // Validate the request
    //     $request->validate([
    //         'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    //     ]);

    //     // Upload the image to Cloudinary
    //     $uploadedFileUrl = Cloudinary::upload($request->file('file')->getRealPath())->getSecurePath();

    //     // Return the URL of the uploaded image
    //     return response()->json(['url' => $uploadedFileUrl], 200);
    // }

    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        if ($validator->fails()) {
            return JsonResponse::handle(400,ConstantsMessage::Bad_Request,$validator->messages(),400);
        }
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $destinationPath = 'uploads';

            $file->move(public_path($destinationPath), $fileName);

            $url = asset($destinationPath . '/' . $fileName);

            return JsonResponse::handle(200, ConstantsMessage::SUCCESS, ['url' => $url], 200);
        }
        return JsonResponse::error(400, ConstantsMessage::ERROR, 400);
    }


}

