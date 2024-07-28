<?php

namespace App\Http\Controllers;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Exports\AppointmentExport;
use App\Exports\HistoryExport;
use App\Exports\InvoiceExport;
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
                $startDate = $request->query('begin-date');
                $endDate= $request->query('end-date');
                if (empty($startDate) || empty($endDate)) {
                    return JsonResponse::handle(400, 'Lỗi dữ liệu', null, 400);
                }
                $service = new StatisticsRepository;
                $getService =$service->getService($request);
                $data= $this->exportRepository->exportServiceExcel($getService);
            return Excel::download(new ServicesExport($data), "Thong ke dich vu.xlsx");
        }catch(Exception $e){
                return JsonResponse::handle(500,ConstantsMessage::ERROR,null,500);
        }
    }

    public function exportInvoice(Request $request){
        try {
            $startDate = $request->query('begin-date');
            $endDate= $request->query('end-date');
            if (empty($startDate) || empty($endDate)) {
                return JsonResponse::handle(400, 'Lỗi dữ liệu', null, 400);
            }
            $invoice = new StatisticsRepository;
            $statisticsInvoice =$invoice->getInvoice($request);
            $data= $this->exportRepository->exportInvoiceExcel($statisticsInvoice);
            return Excel::download(new InvoiceExport($data), "Thong ke hoa don");
        }catch(Exception $e){
            return JsonResponse::handle(500,ConstantsMessage::ERROR,null,500);
        }
    }

    public function exportAppointment(Request $request){
        try {
            $startDate = $request->query('begin-date');
            $endDate= $request->query('end-date');
            if (empty($startDate) || empty($endDate)) {
                return JsonResponse::handle(400, 'Hãy truyền ngày bắt đầu', null, 400);
            }
            $invoice = new StatisticsRepository;
            $statisticsInvoice =$invoice->getAppointment($request);
            $excel =  $this->exportRepository->exportAppointmentExcel($statisticsInvoice);
            return Excel::download(new AppointmentExport($excel),"Thong ke lich hen.xlsx");
        }catch(Exception $e){
            return JsonResponse::handle(500,ConstantsMessage::ERROR,null,500);
        }
    }

    public function exportHistory(Request $request){
        try {
            $startDate = $request->query('begin-date');
            $endDate= $request->query('end-date');
            if (empty($startDate) || empty($endDate)) {
                return JsonResponse::handle(400, 'Hãy truyền ngày bắt đầu', null, 400);
            }
            $invoice = new StatisticsRepository;
            $statisticsInvoice =$invoice->getHistory($request);
            $excel =  $this->exportRepository->exportHistoryExcel($statisticsInvoice);
            return Excel::download(new HistoryExport($excel),"Thong ke lich kham.xlsx");
        }catch(Exception $e){
            return JsonResponse::handle(500,ConstantsMessage::ERROR,null,500);
        }
    }

}