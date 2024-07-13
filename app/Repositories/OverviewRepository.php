<?php
namespace App\Repositories;

use App\Models\Appointment;
use Carbon\Carbon;

class OverviewRepository{

    public function totalAppointment(){
            $date = Carbon::now('Asia/Ho_Chi_Minh');
            $startOfCurrentQuarter = $date->firstOfQuarter()->format('Y-m-d');
            $endOfCurrentQuarter = $date->lastOfQuarter()->format('Y-m-d');

            $totalAppointmentCurrentQuarter = Appointment::whereBetween('created_at', [$startOfCurrentQuarter, $endOfCurrentQuarter])->count();
            $startOfPreviousQuarter = $date->subQuarter()->firstOfQuarter()->format('Y-m-d');
            $endOfPreviousQuarter = $date->lastOfQuarter()->format('Y-m-d');
            $totalAppointmentPreviousQuarter = Appointment::whereBetween('created_at', [$startOfPreviousQuarter, $endOfPreviousQuarter])->count();
            $growth = 0;
            if ($totalAppointmentPreviousQuarter > 0) {
                $growth = (($totalAppointmentCurrentQuarter - $totalAppointmentPreviousQuarter) / $totalAppointmentPreviousQuarter) * 100;
            }
            return response()->json([
                'total_current_quarter' => $totalAppointmentCurrentQuarter,
                'growth' => $growth,
            ]);
    }






}