<?php
namespace App\Repositories;

use App\Models\Appointment;
use App\Models\History;
use App\Models\Invoices;
use App\Models\User;
use Carbon\Carbon;

class OverviewRepository{

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
            return [
                'total' => $totalCurrent,
                'message' => $message,
            ];
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

        return  [
            'total' => $totalAppointmentCurrentMonth,
            'message' => $message,
        ];
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
        return [
            'total' => $totalCurrent,
            'message' => $message,
        ];
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
        return [
            'total' => $totalCurrent.'/'.$totalCustomer,
            'message' => $message,
        ];
    }



    // Public function 


}