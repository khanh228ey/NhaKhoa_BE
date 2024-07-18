<?php
namespace App\Repositories;

use App\Models\History;
use App\Models\Invoices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceRepository{

    public function addInvoice($id){
        $history = History::with('services')->find($id);
        $invoice = new Invoices();
        $invoice->history_id = $id;
        $invoice->method_payment = 0;
        $invoice->status = 0;
        $invoice->created_at = Carbon::now('Asia/Ho_Chi_Minh');
        $total = 0;
        if (!$history->services->isEmpty()) {
            foreach($history->services as $service){
                $quantity = $service->pivot->quantity; 
                $price = $service->pivot->price;
                $total += $quantity * $price ;
            }
        } else {
            $total = 200000;
        }
        $invoice->total_price = $total;
        if($invoice->save()){
            return $invoice;
        }
        return false;
    }

    public function update(Request $request, $invoice){
        $data = $request->only(['method_payment', 'status']);
        if (!$invoice->user_id) {
            $invoice->user_id = 'DH00002'; 
            $invoice->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
        }
        $invoice->fill($data);
        $invoice->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
        if ($invoice->save()) {
            return $invoice;
        }
    
        return false;
    }
}