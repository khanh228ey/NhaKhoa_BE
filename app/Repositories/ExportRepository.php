<?php
namespace App\Repositories;

use App\Exports\InvoiceExport;
use App\Exports\ServicesExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportRepository{

    public function exportServiceExcel($services)
    {
        $servicesArray = $services->toArray();
    
        $formattedData = array_map(function($service) {
            return [
                 $service['id'],
                 $service['name'],
                 $service['unit'],
                 $service['quantity'],
                 $service['quantity_sold'], 
                 $service['total_price'] == 0 ? "0" : $service['total_price'],
            ];
        }, $servicesArray);
        array_unshift($formattedData, ['', '', '', '']);
        return $formattedData;
    }
    


    public function exportInvoiceExcel($invoices) {
        $formattedData = [];
        foreach($invoices as $invoice) {
                $formattedData[] = [
                $invoice->id,
                $invoice->history->customer->name,
                number_format($invoice->total_price),
                $invoice->method_payment == 0 ? "Tiền mặt" : "Chuyển khoản",
                $invoice->user->name,
            ];
        }
        array_unshift($formattedData, ['', '', '', '', '']);
        return $formattedData;
    }

    Public function exportAppointmentExcel($appointments){
        $formattedData = [];
            foreach($appointments as $appointment) {
                $formattedData[] = [
                    $appointment->id,
                    $appointment->name,
                    $appointment->date,
                    $appointment->time,
                    $appointment->status == 1?"Hoàn thành" :"Đã Hủy",
                ];
            }
            array_unshift($formattedData, ['', '', '', '', '']);
            return $formattedData;
    }

    
    Public function exportHistoryExcel($histories){
        $formattedData = [];
        foreach($histories as $history) {
            $formattedData[] = [
                $history->id,
                $history->customer->name,
                $history->doctor->name,
                $history->date,
                $history->time,
                $history->status == 1?"Hoàn thành" :"Đã Hủy",
                $history->invoice->total_price ?? 0,
            ];
        }
        array_unshift($formattedData, ['', '', '', '', '']);
        return $formattedData;
    }
    
}