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

class UserExport implements FromView
{
    private $dataGlobal;
    public function __construct(Request $request)
    {
        $this->dataGlobal = $request;
    }
    
    public function view(): View
    {

        $from =  $this->dataGlobal->from;
        $to = $this->dataGlobal->to;
        $showFrom = $from;
        if($to == $from){
            $to = date('Y-m-d', strtotime('+1 day', strtotime($this->dataGlobal->to)));
        }
        $tempUser = User::find($this->dataGlobal->user_id);
            $data = Sale::join("cash_closings" ,"cash_closings.id", "=" ,"sales.cash_closing_id")
            ->leftjoin("expenses", "expenses.cash_closing_id", "=" ,"cash_closings.id")
            ->select(DB::raw(" SUM(sales.total_cost) as costo,
            cash_closings.initial_cash as caja_inicial,
            cash_closings.end_cash as caja_final,
            SUM(amount_discount) as descuento,
            SUM(cart_subtotal) as subtotal,
            sum(cart_total) as total,
            sum(expenses.price) as expense"))
            ->whereBetween('sales.created_at',[$from, $to])
            ->where("sales.user_id" , "=" , $tempUser->id)
            ->where("cash_closings.status", "=", true) //cambiar a true
            ->where("sales.status",  "=", true)
            ->groupBy("sales.payment_type")
            ->get();


            $p = ProductInSale::join("sales" ,"sales.id", "=" ,"product_in_sales.sale_id")
            ->join("users","users.id","=","sales.user_id")
            ->join("products","products.id","=","product_in_sales.product_id")
            ->join("brands","brands.id","=","products.brand_id")
            ->join("categories","categories.id","=","products.category_id")
            ->select("products.name as product_name",
            "categories.name as category",
            "brands.name as brand",
            "product_in_sales.quantity as quantity",
            "products.cost as cost",
            "product_in_sales.sale_price as sale_price",
            "product_in_sales.discount as amount_discount",
            "product_in_sales.total as total",
            "users.name as seller",
            "users.last_name as seller_lastName",
            "product_in_sales.created_at as date",
            "sales.branch_office_id"
            )
            ->where("sales.user_id","=",$tempUser->id)
            ->whereBetween('sales.created_at',[$from, $to])
            ->where("sales.status",  "=", true)
            ->get();

            $b = DB::table('branch_offices')
            ->distinct()
            ->where("branch_offices.id", "=", $tempUser->branch_office_id) 
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


        return view('reports.reportUser',["cash" => $d0,
        "card" => $d1,
        "user" => Auth::user(),
        "date" =>$date,
        "products"=>$p,
        "branchOffice" => $b,
        "worker" => $tempUser,
        "to" => $to,
        "from" =>$showFrom]);

    }
}