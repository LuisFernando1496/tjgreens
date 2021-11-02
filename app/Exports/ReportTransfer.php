<?php

namespace App\Exports;

use App\SendProduct;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
class ReportTransfer implements FromView
{
    private $dataGlobal;
    public function __construct($request)
    {
        $this->dataGlobal = $request;
    }

    public function view(): View
    {
        $send = SendProduct::where('transfer_id',$this->dataGlobal->transfer_id)->get();
        return view('reports.traspacing',compact('send'));
    }
}
