<?php
namespace App\Repositories;

use App\Models\Appointment; 
use App\Models\Category;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;

class ScheduleRepository{


    // public function addSchedule($data) {
    //     foreach ($data['schedule'] as $scheduleData) {
    //         foreach ($scheduleData['time'] as $time) {
    //             $schedule = new Schedule();
    //             $schedule->doctor_id = $data['doctor_id']; // Gán ID bác sĩ
    //             $schedule->time_id = $time; // Gán ID thời gian
    //             $schedule->status = 0; // Gán trạng thái ban đầu là 0
    //             $schedule->date = Carbon::createFromFormat('d/m/Y', $scheduleData['date'])->format('Y-m-d'); // Định dạng lại ngày
    //             $schedule->created_at = Carbon::now('Asia/Ho_Chi_Minh'); // Gán thời gian hiện tại
    //             $schedule->save(); // Lưu lịch trình vào cơ sở dữ liệu
    //         }
    //     }
    // }

    public function addSchedule($data) {
        $user = User::with('role')->find($data['doctor_id']);
        if($user->role->name != 'Doctor'){
                
        } 
        if (!isset($data['schedule']) || !isset($data['doctor_id'])) {
            return response()->json(['error' => 'Dữ liệu không hợp lệ'], 400);
        }
        foreach ($data['schedule'] as $scheduleData) {
            // Kiểm tra và gán giá trị các khóa cần thiết
            if (!isset($scheduleData['date']) || !isset($scheduleData['time'])) {
                return false;
            }
            foreach ($scheduleData['time'] as $time) {
                $schedule = new Schedule();
                $schedule->doctor_id = $data['doctor_id']; 
                $schedule->time_id = $time;
                $schedule->status = 0; 
                $schedule->date = Carbon::createFromFormat('d/m/Y', $scheduleData['date'])->format('Y-m-d');
                $schedule->created_at = Carbon::now('Asia/Ho_Chi_Minh');
                $schedule->save(); 
            }
        }
        return true;
    }

}