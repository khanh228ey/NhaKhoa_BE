<?php
namespace App\Repositories\Manager;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class NotiRepository{



    Public function getNotify($limit,$page){
        $getNotifications = Notification::orderBy('created_at', 'DESC')
                                 ->paginate($limit, ['*'], 'page', $page);
        $notifications =  collect($getNotifications->items());   
        Log::info('Lịch hẹn đã được tạo :' . $notifications);         
        return $notifications;
    }

    Public function createNotiAppointment($data){
        $noti = new Notification();
        $noti->title = "Lịch hẹn mới";
        $noti->message = $data->name." đã đặt một lịch hẹn vào ngày " .$data->date .', khung giờ '.$data->time;
        $noti->url = "/lich-hen/".$data->id;
        $noti->created_at = Carbon::now('Asia/Ho_Chi_Minh');
        $noti->save();
        return $noti;
    }


    Public function updateNoti($notifications){
        $notifications->status = 0;
        $notifications->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
        $notifications->save();
        return $notifications;
    }
    
    Public function findById($id){
        $noti = Notification::findOrFail($id);
        return $noti;
    }



}

