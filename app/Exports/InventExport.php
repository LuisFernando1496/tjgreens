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

class InventExport implements FromView
{

    
    
    public function view(): View
    {
        $d = new DateTime('NOW',new DateTimeZone('America/Mexico_City')); 
        $date =  $d->format('Y-m-d H:m:s');


        return view('reports.reportInvent',[
            "branchOffice" => BranchOffice::All(),
            "products" => Product::All(),
            "user" => Auth::user(),
            "date" =>$date,
        ]);
    }
}