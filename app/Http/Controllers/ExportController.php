<?php

namespace App\Http\Controllers;

use App\Commons\Responses\JsonResponse;
use App\Exports\ServicesExport;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    //

    public function printInvoicePdf(Request $request)
    {
        $pdf = new Dompdf();
        $html = $request->input('html');
        $pdf->loadHtml($html);
        $pdf->render();
        return $pdf->stream('invoice.pdf');
    }

    public function export(Request $request)
    {
            $data = $request->all();
        if (empty($data['services']) || empty($data['date'])) {
            return JsonResponse::handle(400, 'Lỗi dữ liệu', null, 400);
        }
        try {
            $date = Carbon::createFromFormat('Y-m-d', $data['date'], 'Asia/Ho_Chi_Minh');
            $month = $date->format('m'); 
        } catch (\Exception $e) {
            return JsonResponse::handle(400, 'Lỗi ngày tháng không hợp lệ', null, 400);
        }
        $services = $data['services'];
        $formattedData = array_map(function($service) {
            return [
                'Mã dịch vụ' => $service['service']['id'] ?? 'N/A',
                'Tên dịch vụ' => $service['service']['name'] ?? 'N/A',
                'Số lượng bán ra' => $service['quantity'] ?? 0,
                'Tổng thu' => $service['price'] ?? 0,
            ];
        }, $services);
        
        $filename = "Thống kê tháng $month.xlsx";
        
        return Excel::download(new ServicesExport($formattedData), $filename);
    }
}
