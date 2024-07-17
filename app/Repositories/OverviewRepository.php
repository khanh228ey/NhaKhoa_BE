<?php
namespace App\Repositories;

use App\Models\Appointment;
use App\Models\History;
use App\Models\Invoices;
use App\Models\User;
use Carbon\Carbon;

class OverviewRepository{

    
    Public function responseOverview($total,$message){
        $data = [
            'total' => $total,
            'message' => $message,
        ];
        return $data;
    }

    public function totalAppointment(){
            $date = Carbon::now('Asia/Ho_Chi_Minh');
            //Tháng hiện tại
            $startOfCurrentMonth = $date->startOfMonth()->format('Y-m-d H:i:s');
            $endOfCurrentMonth = $date->endOfMonth()->format('Y-m-d H:i:s'); 
            $totalCurrent = Appointment::whereBetween('created_at', [$startOfCurrentMonth, $endOfCurrentMonth])->count();
            // Tháng trước
            $startOfPreviousMonth = $date->subMonthsNoOverflow(1)->startOfMonth()->format('Y-m-d H:i:s');
            $endOfPreviousMonth = $date->subMonthsNoOverflow(0)->endOfMonth()->format('Y-m-d H:i:s'); 
            $totalPrevious = Appointment::whereBetween('created_at', [$startOfPreviousMonth, $endOfPreviousMonth])->count();
            $difference = $totalCurrent - $totalPrevious;
            $message = '';
            if ($difference > 0) {
                $message = 'Tăng ' . $difference . ' lịch hẹn so với tháng trước.';
            } elseif ($difference < 0) {
                $message = 'Giảm ' . abs($difference) . ' lịch hẹn so với tháng trước.';
            } else {
                $message = 'Tăng 0 lịch hẹn so với tháng trước.';
            }
            return $this->responseOverview($totalCurrent,$message);
    }


    Public function totalHistory(){
        $date = Carbon::now('Asia/Ho_Chi_Minh');
        //Tháng hiện tại
        $startOfCurrentMonth = $date->startOfMonth()->format('Y-m-d H:i:s');
        $endOfCurrentMonth = $date->endOfMonth()->format('Y-m-d H:i:s');
        $totalAppointmentCurrentMonth = History::where('status',1)->whereBetween('created_at', [$startOfCurrentMonth, $endOfCurrentMonth])->count();
        //Tháng trước
        $startOfPreviousMonth = $date->subMonthsNoOverflow(1)->startOfMonth()->format('Y-m-d H:i:s');
        $endOfPreviousMonth = $date->subMonthsNoOverflow(0)->format('Y-m-d H:i:s');
        $totalAppointmentPreviousMonth = History::where('status',1)->whereBetween('created_at', [$startOfPreviousMonth, $endOfPreviousMonth])->count();
        $difference = $totalAppointmentCurrentMonth - $totalAppointmentPreviousMonth;
        $message = '';
        if ($totalAppointmentPreviousMonth > 0) {
            if ($difference > 0) {
                $message = 'Tăng ' . $difference . ' lịch khám so với tháng trước.';
            } elseif ($difference < 0) {
                $message = 'Giảm ' . abs($difference) . ' lịch khám so với tháng trước.';
            } else {
                $message = 'Tăng 0 lịch khám so với tháng trước.';
            }
        } else {
            $message = 'Tăng ' . $totalAppointmentCurrentMonth . ' lịch khám so với tháng trước.';
        }
        return $this->responseOverview($totalAppointmentCurrentMonth,$message);
    }

    Public function totalTurnover(){
        $date = Carbon::now('Asia/Ho_Chi_Minh');
        //Tháng hiện tại
        $startOfCurrentMonth = $date->startOfMonth()->format('Y-m-d H:i:s');
        $endOfCurrentMonth = $date->endOfMonth()->format('Y-m-d H:i:s'); 
        $totalCurrent = Invoices::whereBetween('created_at', [$startOfCurrentMonth, $endOfCurrentMonth])->sum('total_price');
        // Tháng trước
        $startOfPreviousMonth = $date->subMonthsNoOverflow(1)->startOfMonth()->format('Y-m-d H:i:s');
        $endOfPreviousMonth = $date->subMonthsNoOverflow(0)->endOfMonth()->format('Y-m-d H:i:s'); 
        $totalPrevious = Invoices::whereBetween('created_at', [$startOfPreviousMonth, $endOfPreviousMonth])->sum('total_price');
        $percentageChange = $totalPrevious > 0 ? (($totalCurrent - $totalPrevious) / $totalPrevious) * 100 : ($totalCurrent > 0 ? 100 : 0);
        $message = '';
        if ($percentageChange >= 0) {
            $message = 'Tăng ' . number_format($percentageChange, 1) . '% doanh thu so với tháng trước.';
        } elseif ($percentageChange < 0) {
            $message = 'Giảm ' . number_format(abs($percentageChange), 1) . '% doanh thu so với tháng trước.';
        }
        return $this->responseOverview($totalCurrent,$message);
    }
    
    Public function totalCustomer(){
        $date = Carbon::now('Asia/Ho_Chi_Minh');
        //Tháng hiện tại
        $startOfCurrentMonth = $date->startOfMonth()->format('Y-m-d H:i:s');
        $endOfCurrentMonth = $date->endOfMonth()->format('Y-m-d H:i:s'); 
        $totalCurrent = User::whereBetween('created_at', [$startOfCurrentMonth, $endOfCurrentMonth])->count();
        $totalCustomer = User::count();
        // Tháng trước
        $message = 'Có '. $totalCurrent.' khách hàng mới trong tháng';
        $total = $totalCurrent.'/'.$totalCustomer;
        return $this->responseOverview($total,$message);
    }




    Public function monthlyStatistics(){
        $date = Carbon::now('Asia/Ho_Chi_Minh');
        for ($month = 1; $month <= 12; $month++) {
            $startMonth = $date->copy()->month($month)->startOfMonth()->format('Y-m-d H:i:s');
            $endMonth = $date->copy()->month($month)->endOfMonth()->format('Y-m-d H:i:s');
            $total = Invoices::whereBetween('created_at', [$startMonth, $endMonth])->sum('total_price');
            $totals[] = $this->responseOverview($total,$month);
        }
        return $totals;
        }
 
        public function appointmentStatistics() {
            $totalCancel = Appointment::where('status', 2)->count();
            $totalDone = Appointment::where('status', 1)->count();
        
            $totals = [
                $this->responseOverview($totalCancel, 'Đã hủy'),
                $this->responseOverview($totalDone, 'Đã xong')
            ];
        
            return $totals;
        }

    


}




