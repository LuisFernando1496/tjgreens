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

class CutBoxExport implements FromView
{
    private $dataGlobal;
    public function __construct(Request $request)
    {
        $this->dataGlobal = $request;
    }
    
    public function view(): View
    {
        $tempCashClosing = CashClosing::find($this->dataGlobal->cashClosingId);
            $data = Sale::join("cash_closings" ,"cash_closings.id", "=" ,"sales.cash_closing_id")
            ->leftjoin("expenses", "expenses.cash_closing_id", "=" ,"cash_closings.id")
            ->select(DB::raw(" SUM(sales.total_cost) as costo,
            cash_closings.initial_cash as caja_inicial,
            cash_closings.end_cash as caja_final,
            SUM(amount_discount) as descuento,
            SUM(cart_subtotal) as subtotal,
            sum(cart_total) as total,
            sales.payment_type,
            sum(expenses.price) as expense"))
            ->where("sales.branch_office_id", "=", $tempCashClosing->branch_office_id)
            ->where("sales.cash_closing_id" , "=" , $tempCashClosing->id)
            ->where("cash_closings.status", "=", true) //cambiar a true
            ->where("sales.status",  "=", true)
            ->groupBy("sales.payment_type")
            ->get();

            $p = ProductInSale::join("sales" ,"sales.id", "=" ,"product_in_sales.sale_id")
            ->select("*")
            ->where("sales.cash_closing_id" , "=" , $tempCashClosing->id)
            ->get();

            $b = DB::table('branch_offices')
            ->distinct()
            ->where("branch_offices.id", "=", $tempCashClosing->branch_office_id)
            ->get();
            
            $d = new DateTime('NOW',new DateTimeZone('America/Mexico_City')); 
            $date =  $d->format('Y-m-d H:m:s');
            //$pin = ProductsInSale::where();
            $d0 = new Request();
            $d1 = new Request();
            $d0["subtotal"] = 0;
            $d0["total"] = 0;
            $d0["descuento"] = 0;
            $d0["costo"] = 0;
            $d1["subtotal"] = 0;
            $d1["total"] = 0;
            $d1["descuento"] = 0;
            $d1["costo"] = 0;
            $d1["expense"] = 0;
            try {
                $d0 = $data[0];
            } catch (\Throwable $th) {
                //throw $th;
            }

            try {
                $d1 = $data[1];
            } catch (\Throwable $th) {
                //throw $th;
            }
            if(count($data) == 1){
                    $d1["subtotal"] = $d0["subtotal"];
                    $d1["total"] = $d0["total"];
                    $d0["subtotal"] = 0;
                    $d0["total"] = 0;
            }

        return view('reports.reportCashClosing',["cash" => $d0,
        "card" => $d1,
        "user" => Auth::user(),
        "date" =>$date,
        "products"=>$p,
        "branchOffice" => $b,
        "worker" => $tempCashClosing->user]);

    }
}