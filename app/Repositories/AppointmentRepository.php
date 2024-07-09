<?php
namespace App\Repositories;

use App\Events\AppointmentCreatedEvent;
use App\Models\Appointment; 
use App\Models\Category;
use App\Models\Schedule;
use Carbon\Carbon;

class AppointmentRepository{

    public function addAppointment($data) {
        
        $quantityDoctor = Schedule::with('time')
            ->where('date',  $data['date'])
            ->whereHas('time', function ($query) use ($data) {
                $query->where('time', $data['time']); 
            })->count();
        // Kiểm tra số lượng cuộc hẹn đã có
        $quantityAppointment = Appointment::where('date',  $data['date'])->where('time', $data['time'])->where('status', '!=', 2)->count();
        // Kiểm tra điều kiện để thêm cuộc hẹn
        if($quantityDoctor == 0 && $quantityAppointment < 2){
            $appointment = new Appointment();
            $appointment->name = $data['name'];
            $appointment->phone = $data['phone'];
            $appointment->date = $data['date'];
            $appointment->time = $data['time'];
            if(isset($data['status'])){
                $appointment->status = $data['status'];
            }else{
                $appointment->status = 0;
            }
            $appointment->note = $data['note'];
            $appointment->created_at = Carbon::now('Asia/Ho_Chi_Minh');
        }
        else if ($quantityAppointment < $quantityDoctor) {
            $appointment = new Appointment();
            $appointment->name = $data['name'];
            $appointment->phone = $data['phone'];
            $appointment->date =  $data['date'];
            $appointment->time = $data['time'];
            if(isset($data['status'])){
                $appointment->status = $data['status'];
            }else{
                $appointment->status = 0;
            }
            $appointment->note = $data['note'];
            $appointment->created_at = Carbon::now('Asia/Ho_Chi_Minh');
        } else{
            return ['success' => false, 'message' => "Lịch hẹn trong thời gian này đã đầy"];
        }
            // Gán doctor_id nếu có
            if (isset($data['doctor_id'])) {
                $schedule = Schedule::with('time')->where('date',  $data['date'])
                ->whereHas('time', function ($query) use ($data) {
                    $query->where('time', $data['time']); 
                })->where('doctor_id',$data['doctor_id'])->first();
                if($schedule){
                    $schedule->status = 0;
                    $schedule->save();
                }
                $appointment->doctor_id = $data['doctor_id'];
            }
               
            if ($appointment->save()) {
                if (isset($data['services']) && is_array($data['services'])) {
                    $serviceIds = collect($data['services'])->pluck('id')->toArray();
                    $appointment->Services()->attach($serviceIds);
                }
                return ['success' => true, 'appointment' => $appointment];
            }
      
    
        return ['success' => false, 'message' => "Đã xảy ra lỗi khi thêm cuộc hẹn"];
        
    }
    // public function update($data,$appointment){
    //     if( isset($data['doctor_id'])){
    //         $schedule = Schedule::with('time')->where('date',  $appointment->date)
    //             ->whereHas('time', function ($query) use ($appointment) {
    //                 $query->where('time', $appointment->time); 
    //             })->where('doctor_id',$data['doctor_id'])->first();
    //             if($schedule){
    //                 $schedule->status=1;
    //                 $schedule->save();
    //             }
    //         $schedule = Schedule::where('date',$data['date'])->where('time',$data['time'])->where('doctor_id',$data['doctor_id'])->get();
    //         $schedule->status = 0;
    //         $schedule->save();
    //         $appointment->doctor_id = $data['doctor_id'];
    //     }
    //         $appointment->name = $data['name'];
    //         $appointment->phone = $data['phone'];
    //         $appointment->date = $data['date'];
    //         $appointment->time = $data['time'];
    //         $appointment->email = $data['email'];
                
    //         $appointment->status = $data['status'];
    //         $appointment->note = $data['note'];
    //         $appointment->updated_at =  Carbon::now('Asia/Ho_Chi_Minh');
       
    //         $appointment->save();
    //         if (isset($data['services'])) {
    //                 $serviceIds = collect($data['services'])->pluck('id')->toArray();
    //                 $appointment->Services()->attach($serviceIds);
    //             $appointment->services()->sync($serviceIds);
    //         } else {
    //             // Nếu không có dịch vụ nào được cung cấp, xóa tất cả các dịch vụ liên quan
    //             $appointment->services()->detach();
    //         }
    //     return $appointment;
    // }

    public function update($data, $appointment) {
        $quantityDoctor = Schedule::with('time')->where('date', $data['date'])
        ->whereHas('time', function ($query) use ($data) {
                $query->where('time', $data['time']); })->count();
        $quantityAppointment = Appointment::where('date', $data['date'])->where('time', $data['time'])->where('status', '!=', 2)
        ->where('id', '!=', $appointment->id)->count();
        // Kiểm tra điều kiện để cập nhật cuộc hẹn
        if ($quantityDoctor == 0 && $quantityAppointment < 2) {
            $appointment->name = $data['name'];
            $appointment->phone = $data['phone'];
            $appointment->date = $data['date'];
            $appointment->time = $data['time'];
            $appointment->status = isset($data['status']) ? $data['status'] : 0;
            $appointment->note = $data['note'];
            $appointment->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
        } else if ($quantityAppointment < $quantityDoctor) {
            $appointment->name = $data['name'];
            $appointment->phone = $data['phone'];
            $appointment->date = $data['date'];
            $appointment->time = $data['time'];
            $appointment->status = isset($data['status']) ? $data['status'] : 0;
            $appointment->note = $data['note'];
            $appointment->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
        }else{
            return ['success' => false, 'message' => "Lịch hẹn trong thời gian này đã đầy"];
        }
            if (isset($data['doctor_id'])) {
                $schedule = Schedule::with('time')->where('date', $appointment->date)
                    ->whereHas('time', function ($query) use ($appointment) {
                        $query->where('time', $appointment->time);
                    })->where('doctor_id', $data['doctor_id'])->first();
                if ($schedule) {
                    $schedule->status = 1;
                    $schedule->save();
                }
                $schedule = Schedule::with('time')->where('date', $data['date'])->whereHas('time', function ($query) use ($data) {
                    $query->where('time', $data['time']);
                })->where('doctor_id', $data['doctor_id'])->first();
                
            if ($schedule && $data['status'] !=2) {
                $schedule->status = 0;
                $schedule->save();
            }
            $appointment->doctor_id = $data['doctor_id'];
        }
        if ($appointment->save()) {
            if (isset($data['services']) && is_array($data['services'])) {
                $serviceIds = collect($data['services'])->pluck('id')->toArray();
                $appointment->services()->sync($serviceIds);
            } else {
                $appointment->services()->detach();
            }
            
            return ['success' => true, 'appointment' => $appointment];
        }
    
        return ['success' => false, 'message' => "Đã xảy ra lỗi khi cập nhật cuộc hẹn"];
    }
    
}