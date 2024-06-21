<?php

namespace App\Http\Controllers;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoices;
use App\Repositories\InvoiceRepository;
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
        return JsonResponse::handle(201, ConstantsMessage::SUCCESS, $invoices, 201);
    }

    Public function updateInvoice(Request $request,$id){
        $invoices = $this->invoiceRepository->update($request->all(),$id);
        if ($invoices == false) {
                return JsonResponse::error(401,ConstantsMessage::ERROR,401);
        }
        return JsonResponse::handle(201, ConstantsMessage::SUCCESS, $invoices, 201);
    }

    Public function getInvoice(Request $request){
        $perPage = $request->get('limit', 10);
        $page = $request->get('page'); 
        // $name = $request->get('name');
        $query = Invoices::with(['History','user'])->orderBy('created_at','DESC');
        // if ($name) {
        //     $query->where('name', 'LIKE', "%{$name}%");
        // }
        if (!is_null($page)) {
            $data = $query->paginate($perPage, ['*'], 'page', $page);
            $invoices = $data->items();
        } else {
            $invoices = $query->get();
        }   
        $result = $invoices->map(function ($item) {
            return [
                'id' => $item->id,
                'total_price' => $item->total_price,
                'method_payment' => $item->method_payment,
                'customer' => $item->history->customer->name,
                'status' => $item->status,
                'user' => $item->user->name,

            ];
        });
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
}
