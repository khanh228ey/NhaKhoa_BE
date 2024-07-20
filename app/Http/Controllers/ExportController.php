<?php

namespace App\Http\Controllers;

use App\Commons\Responses\JsonResponse;
use App\Exports\ServicesExport;
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
        $data = $request->input('data');

        if (empty($data)) {
            return JsonResponse::handle(400,'Lỗi dữ liệu',null,400);
        }
        $formattedData = array_map(function($item) {
                return [
                    'Mã dịch vụ' => $item['service']['id'],
                    'Tên dịch vụ' => $item['service']['name'],
                    'Số lượng bán ra' => $item['quantity'] ?? 0,
                    'Tổng thu' => $item['price'] ?? 0,
                ];
        }, $data);

        return Excel::download(new ServicesExport($formattedData), 'Thống kê tháng '.$data.'.xlsx');
    }
}
