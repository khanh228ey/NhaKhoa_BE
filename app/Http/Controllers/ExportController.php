<?php

namespace App\Http\Controllers;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Exports\ServicesExport;
use App\Models\Invoices;
use App\Repositories\ExportRepository;
use App\Repositories\StatisticsRepository;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    //
    protected $exportRepository;
    public function __construct( ExportRepository $exportRepository)
    {
        $this->exportRepository = $exportRepository; 
        // $this->middleware('check.role:3');
    }

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
    

    Public function exportService(Request $request){
        try {
                $startDate = $request->query('beginDate');
                $endDate= $request->query('endDate');
                if (empty($startDate) || empty($endDate)) {
                    return JsonResponse::handle(400, 'Lỗi dữ liệu', null, 400);
                }
                $service = new StatisticsRepository;
                $statisticsService =$service->statisticService($request);
   
                return $this->exportRepository->exportServiceExcel($statisticsService);
        }catch(Exception $e){
            return JsonResponse::handle(500,ConstantsMessage::ERROR,null,500);
        }
    }

}