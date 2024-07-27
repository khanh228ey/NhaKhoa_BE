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
                 $service['service']['id'] ?? 'N/A',
                 $service['service']['name'] ?? 'N/A',
                 $service['quantity_sold'] ?? "0",
                 $service['total_price'] ?? "0",
            ];
        }, $servicesArray);
    
        $filename = "Thống kê dịch vụ.xlsx";
        return Excel::download(new ServicesExport($formattedData), $filename);
    }
    
   
}