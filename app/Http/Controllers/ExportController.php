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
        $date = Carbon::createFromFormat('Y-m-d', $data['date'], 'Asia/Ho_Chi_Minh');
        $month = $date->format('m');
        if (empty($data)) {
            return JsonResponse::handle(400,'Lỗi dữ liệu',null,400);
        }
        $formattedData = array_map(function($service) {
                return [
                    'Mã dịch vụ' => $service['id'],
                    'Tên dịch vụ' => $service['name'],
                    'Số lượng bán ra' => $service['quantity'] ?? 0,
                    'Tổng thu' => $service['price'] ?? 0,
                ];
        }, $data);

        return Excel::download(new ServicesExport($formattedData), 'Thống kê tháng '.$month.'.xlsx');
    }
}
