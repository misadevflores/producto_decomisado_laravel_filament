<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Seizure;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    //
    public function SeizuresRecords($id)
    {

        $seizures = Seizure::where('id', $id)->get();
        $pdf = Pdf::loadView('pdf.example', compact('seizures'));
        return $pdf->stream('productoDecoimisado.pdf'); 
        // return $pdf->stream('archivo.pdf');

        // dd($seizures);
    }
}
