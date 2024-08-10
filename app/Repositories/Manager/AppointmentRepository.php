<?php
namespace App\Repositories\Manager;
use App\Events\AppointmentCreatedEvent;
use App\Models\Appointment; 
use App\Models\Category;
use App\Models\Schedule;
use Carbon\Carbon;

class AppointmentRepository{

    public function addAppointment($data) {
        
        $quantityDoctor = Schedule::with('time')->where('date',  $data['date'])
        ->whereHas('time', function ($query) use ($data) {
                $query->where('time', $data['time']); 
            })->count();
        // Kiểm tra số lượng cuộc hẹn đã có
        $quantityAppointment = Appointment::where('date',  $data['date'])->where('time', $data['time'])->where('status', '!=', 2)->count();
        // Kiểm tra điều kiện để thêm cuộc hẹn
        if($quantityDoctor == 0 && $quantityAppointment < 2){
           $appointment = $this->add($data);
        }
        else if ($quantityAppointment < $quantityDoctor) {
            $appointment = $this->add($data);
        } else{
            return ['success' => false, 'message' => "Lịch hẹn trong thời gian này đã đầy"];
        }
        if (isset($data['doctor_id'])) {
               $this->updateStatusSchedule($data);
            }
        if (isset($data['services']) && is_array($data['services'])) {
                    $serviceIds = $this->appointmentDetail($data['services']);
                    $appointment->Services()->attach($serviceIds);
            }

        return ['success' => true, 'appointment' => $appointment];
   
    }
    
    public function updateAppointment($data, $appointment) {
        $quantityDoctor = Schedule::with('time')->where('date', $data['date'])
        ->whereHas('time', function ($query) use ($data) {
                $query->where('time', $data['time']); })->count();
        $quantityAppointment = Appointment::where('date', $data['date'])->where('time', $data['time'])->where('status', '!=', 2)
        ->where('id', '!=', $appointment->id)->count();
        // Kiểm tra điều kiện để cập nhật cuộc hẹn
        if ($quantityDoctor == 0 && $quantityAppointment < 2) {
           $appointment = $this->update($data,$appointment);
        } else if ($quantityAppointment < $quantityDoctor) {
            $appointment = $this->update($data,$appointment);
        }else{
            return ['success' => false, 'message' => "Lịch hẹn trong thời gian này đã đầy"];
        }
        if (isset($data['doctor_id'])) {$this->updateStatusSchedule($data);}
        if (isset($data['services']) && is_array($data['services'])) {
                $serviceIds = $this->appointmentDetail($data['services']);
                $appointment->services()->sync($serviceIds);
        } else {
                $appointment->services()->detach();
        }

        return ['success' => true, 'appointment' => $appointment];
    }


    public function add($data){
        $appointment = new Appointment();
        $appointment->name = $data['name'];
        $appointment->phone = $data['phone'];
        $appointment->date = $data['date'];
        $appointment->time = $data['time'];
        $appointment->status = isset($data['status']) ? $data['status'] : 0;
        $appointment->note = $data['note'];
        $appointment->created_at = Carbon::now('Asia/Ho_Chi_Minh');
        $appointment->doctor_id = $data['doctor_id'];
        $appointment->save();
        return $appointment;
    }


    public function update($data,$appointment){
        $appointment->name = $data['name'];
        $appointment->phone = $data['phone'];
        $appointment->date = $data['date'];
        $appointment->time = $data['time'];
        $appointment->status =  $data['status'];
        $appointment->note = $data['note'];
        $appointment->doctor_id = $data['doctor_id'];
        $appointment->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
        $appointment->save();
        return $appointment;
    }


    public function appointmentDetail($service){
        $serviceIds = collect($service)->pluck('id')->toArray();
        return $serviceIds;
    }

    public function updateStatusSchedule($data){
        $schedule = Schedule::with('time')->where('date',  $data['date'])
        ->whereHas('time', function ($query) use ($data) {
            $query->where('time', $data['time']); 
        })->where('doctor_id',$data['doctor_id'])->first();
        $schedule->status = 0;
        $schedule->save();
    }


    public function passes($data){
        $check = Appointment::where('name',$data['name'])->where('phone',$data['phone'])
        ->where();
    }
};