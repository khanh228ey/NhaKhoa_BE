<?php
namespace App\Repositories;

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
                 $service['quantity_sold'] == 0 ? "0" : $service['quantity_sold'],
                 $service['total_price'] == 0 ? "0" : $service['total_price'],
            ];
        }, $servicesArray);
        array_unshift($formattedData, ['', '', '', '']);
        $filename = "Thống kê dịch vụ.xlsx";
        return Excel::download(new ServicesExport($formattedData), $filename);
    }
    


    Public function exportInvoiceExcel($invoice){
        
    }



    Public function exportHistoryExcel($history){
        
    }
    
    
    Public function exportAppointmentExcel($appointment){
        
    }
}