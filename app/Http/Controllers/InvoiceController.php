<?php

namespace App\Http\Controllers;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoices;
use App\Repositories\InvoiceRepository;
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
        return JsonResponse::handle(200, ConstantsMessage::Add, $invoices, 200);
    }

    Public function updateInvoice(Request $request,$id){
        try{
            $invoices = Invoices::findOrFail($id);
            $invoices = $this->invoiceRepository->update($request,$invoices);
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

    Public function printInvoice(){
        
    }
}
