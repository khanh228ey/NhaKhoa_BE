<?php
namespace App\Repositories;

use App\Models\Appointment; 
use App\Models\Category;
use App\Models\Schedule;
use Carbon\Carbon;

class AppointmentRepository{

    public function addAppointment($data) {
        $date = Carbon::createFromFormat('d/m/Y', $data['date'])->format('Y-m-d');
        $quantityDoctor = Schedule::with('time')
            ->where('date',  $date)
            ->whereHas('time', function ($query) use ($data) {
                $query->where('time', $data['time']); 
            })->count();
        // Kiểm tra số lượng cuộc hẹn đã có
        $quantityAppointment = Appointment::where('date',  $date)->where('time', $data['time'])->count();
        // Kiểm tra điều kiện để thêm cuộc hẹn
        if($quantityDoctor == 0){
            $appointment = new Appointment();
            $appointment->name = $data['name'];
            $appointment->phone = $data['phone'];
            $appointment->date =  $date;
            $appointment->time = $data['time'];
            $appointment->email = $data['email'];
            $appointment->status = $data['status'];
            $appointment->note = $data['note'];
            $appointment->created_at = Carbon::now('Asia/Ho_Chi_Minh');
        }
        else if ($quantityAppointment < $quantityDoctor) {
            $appointment = new Appointment();
            $appointment->name = $data['name'];
            $appointment->phone = $data['phone'];
            $appointment->date =  $date;
            $appointment->time = $data['time'];
            $appointment->email = $data['email'];
            $appointment->status = $data['status'];
            $appointment->note = $data['note'];
            $appointment->created_at = Carbon::now('Asia/Ho_Chi_Minh');
        } else{
            return ['success' => false, 'message' => "Lịch hẹn trong thời gian này đã đầy"];
        }
            // Gán doctor_id nếu có
            if (isset($data['doctor_id'])) {
                $schedule = Schedule::with('time')->where('date',  $date)
                ->whereHas('time', function ($query) use ($data) {
                    $query->where('time', $data['time']); 
                })->where('doctor_id',$data['doctor_id'])->first();
                $schedule->status=1;
                $schedule->save();
                $appointment->doctor_id = $data['doctor_id'];
            }
               
            if ($appointment->save()) {
                // Gán dịch vụ nếu có
                if (isset($data['service'])) {
                    $appointment->Services()->attach($data['service']);
                }
                return ['success' => true, 'appointment' => $appointment];
            }
      
    
        return ['success' => false, 'message' => "Đã xảy ra lỗi khi thêm cuộc hẹn"];
        
    }
    public function update($data){
        $id = $data['id'];
        $appointment = Appointment::find($id);
        $appointment->name = $data['name'];
            $appointment->phone = $data['phone'];
            $appointment->date = $data['date'];
            $appointment->time = $data['time'];
            $appointment->email = $data['email'];
            if($data['status'] == 2){
                $schedule = Schedule::with('time')->where('date',  $data['date'])
                ->whereHas('time', function ($query) use ($data) {
                    $query->where('time', $data['time']); 
                })->where('doctor_id',$data['doctor_id'])->first();
                $schedule->status=0;
                $schedule->save();
            }
            $appointment->status = $data['status'];
            $appointment->note = $data['note'];
            $appointment->updated_at =  Carbon::now('Asia/Ho_Chi_Minh');
        if( isset($data['doctor_id'])){
            $schedule = Schedule::where('date',$data['date'])->where('time',$data['time'])->where('doctor_id',$data['doctor_id'])->get();
            $schedule->status = 1;
            $schedule->save();
            $appointment->doctor_id = $data['doctor_id'];
        }
            $appointment->save();
            if (isset($data['service'])) {
                // Đồng bộ các dịch vụ liên quan, xóa các dịch vụ cũ không còn trong mảng $data['service']
                $appointment->services()->sync($data['service']);
            } else {
                // Nếu không có dịch vụ nào được cung cấp, xóa tất cả các dịch vụ liên quan
                $appointment->services()->detach();
            }
        return $appointment;
    }
}