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

    public function printInvoicePdf($id)
    {
        try{
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
            return $pdf->stream('invoice.pdf', ['Attachment' => true]);
        }catch(Exception $e){
            return JsonResponse::handle(500,ConstantsMessage::ERROR,null,500);
        }
        
    }
    // public function printInvoicePdf(Request $request)
    // {
    //     $pdf = new Dompdf();
    //     $html = $request->input('html');
    //     $pdf->loadHtml($html);
    //     $pdf->render();
    //     return $pdf->stream('invoice.pdf');
    // }

}
