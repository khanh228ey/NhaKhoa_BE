<?php

namespace App\Http\Controllers;

use App\Commons\Responses\JsonResponse;
use App\Mail\TestMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function send()
    {
        $data = [
            'name' => 'Name',
            'message' => 'Đây là nội dung email test'
        ];
        for($i = 0; $i < 5; $i++){
            Mail::to('tombilithecat30@gmail.com')->send(new TestMail($data));
        }
        return JsonResponse::handle(200, 'Email sent successfully', null, 200);
    }
}