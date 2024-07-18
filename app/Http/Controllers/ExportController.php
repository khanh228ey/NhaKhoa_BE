<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    //
    public function printInvoicePdf(Request $request)
    {
        $pdf = new Dompdf();
        $html = $request->input('html');
        $pdf->loadHtml($html);
        $pdf->render();
        return $pdf->stream('invoice.pdf');
    }
}
