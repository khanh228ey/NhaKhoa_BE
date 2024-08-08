<?php
namespace App\Repositories\Manager;

use App\Models\Appointment; 
use App\Models\Category;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class ScheduleRepository{

    public function addSchedule($data) {
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

    Public function updateSchedule(Request $request,$schedules){
     try{
        $data = $request->all();
        foreach ($schedules as $schedule) {
            $schedule->delete();
        }
         $times = $data['time'];
        foreach($times as $item){
            $schedule = new Schedule();
            $schedule->date = $data['date'];
            $schedule->doctor_id = $data['doctor_id'];
            $schedule->time_id = $item;
            $schedule->created_at = Carbon::now('Asia/Ho_Chi_Minh');
            $schedule->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
            $schedule->status = 1;
            $schedule->save();
        }
        return true;
        }catch(Exception $e){
            return false;
        }
    }

    public function deleteSchedule($schedules){
        try {
            foreach($schedules as $item){
                $item->delete();
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


  
}