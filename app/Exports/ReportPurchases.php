<?php

namespace App\Exports;

use App\Purchase;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
class ReportPurchases implements FromView
{
    private $dataGlobal;
    public function __construct( $request)
    {
        $this->dataGlobal = $request;
    }
    public function view(): View
    {
        $from = Carbon::parse($this->dataGlobal->from)->format('y-m-d');
        $to = Carbon::parse($this->dataGlobal->to)->format('y-m-d');
        if($this->dataGlobal->today = 'on'){
            $from = Carbon::now()->format('y-m-d');
        $to = Carbon::now()->addDays(1)->format('y-m-d');
        }
        $compras = Purchase::join('users','users.id','=','purchases.user_id')
         ->join('branch_offices','branch_offices.id','=','users.branch_office_id')
         ->whereBetween('purchases.created_at',[$from,$to])
         ->where('branch_offices.id','=',Auth::user()->branch_office_id)->select('purchases.*','branch_offices.name as name_office')->get();
       return view('reports.reportPurchases',compact('compras','from','to'));
    }
   
}
