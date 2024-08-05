<?php
namespace App\Repositories\Manager;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\History;
use App\Models\Invoices;
use App\Models\User;
use Carbon\Carbon;

class OverviewRepository{

    
    Public function responseOverview($title,$total,$message){
        $data = [
            "title" =>  $title,
            'total' => (int)$total,
            'message' => $message,
        ];
        return $data;
    }

    public function totalAppointment(){
            $title = "Tổng số lịch hẹn:";
            $date = Carbon::now('Asia/Ho_Chi_Minh');
            //Tháng hiện tại
            $startOfCurrentMonth = $date->startOfMonth()->format('Y-m-d');
            $endOfCurrentMonth = $date->endOfMonth()->format('Y-m-d'); 
            $totalCurrent = Appointment::whereBetween('created_at', [$startOfCurrentMonth, $endOfCurrentMonth])->count();
            // Tháng trước
            $startOfPreviousMonth = $date->subMonthsNoOverflow(1)->startOfMonth()->format('Y-m-d');
            $endOfPreviousMonth = $date->subMonthsNoOverflow(0)->endOfMonth()->format('Y-m-d'); 
            $totalPrevious = Appointment::whereBetween('created_at', [$startOfPreviousMonth, $endOfPreviousMonth])->count();
            $difference = $totalCurrent - $totalPrevious;
            $message = '';
            if ($difference > 0) {
                $message = 'Tăng <span style ="color:blue;">'.$difference.'</span> lịch hẹn so với tháng trước.';
            } elseif ($difference < 0) {
                $message = 'Giảm <span style ="color:blue;">'.abs($difference).'</span> lịch hẹn so với tháng trước.';
            } else {
                $message = 'Tăng <span style ="color:blue;">0</span> lịch hẹn so với tháng trước.';
            }
            return $this->responseOverview($title,$totalCurrent,$message);
    }


    Public function totalHistory(){
         $title = "Tổng số lịch khám:";
        $date = Carbon::now('Asia/Ho_Chi_Minh');
        //Tháng hiện tại
        $startOfCurrentMonth = $date->startOfMonth()->format('Y-m-d');
        $endOfCurrentMonth = $date->endOfMonth()->format('Y-m-d');
        $totalAppointmentCurrentMonth = History::where('status',1)->whereBetween('created_at', [$startOfCurrentMonth, $endOfCurrentMonth])->count();
        //Tháng trước
        $startOfPreviousMonth = $date->subMonthsNoOverflow(1)->startOfMonth()->format('Y-m-d');
        $endOfPreviousMonth = $date->subMonthsNoOverflow(0)->endOfMonth()->format('Y-m-d');
        $totalAppointmentPreviousMonth = History::where('status',1)->whereBetween('created_at', [$startOfPreviousMonth, $endOfPreviousMonth])->count();
        $difference = $totalAppointmentCurrentMonth - $totalAppointmentPreviousMonth;
        $message = '';
        if ($totalAppointmentPreviousMonth > 0) {
            if ($difference > 0) {
                $message = 'Tăng <span style ="color:blue;">'.$difference.'</span> lịch khám so với tháng trước.';
            } elseif ($difference < 0) {
                $message = 'Giảm <span style ="color:blue;">'.abs($difference). '</span> lịch khám so với tháng trước.';
            } else {
                $message = 'Tăng <span style ="color:blue;">0</span> lịch khám so với tháng trước.';
            }
        } else {
            $message = 'Tăng ' . $totalAppointmentCurrentMonth . ' lịch khám so với tháng trước.';
        }
        return $this->responseOverview($title,$totalAppointmentCurrentMonth,$message);
    }

    Public function totalTurnover(){
        $title = "Tổng doanh thu (VND):";
        $date = Carbon::now('Asia/Ho_Chi_Minh');
        //Tháng hiện tại
        $startOfCurrentMonth = $date->startOfMonth()->format('Y-m-d H:i:s');
        $endOfCurrentMonth = $date->endOfMonth()->format('Y-m-d H:i:s'); 
        $totalCurrent = Invoices::where('status',1)->whereBetween('created_at', [$startOfCurrentMonth, $endOfCurrentMonth])->sum('total_price');
        // Tháng trước
        $startOfPreviousMonth = $date->subMonthsNoOverflow(1)->startOfMonth()->format('Y-m-d H:i:s');
        $endOfPreviousMonth = $date->subMonthsNoOverflow(0)->endOfMonth()->format('Y-m-d H:i:s'); 
        $totalPrevious = Invoices::whereBetween('created_at', [$startOfPreviousMonth, $endOfPreviousMonth])->sum('total_price');
        $percentageChange = $totalPrevious > 0 ? (($totalCurrent - $totalPrevious) / $totalPrevious) * 100 : ($totalCurrent > 0 ? 100 : 0);
        $message = '';
        if ($percentageChange >= 0) {
            $message = 'Tăng <span style ="color:blue;">'.number_format($percentageChange, 1).'%</span> doanh thu so với tháng trước.';
        } elseif ($percentageChange < 0) {
            $message = 'Giảm <span style ="color:blue;">'.number_format(abs($percentageChange),1).'%</span> doanh thu so với tháng trước.';
        }
        return $this->responseOverview($title,number_format($totalCurrent),$message);
    }
    
    Public function totalCustomer(){
         $title = "Số khách hàng mới:";
        $date = Carbon::now('Asia/Ho_Chi_Minh');
        //Tháng hiện tại
        $startOfCurrentMonth = $date->startOfMonth()->format('Y-m-d');
        $endOfCurrentMonth = $date->endOfMonth()->format('Y-m-d'); 
        $totalCurrent = Customer::whereBetween('created_at', [$startOfCurrentMonth, $endOfCurrentMonth])->count();
        $totalCustomer = Customer::count();
        // Tháng trước
        $message = 'Có <span style ="color:blue;">'.$totalCurrent.'</span> khách hàng mới trong tháng';
        $total = $totalCurrent.'/'.$totalCustomer;
        return $this->responseOverview($title,$total,$message);
    }


    Public function monthlyStatistics(){
        $title = "Doanh thu trong tháng ";
        $date = Carbon::now('Asia/Ho_Chi_Minh');
        for ($month = 1; $month <= 12; $month++) {
            $startMonth = $date->month($month)->startOfMonth()->format('Y-m-d');
            $endMonth = $date->month($month)->endOfMonth()->format('Y-m-d');
            // dd($endMonth);
            $total = (int)Invoices::where('status',1)->whereBetween('created_at', [$startMonth, $endMonth])->sum('total_price');
           
            $title = $title. $month;
            $message = $month."/".date('Y');
            $totals[] = $this->responseOverview($title,$total,$message);
            $title = "Doanh thu trong tháng ";
        }
        return $totals;
        }
 
        public function appointmentStatistics() {
            
            $totalCancel = Appointment::where('status', 2)->count();
            $totalDone = Appointment::where('status', 1)->count();
        
            $totals = [
                $this->responseOverview('Số lịch hẹn đã bị hủy ',$totalCancel, 'Đã hủy'),
                $this->responseOverview('Sô lịch hẹn hoàn thành ',$totalDone, 'Hoàn thành')
            ];
        
            return $totals;
        }

    


}



