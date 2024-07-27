<?php

namespace App\Http\Controllers;
use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoices;
use App\Repositories\InvoiceRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InvoiceController extends Controller
{
    //
    protected $invoiceRepository;
    public function __construct(InvoiceRepository $invoiceRepository)
    {
        $this->invoiceRepository = $invoiceRepository; 
      
    }
    Public function createInvoice(Request $request){
        $invoices = $this->invoiceRepository->addInvoice($request->all());
        if ($invoices == false) {
                return JsonResponse::error(401,ConstantsMessage::ERROR,401);
        }
        $invoices = new InvoiceResource($invoices);
        return JsonResponse::handle(200, ConstantsMessage::Add, $invoices, 200);
    }

    Public function updateInvoice(Request $request,$id){
        try{
            $invoices = Invoices::findOrFail($id);
            $invoices = $this->invoiceRepository->update($request,$invoices);
            $invoices = new InvoiceResource($invoices);
            return JsonResponse::handle(200, ConstantsMessage::Update, $invoices, 200);
        }catch(ModelNotFoundException $e){
            return JsonResponse::handle(200, "Không tìm thấy hóa đơn", null, 200);
        }catch(Exception $e){
            return JsonResponse::handle(200, ConstantsMessage::ERROR, null, 200);
        }
        
    }

    Public function getInvoice(Request $request){
        $perPage = $request->get('limit', 10);
        $page = $request->get('page'); 
        $query = Invoices::with(['History','user'])->orderBy('created_at','DESC');
        if (!is_null($page)) {
            $data = $query->paginate($perPage, ['*'], 'page', $page);
            $invoices = collect($data->items());
        } else {
            $invoices = $query->get();
        }   
        $result = InvoiceResource::collection($invoices);
        return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $result, 200);
    }
    Public function findById($id){
        try {
            $invoice = Invoices::with(['History','user'])->findOrFail($id); 
            $result = new InvoiceResource($invoice);
            return JsonResponse::handle(200, ConstantsMessage::SUCCESS,  $result, 200);
        } catch (ModelNotFoundException $e) {
            return JsonResponse::handle(404, ConstantsMessage::Not_Found, null, 404);
        }
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
            $response = new Response($pdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="invoice.pdf"',
                'Access-Control-Allow-Origin' => env('COR_FE'),
                'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, X-Auth-Token, Origin, Authorization',
            ]);
    
            return $response;
        }catch(Exception $e){
            return JsonResponse::handle(500,ConstantsMessage::ERROR,null,500);
        }
        
    }
    
}
