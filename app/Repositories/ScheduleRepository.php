<?php
namespace App\Repositories;

use App\Models\Appointment; 
use App\Models\Category;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;

class ScheduleRepository{

    public function addSchedule($data) {
        // $user = User::with('role')->find($data['doctor_id']);
        // // if($user->role->name != 'Doctor'){
                
        // // } 
        if (!isset($data['schedule']) || !isset($data['doctor_id'])) {
            return response()->json(['error' => 'Dữ liệu không hợp lệ'], 400);
        }
        foreach ($data['schedule'] as $scheduleData) {
            if (!isset($scheduleData['date']) || !isset($scheduleData['time'])) {
                return false;
            }
            foreach ($scheduleData['time'] as $time) {
                $schedule = new Schedule();
                $schedule->doctor_id = $data['doctor_id']; 
                $schedule->time_id = $time;
                $schedule->status = 1; 
                $schedule->date = $scheduleData['date'];
                $schedule->created_at = Carbon::now('Asia/Ho_Chi_Minh');
                $schedule->save(); 
            }
        }
        return true;
    }

}