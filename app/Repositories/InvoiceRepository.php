<?php
namespace App\Repositories;

use App\Models\History;
use App\Models\Invoices;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class InvoiceRepository{

    public function addInvoice($data){
        $history = History::with('services')->find($data['history_id']);
        $invoice = new Invoices();
        $invoice->history_id = $data['history_id'];
        $invoice->user_id = Auth::user()->id;
        $invoice->method_payment = 0;
        $invoice->status = 0;
        $invoice->created_at = Carbon::now('Asia/Ho_Chi_Minh');
        $total = 0;
        foreach($history->services as $service){
            $quantity = $service->pivot->quantity; 
            $price = $service->pivot->price; 
            $total += $quantity * $price;
        }
        $invoice->total_price = $total;
        if($invoice->save()){
            return $invoice;
        }
        return false;
    }

    Public function update($data){
        $invoice = Invoices::find($data['id']);
        $invoice->method_payment = $data['method_payment'];
        $invoice->status = $data['status'];
        $invoice->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
        if($invoice->save()){
            return $invoice;
        }
        return false;
    }
}