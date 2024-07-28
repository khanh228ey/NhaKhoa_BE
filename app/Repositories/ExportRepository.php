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
        $filename = "Thống kê dịch vụ.xlsx";
        return Excel::download(new ServicesExport($formattedData), $filename);
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
            $filename = "Thống kê hóa đơn.xlsx";
            return Excel::download(new InvoiceExport($formattedData), $filename);
        }



    Public function exportHistoryExcel($history){
        
    }
    
    
    Public function exportAppointmentExcel($appointment){
        
    }
}