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
        if($quantityDoctor == 0){
            $appointment = new Appointment();
            $appointment->name = $data['name'];
            $appointment->phone = $data['phone'];
            $appointment->date = $data['date'];
            $appointment->time = $data['time'];
            $appointment->email = $data['email'];
            if(isset($data['status'])){
                $appointment->status = $data['status'];
            }else{
                $appointment->status =0;
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
            $appointment->email = $data['email'];
            if(isset($data['status'])){
                $appointment->status = $data['status'];
            }else{
                $appointment->status =0;
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
                $schedule->status = 1;
                $schedule->save();
                $appointment->doctor_id = $data['doctor_id'];
            }
               
            if ($appointment->save()) {
                // Gán dịch vụ nếu có
                // broadcast(new AppointmentCreatedEvent($appointment));
                // if (isset($data['services'])) {
                //     $appointment->Services()->attach($data['services']);
                // }
                if (isset($data['services']) && is_array($data['services'])) {
                    $serviceIds = collect($data['services'])->pluck('id')->toArray();
                    $appointment->Services()->attach($serviceIds);
                }
                return ['success' => true, 'appointment' => $appointment];
            }
      
    
        return ['success' => false, 'message' => "Đã xảy ra lỗi khi thêm cuộc hẹn"];
        
    }
    public function update($data,$appointment){
            $appointment->name = $data['name'];
            $appointment->phone = $data['phone'];
            $appointment->date = $data['date'];
            $appointment->time = $data['time'];
            $appointment->email = $data['email'];
                $schedule = Schedule::with('time')->where('date',  $data['date'])
                ->whereHas('time', function ($query) use ($data) {
                    $query->where('time', $data['time']); 
                })->where('doctor_id',$data['doctor_id'])->first();
                $schedule->status=1;
                $schedule->save();
            $appointment->status = $data['status'];
            $appointment->note = $data['note'];
            $appointment->updated_at =  Carbon::now('Asia/Ho_Chi_Minh');
        if( isset($data['doctor_id'])){
            $schedule = Schedule::where('date',$data['date'])->where('time',$data['time'])->where('doctor_id',$data['doctor_id'])->get();
            $schedule->status = 0;
            $schedule->save();
            $appointment->doctor_id = $data['doctor_id'];
        }
            $appointment->save();
            if (isset($data['services'])) {
                // Đồng bộ các dịch vụ liên quan,
                $appointment->services()->sync($data['services']);
            } else {
                // Nếu không có dịch vụ nào được cung cấp, xóa tất cả các dịch vụ liên quan
                $appointment->services()->detach();
            }
        return $appointment;
    }
}