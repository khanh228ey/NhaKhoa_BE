<?php

namespace App\Http\Controllers;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Exports\ServicesExport;
use App\Models\Invoices;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    //

    public function printInvoicePdf(Request $request)
    {
        try{
            $id = $request->input('id');
            $invoice = Invoices::findOrFail($id);
            if($invoice->status == 0){
                return JsonResponse::handle(400,"Hóa đơn chưa thanh toán",$invoice->id,400);
            }
            $options = new Options();
            $options->set('defaultFont', 'DejaVu Sans');
            $pdf = new Dompdf($options);
            $html = view('invoice.index', ['invoice' => $invoice])->render();
            $pdf->loadHtml($html);
            $pdf->setPaper('A4', 'portrait');
            $pdf->render();
            return $pdf->stream('invoice.pdf', ['Attachment' => false]);
        }catch(Exception $e){
            return JsonResponse::handle(500,ConstantsMessage::ERROR,null,500);
        }
        
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
