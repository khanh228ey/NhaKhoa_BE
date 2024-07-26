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
        $html = $request->input('html');

        $fullHtml = '
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                }
                .container {
                    width: 70%;
                    margin: 0 auto;
                    padding: 20px;
                    border: 1px solid #000;
                    box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
                }
                .header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 20px;
                }
                .header img {
                    max-width: 100px;
                }
                .header .info {
                    text-align: right;
                }
                .header .info p {
                    margin: 2px 0;
                }
                .title {
                    text-align: center;
                    font-weight: bold;
                    text-decoration: underline;
                    margin-bottom: 20px;
                }
                .details, .content {
                    margin-bottom: 20px;
                }
                .details .row, .content .row {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 5px;
                }
                .details .row p, .content .row p {
                    margin: 0;
                }
                .content p {
                    margin: 0;
                }
                .footer {
                    display: flex;
                    justify-content: space-between;
                    margin-top: 50px;
                }
                .footer .sign {
                    text-align: center;
                    width: 45%;
                }
                .footer .sign p {
                    margin: 0;
                }
                .field-label {
                    font-weight: bold;
                }
                .field-value {
                    font-style: italic;
                }
                .money {
                    font-weight: bold;
                }
            </style>
        </head>
        <body>' . $html . '</body>
        </html>';

        $pdf = new Dompdf();
        $pdf->loadHtml($fullHtml);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        return $pdf->stream('invoice.pdf', ["Attachment" => false]);
    }

    public function export(Request $request)
    {
            $data = $request->all();
        if (empty($data['end']) || empty($data['begin'])) {
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
