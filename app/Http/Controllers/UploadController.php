<?php

namespace App\Http\Controllers;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UploadController extends Controller
{

    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads', $fileName, 's3');
            $url = Storage::disk('s3')->url($path);
            // return response()->json(['url' => $url], 200);
            return JsonResponse::handle(200,ConstantsMessage::SUCCESS,['url' => $url],200);
        }
        // return response()->json(['error' => 'No file uploaded or file is not valid'], 400);
        return JsonResponse::error(400,ConstantsMessage::ERROR,400);
    }
}
