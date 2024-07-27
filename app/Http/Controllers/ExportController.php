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

    
    public function exportService(Request $request){
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

    public function exportInvoice(Request $request){
        try {
            $startDate = $request->query('beginDate');
            $endDate= $request->query('endDate');
            if (empty($startDate) || empty($endDate)) {
                return JsonResponse::handle(400, 'Lỗi dữ liệu', null, 400);
            }
            $invoice = new StatisticsRepository;
            $statisticsInvoice =$invoice->getInvoice($request);

            return $this->exportRepository->exportInvoiceExcel($statisticsInvoice);
        }catch(Exception $e){
            return JsonResponse::handle(500,ConstantsMessage::ERROR,null,500);
        }
    }

}