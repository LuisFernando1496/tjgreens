<?php

namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Http\Request;
use App\User;
use App\BranchOffice;
use App\Sale;

use App\CashClosing;
use App\Product;
use DB;
use PDF;
use Excel;
use DateTime;
use DateTimeZone;
use App\Box;
use App\ProductInSale;
use Illuminate\Support\Facades\Auth;

class InventByBranchOfficeIdExport implements FromView
{

    private $dataGlobal;
    public function __construct(Request $request)
    {
        $this->dataGlobal = $request;
    }
    
    public function view(): View
    {
        $d = new DateTime('NOW',new DateTimeZone('America/Mexico_City')); 
        $date =  $d->format('Y-m-d H:m:s');
        $bo = BranchOffice::find($this->dataGlobal->branchOfficeId);

        return view('reports.reportInventByBranchOfficeId',[
            "branchOffice" => $bo,
            "products" => Product::All(),
            "user" => Auth::user(),
            "date" =>$date,
        ]);
    }
}