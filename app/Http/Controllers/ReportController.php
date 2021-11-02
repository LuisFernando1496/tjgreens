<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\BranchOffice;
use App\Sale;

use App\CashClosing;
use App\Product;
use DB;
use PDF;
use App\Exports\GeneralExport;
use App\Exports\BranchOfficeExport;
use App\Exports\UserExport;
use App\Exports\CutBoxExport;
use App\Exports\InventExport;
use App\Exports\InventByBranchOfficeIdExport;
use Excel;
use DateTime;
use DateTimeZone;
use App\Box;
use App\ProductInSale;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(){

        $user = Auth::user();

        if ($user->rol_id == 1) {
            $offices = BranchOffice::all();
            return view('reports.index', ['offices'=>$offices]);
        }elseif ($user->rol_id == 2) {
            $offices = BranchOffice::where('id',$user->branchOffice->id)->get();
            return view('reports.index', ['offices'=>$offices]);
        }else{
            $offices = BranchOffice::where('id',$user->branchOffice->id)->get();
            return view('reports.index', ['offices'=>$offices]);
        }
    }

    public function employeeByOffice($office_id){
        $user = Auth::user();

        if ($user->rol_id == 1) {
            $employees = User::where('status',true)->where('branch_office_id',$office_id)->get();
            return response()->json($employees);
        }elseif ($user->rol_id == 2) {
            $employees = User::where('status',true)->where('branch_office_id',$office_id)->where('rol_id',3)->get();
            return response()->json($user);
        }else{
            $employees = User::where('status',true)->where('branch_office_id',$office_id)->where('id',$user->id)->get();
            return response()->json($employees);
        }
    }

    public function generalReport(Request $request){
        //PEDIR FECHAS
        $from =  $request->from;
        $to = $request->to;

        $showFrom = $from;
        if($to == $from){
            $to = date('Y-m-d', strtotime('+1 day', strtotime($request->to)));
        }


            $data = Sale::join("cash_closings" ,"cash_closings.id", "=" ,"sales.cash_closing_id")
            ->leftjoin("expenses", "expenses.cash_closing_id", "=" ,"cash_closings.id")
            ->select(DB::raw("SUM(sales.total_cost) as costo,
            cash_closings.initial_cash as caja_inicial,
            cash_closings.end_cash as caja_final,
            SUM(amount_discount) as descuento,
            SUM(cart_subtotal) as subtotal,
            sum(cart_total) as total,
            sum(expenses.price) as expense"))
            ->whereBetween('sales.created_at',[$from, $to])
            ->where("sales.status",  "=", true)
            ->where("cash_closings.status", "=", true) //cambiar a true
            ->groupBy("sales.payment_type")
            ->get();


            $dataSub = Sale::join("cash_closings" ,"cash_closings.id", "=" ,"sales.cash_closing_id")
            ->leftjoin("expenses", "expenses.cash_closing_id", "=" ,"cash_closings.id")
            ->select(DB::raw(" SUM(sales.total_cost) as costo,
            cash_closings.initial_cash as caja_inicial,
            cash_closings.end_cash as caja_final,
            SUM(amount_discount) as descuento,
            SUM(cart_subtotal) as subtotal,
            sum(cart_total) as total,
            sales.payment_type as tipo_de_pago,
            sales.branch_office_id,
            sum(expenses.price) as expense"))
            ->whereBetween('sales.created_at',[$from, $to])
            ->where("cash_closings.status", "=", true) //cambiar a true
            ->where("sales.status",  "=", true)
            ->groupBy("sales.payment_type")
            ->groupBy("sales.branch_office_id")
            ->orderBy("sales.branch_office_id")
            ->orderBy("sales.payment_type")
            ->get();
            


            $array = array();
            $tempData = array();
            $flag = true;
            $last = -1;
            foreach ($dataSub as $key => $value) {
                if($value->branch_office_id != $last && $value->payment_type == 0){
                    $tempData["costo_cash"] = $value->costo;
                    $tempData["caja_inicial_cash"] = $value->caja_inicial;
                    $tempData["caja_final_cash"] = $value->caja_final;
                    $tempData["descuento_cash"] = $value->descuento;
                    $tempData["subtotal_cash"] = $value->subtotal;
                    $tempData["total_cash"] = $value->total;
                    $tempData["tipo_de_pago_cash"] = $value->tipo_de_pago;
                    $tempData["branch_office_id_cash"] = $value->branch_office_id;
                    $tempData["expense_cash"] = $value->expense;


                    $tempData["costo_card"] = 0;
                    $tempData["caja_inicial_card"] = 0;
                    $tempData["caja_final_card"] = 0;
                    $tempData["descuento_card"] = 0;
                    $tempData["subtotal_card"] = 0;
                    $tempData["total_card"] = 0;
                    $tempData["tipo_de_pago_card"] = 0;
                    $tempData["branch_office_id_card"] = 0;
                    $tempData["expense_card"] = 0;


                    $last = $value->branch_office_id;
                    $flag =true;
                }else{
                    $tempData["costo_card"] = $value->costo;
                    $tempData["caja_inicial_card"] = $value->caja_inicia;
                    $tempData["caja_final_card"] = $value->caja_final;
                    $tempData["descuento_card"] = $value->descuento;
                    $tempData["subtotal_card"] = $value->subtotal;
                    $tempData["total_card"] = $value->total;
                    $tempData["tipo_de_pago_card"] = $value->tipo_de_pago;
                    $tempData["branch_office_id_card"] = $value->branch_office_id;
                    $tempData["expense_card"] = $value->expense;
                    $flag =false;
                    $last = $value->branch_office_id;
                    $array[$key] = $tempData;
                    $tempData = array();
                }
                if(count($dataSub) < $key + 1){
                    if($flag && $value[$key]->branch_office_id != $last ){
                        $array[$key] = $tempData;
                        $tempData = array();
                    }
                }else{
                    if($flag){
                        $array[$key] = $tempData;
                        $tempData = array();
                    }
                }
            }




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
            ->where("sales.status",  "=", true)
            ->whereBetween('sales.created_at',[$from, $to])
            ->get();



            $ap =ProductInSale::join("sales" ,"sales.id", "=" ,"product_in_sales.sale_id")
            ->join("users","users.id","=","sales.user_id")
            ->join("products","products.id","=","product_in_sales.product_id")
            ->join("brands","brands.id","=","products.brand_id")
            ->join("categories","categories.id","=","products.category_id")
            ->select(DB::raw("product_in_sales.product_id as product_id,
            products.name as product_name,
            brands.name as brand,
            categories.name as category,
            sum(product_in_sales.quantity) as quantity,
            products.cost as cost,
            product_in_sales.sale_price as sale_price,
            product_in_sales.discount as discount,
            sum(product_in_sales.total_cost) as total_cost,
            sum(product_in_sales.total) as total,
            users.name as seller,
            users.last_name as seller_lastName,
            product_in_sales.created_at as date"))
            ->whereBetween('sales.created_at',[$from, $to])
            ->where("sales.status",  "=", true)
            ->groupBy("product_in_sales.product_id","product_in_sales.sale_price")
            ->get();



            $b = DB::table('branch_offices')
            ->distinct()
            ->get();
            
            $d = new DateTime('NOW',new DateTimeZone('America/Mexico_City')); 
            $date =  $d->format('Y-m-d H:m:s');
            

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
                if($data[0]->payment_type == 1){
                    $d1["total"] = $d0["total"];
                    $d0["total"] = 0;

                }
            } catch (\Throwable $th) {
                //throw $th;
            }

            try {
                $d1 = $data[1];
            } catch (\Throwable $th) {
                //throw $th;
            }

            // $d0Sub = new Request();
            // $d1Sub = new Request();
            // $d0Sub["subtotal"] = 0;
            // $d0Sub["total"] = 0;
            // $d0Sub["descuento"] = 0;
            // $d0Sub["costo"] = 0;
            // $d1Sub["subtotal"] = 0;
            // $d1Sub["total"] = 0;
            // $d1Sub["descuento"] = 0;
            // $d1Sub["costo"] = 0;
            // $d1Sub["expense"] = 0;
            // try {
            //     $d0Sub = $dataSub[0];
            // } catch (\Throwable $th) {
            //     //throw $th;
            // }

            // try {
            //     $d1Sub = $dataSub[1];
            // } catch (\Throwable $th) {
            //     //throw $th;
            // }

            return view('reports.reportGeneral',["cash" => $d0,
            "card" => $d1,
            "user" => Auth::user(),
            "date" =>$date,
            "products"=>$p,
            "branchOffice" => $b,
            "otherData" => $array,
            "to" => $to,
            "from" =>$showFrom,
            "ap" =>$ap]);

    }

    public function generalReportDownload(Request $request){
        //PEDIR FECHAS
        $from =  $request->from;
        $to = $request->to;

        $showFrom = $from;
        if($to == $from){
            $to = date('Y-m-d', strtotime('+1 day', strtotime($request->to)));
        }

            $data = Sale::join("cash_closings" ,"cash_closings.id", "=" ,"sales.cash_closing_id")
            ->leftjoin("expenses", "expenses.cash_closing_id", "=" ,"cash_closings.id")
            ->select(DB::raw("SUM(sales.total_cost) as costo,
            cash_closings.initial_cash as caja_inicial,
            cash_closings.end_cash as caja_final,
            SUM(amount_discount) as descuento,
            SUM(cart_subtotal) as subtotal,
            sum(cart_total) as total,
            sum(expenses.price) as expense"))
            ->whereBetween('sales.created_at',[$from, $to])
            ->where("cash_closings.status", "=", true) //cambiar a true
            ->where("sales.status",  "=", true)
            ->groupBy("sales.payment_type")
            ->get();


            $dataSub = Sale::join("cash_closings" ,"cash_closings.id", "=" ,"sales.cash_closing_id")
            ->join("expenses", "expenses.cash_closing_id", "=" ,"cash_closings.id")
            ->select(DB::raw(" SUM(sales.total_cost) as costo,
            cash_closings.initial_cash as caja_inicial,
            cash_closings.end_cash as caja_final,
            SUM(amount_discount) as descuento,
            SUM(cart_subtotal) as subtotal,
            sum(cart_total) as total,
            sales.payment_type as tipo_de_pago,
            sales.branch_office_id,
            sum(expenses.price) as expense"))
            ->whereBetween('sales.created_at',[$from, $to])
            ->where("sales.status",  "=", true)
            ->where("cash_closings.status", "=", true) //cambiar a true
            ->groupBy("sales.payment_type")
            ->groupBy("sales.branch_office_id")
            ->orderBy("sales.branch_office_id")
            ->orderBy("sales.payment_type")
            ->get();
            

            $array = array();
            $tempData = array();
            $flag = true;
            $last = -1;
            foreach ($dataSub as $key => $value) {
                if($value->branch_office_id != $last && $value->payment_type == 0){
                    $tempData["costo_cash"] = $value->costo;
                    $tempData["caja_inicial_cash"] = $value->caja_inicial;
                    $tempData["caja_final_cash"] = $value->caja_final;
                    $tempData["descuento_cash"] = $value->descuento;
                    $tempData["subtotal_cash"] = $value->subtotal;
                    $tempData["total_cash"] = $value->total;
                    $tempData["tipo_de_pago_cash"] = $value->tipo_de_pago;
                    $tempData["branch_office_id_cash"] = $value->branch_office_id;
                    $tempData["expense_cash"] = $value->expense;


                    $tempData["costo_card"] = 0;
                    $tempData["caja_inicial_card"] = 0;
                    $tempData["caja_final_card"] = 0;
                    $tempData["descuento_card"] = 0;
                    $tempData["subtotal_card"] = 0;
                    $tempData["total_card"] = 0;
                    $tempData["tipo_de_pago_card"] = 0;
                    $tempData["branch_office_id_card"] = 0;
                    $tempData["expense_card"] = 0;



                    $last = $value->branch_office_id;
                    $flag =true;
                }else{
                    $tempData["costo_card"] = $value->costo;
                    $tempData["caja_inicial_card"] = $value->caja_inicia;
                    $tempData["caja_final_card"] = $value->caja_final;
                    $tempData["descuento_card"] = $value->descuento;
                    $tempData["subtotal_card"] = $value->subtotal;
                    $tempData["total_card"] = $value->total;
                    $tempData["tipo_de_pago_card"] = $value->tipo_de_pago;
                    $tempData["branch_office_id_card"] = $value->branch_office_id;
                    $tempData["expense_card"] = $value->expense;
                    $flag =false;
                    $last = $value->branch_office_id;
                    $array[$key] = $tempData;
                    $tempData = array();
                }
                if(count($dataSub) < $key + 1){
                    if($flag && $value[$key]->branch_office_id !=$last ){
                        $array[$key] = $tempData;
                        $tempData = array();
                    }
                }else{
                    if($flag){
                        $array[$key] = $tempData;
                        $tempData = array();
                    }
                }
            }


            $p = ProductInSale::join("sales" ,"sales.id", "=" ,"product_in_sales.sale_id")
            ->join("users","users.id","=","sales.user_id")
            ->join("products","products.id","=","product_in_sales.id")
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
            ->where("sales.status",  "=", true)
            ->whereBetween('sales.created_at',[$from, $to])
            ->get();


            $ap =ProductInSale::join("sales" ,"sales.id", "=" ,"product_in_sales.sale_id")
            ->join("users","users.id","=","sales.user_id")
            ->join("products","products.id","=","product_in_sales.product_id")
            ->join("brands","brands.id","=","products.brand_id")
            ->join("categories","categories.id","=","products.category_id")
            ->select(DB::raw("product_in_sales.product_id as product_id,
            products.name as product_name,
            brands.name as brand,
            categories.name as category,
            sum(product_in_sales.quantity) as quantity,
            products.cost as cost,
            product_in_sales.sale_price as sale_price,
            product_in_sales.discount as discount,
            sum(product_in_sales.total_cost) as total_cost,
            sum(product_in_sales.total) as total,
            users.name as seller,
            users.last_name as seller_lastName,
            product_in_sales.created_at as date"))
            ->whereBetween('sales.created_at',[$from, $to])
            ->where("sales.status",  "=", true)
            ->groupBy("product_in_sales.product_id","product_in_sales.sale_price")
            ->get();

            $b = DB::table('branch_offices')
            ->distinct()
            ->get();

            
            $d = new DateTime('NOW',new DateTimeZone('America/Mexico_City')); 
            $date =  $d->format('Y-m-d H:m:s');
            

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
                if($data[0]->payment_type == 1){
                    $d1["total"] = $d0["total"];
                    $d0["total"] = 0;

                }
            } catch (\Throwable $th) {
                //throw $th;
            }

            try {
                $d1 = $data[1];
            } catch (\Throwable $th) {
                //throw $th;
            }


            view()->share(["cash" => $d0,
            "card" => $d1,
            "user" => Auth::user(),
            "date" =>$date,
            "products"=>$p,
            "branchOffice" => $b,
            "otherData" => $array,
            "to" => $to,
            "from" =>$showFrom,
            "ap"=>$ap]);

            $pdf = PDF::loadView('reports.reportGeneral');
      
            // download PDF file with download method
            return $pdf->download('reporteGeneral.pdf');
            
            //return view('reports.reportGeneral');
            

    }

    public function generalReportDownloadExcel(Request $request){
        return Excel::download(new GeneralExport($request), 'reporteGeneral.xlsx');
    }

    public function branchOfficeReport(Request $request){
        //recibir el id de la sucursal

        $from =  $request->from;
        $to = $request->to;

        $showFrom = $from;
        if($to == $from){
            $to = date('Y-m-d', strtotime('+1 day', strtotime($request->to)));
        }
        $tempId = $request->branchOfficeId;
            $data = Sale::join("cash_closings" ,"cash_closings.id", "=" ,"sales.cash_closing_id")
            ->leftjoin("expenses", "expenses.cash_closing_id", "=" ,"cash_closings.id")
            ->select(DB::raw("SUM(sales.total_cost) as costo,
            cash_closings.initial_cash as caja_inicial,
            cash_closings.end_cash as caja_final,
            SUM(amount_discount) as descuento,
            SUM(cart_subtotal) as subtotal,
            sum(cart_total) as total,
            sum(expenses.price) as expense"))
            ->whereBetween('sales.created_at',[$from, $to])
            ->where("sales.branch_office_id" , "=" , $tempId)
            ->where("sales.status",  "=", true)
            ->where("cash_closings.status", "=", true) //cambiar a true
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
            ->where("sales.status",  "=", true)
            ->whereBetween('sales.created_at',[$from, $to])
            ->get();
            

            $b = DB::table('branch_offices')
            ->distinct()
            ->where("branch_offices.id", "=", $tempId) 
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
                if($data[0]->payment_type == 1){
                    $d1["total"] = $d0["total"];
                    $d0["total"] = 0;

                }
            } catch (\Throwable $th) {
                //throw $th;
            }

            try {
                $d1 = $data[1];
            } catch (\Throwable $th) {
                //throw $th;
            }

            return view('reports.reportBranchOffice',["cash" => $d0,
            "card" => $d1,
            "user" => Auth::user(),
            "date" =>$date,
            "products"=>$p,
            "branchOffice" => $b,
            "to" => $to,
            "from" =>$showFrom]);

            
            
    }

    public function branchOfficeReportDownload(Request $request){
        //recibir el id de la sucursal
        $from =  $request->from;
        $to = $request->to;

        $showFrom = $from;
        if($to == $from){
            $to = date('Y-m-d', strtotime('+1 day', strtotime($request->to)));
        }

        $tempId = $request->branchOfficeId;
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
            ->where("sales.branch_office_id" , "=" , $tempId)
            ->where("sales.status",  "=", true)
            ->where("cash_closings.status", "=", true) //cambiar a true
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
            ->where("sales.status",  "=", true)
            ->where("sales.branch_office_id","=",$tempId)
            ->whereBetween('sales.created_at',[$from, $to])
            ->get();

            $b = DB::table('branch_offices')
            ->distinct()
            ->where("branch_offices.id", "=", $tempId) 
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
                if($data[0]->payment_type == 1){
                    $d1["total"] = $d0["total"];
                    $d0["total"] = 0;

                }
            } catch (\Throwable $th) {
                //throw $th;
            }

            try {
                $d1 = $data[1];
            } catch (\Throwable $th) {
                //throw $th;
            }

            view()->share(["cash" => $d0,
            "card" => $d1,
            "user" => Auth::user(),
            "date" =>$date,
            "products"=>$p,
            "branchOffice" => $b,
            "to" => $to,
            "from" =>$showFrom]);

            $pdf = PDF::loadView('reports.reportBranchOffice');
      
            // download PDF file with download method
            return $pdf->download('reporte_por_sucursal.pdf');
            
    }

    public function branchOfficeReportDownloadExcel(Request $request){
        return Excel::download(new BranchOfficeExport($request), 'reporte_por_sucursal.xlsx');
    }

    public function userReport(Request $request){
        //recibir el id de la sucursal
        $from =  $request->from;
        $to = $request->to;

        $showFrom = $from;
        if($to == $from){
            $to = date('Y-m-d', strtotime('+1 day', strtotime($request->to)));
        }

        $tempUser = User::find($request->user_id);
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
            ->where("sales.status",  "=", true)
            ->where("cash_closings.status", "=", true) //cambiar a true
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
            ->where("sales.status",  "=", true)
            ->where("sales.user_id","=",$request->user_id)
            ->whereBetween('sales.created_at',[$from, $to])
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
                if($data[0]->payment_type == 1){
                    $d1["total"] = $d0["total"];
                    $d0["total"] = 0;

                }
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

    public function userReportDownload(Request $request){
        //recibir el id de la sucursal
        $from =  $request->from;
        $to = $request->to;

        $showFrom = $from;
        if($to == $from){
            $to = date('Y-m-d', strtotime('+1 day', strtotime($request->to)));
        }
        $tempUser = User::find($request->user_id);
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
            ->where("sales.status",  "=", true)
            ->where("cash_closings.status", "=", true) //cambiar a true
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
            ->where("sales.status",  "=", true)
            ->where("sales.user_id","=",$request->user_id)
            ->whereBetween('sales.created_at',[$from, $to])
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
                if($data[0]->payment_type == 1){
                    $d1["total"] = $d0["total"];
                    $d0["total"] = 0;

                }
            } catch (\Throwable $th) {
                //throw $th;
            }

            try {
                $d1 = $data[1];
            } catch (\Throwable $th) {
                //throw $th;
            }


            
            view()->share(["cash" => $d0,
            "card" => $d1,
            "user" => Auth::user(),
            "date" =>$date,
            "products"=>$p,
            "branchOffice" => $b,
            "worker" => $tempUser,
            "to" => $to,
            "from" =>$showFrom]);

            $pdf = PDF::loadView('reports.reportUser');
      
            // download PDF file with download method
            return $pdf->download('reporte_por_usuario.pdf');
            
    }

    public function userReportDownloadExcel(Request $request){
        return Excel::download(new UserExport($request), 'reporte_por_usuario.xlsx');
    }

    public function CutBox(Request $request){ //:v
        //recibir el id de la caja
        $tempCashClosing = CashClosing::find($request->cashClosingId);
            $data = Sale::join("cash_closings" ,"cash_closings.id", "=" ,"sales.cash_closing_id")
            ->leftjoin("expenses", "expenses.cash_closing_id", "=" ,"cash_closings.id")
            ->select(DB::raw(" SUM(sales.total_cost) as costo,
            cash_closings.initial_cash as caja_inicial,
            cash_closings.end_cash as caja_final,
            SUM(amount_discount) as descuento,
            SUM(cart_subtotal) as subtotal,
            sales.payment_type,
            sum(cart_total) as total,
            sum(expenses.price) as expense"))
            ->where("sales.branch_office_id", "=", $tempCashClosing->branch_office_id)
            ->where("sales.cash_closing_id" , "=" , $tempCashClosing->id)
            ->where("cash_closings.status", "=", true) //cambiar a true
            ->where("sales.status",  "=", true)
            ->groupBy("sales.payment_type")
            ->get();


            $p = ProductInSale::join("sales" ,"sales.id", "=" ,"product_in_sales.sale_id")
            ->select("*")
            ->where("sales.status",  "=", true)
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
                if($data[0]->payment_type == 1){
                    $d1["total"] = $d0["total"];
                    $d0["total"] = 0;

                }
            } catch (\Throwable $th) {
                //throw $th;
            }

            try {
                $d1 = $data[1];
            } catch (\Throwable $th) {
                //throw $th;
            }
            //revisar --------------------

            // if(count($data) == 1){
            //         $d1["subtotal"] = $d0["subtotal"];
            //         $d1["total"] = $d0["total"];
            //         $d0["subtotal"] = 0;
            //         $d0["total"] = 0;
            // }
            

            return view('reports.reportCashClosing',["cash" => $d0,
            "card" => $d1,
            "user" => Auth::user(),
            "date" =>$date,
            "products"=>$p,
            "branchOffice" => $b,
            "worker" => $tempCashClosing->user]);
            
    }


    public function CutBoxDownload(Request $request){ //:v
        //recibir el id de la caja
        $tempCashClosing = CashClosing::find($request->cashClosingId);
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
            ->where("sales.status",  "=", true)
            ->where("cash_closings.status", "=", true) //cambiar a true
            ->groupBy("sales.payment_type")
            ->get();

            $p = ProductInSale::join("sales" ,"sales.id", "=" ,"product_in_sales.sale_id")
            ->select("*")
            ->where("sales.status",  "=", true)
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


            view()->share(["cash" => $d0,
            "card" => $d1,
            "user" => Auth::user(),
            "date" =>$date,
            "products"=>$p,
            "branchOffice" => $b,
            "worker" => $tempCashClosing->user]);

            $pdf = PDF::loadView('reports.reportCashClosing');
      
            // download PDF file with download method
            return $pdf->download('reporte_corte_de_caja.pdf');
            
    }

    public function CutBoxDownloadExcel(Request $request){ //:v
        return Excel::download(new CutBoxExport($request), 'reporte_corte_de_caja.xlsx');
    }

    public function invent(){
        $d = new DateTime('NOW',new DateTimeZone('America/Mexico_City')); 
        $date =  $d->format('Y-m-d H:m:s');
        return view("reports.reportInvent",[
            "branchOffice" => BranchOffice::All(),
            "products" => Product::All(),
            "user" => Auth::user(),
            "date" =>$date,
        ]);
    }

    public function inventDownload(){
        $d = new DateTime('NOW',new DateTimeZone('America/Mexico_City')); 
        $date =  $d->format('Y-m-d H:m:s');

        view()->share([
            "branchOffice" => BranchOffice::All(),
            "products" => Product::All(),
            "user" => Auth::user(),
            "date" =>$date,
        ]);

        $pdf = PDF::loadView("reports.reportInvent");
  
        // download PDF file with download method
        return $pdf->download('Inventario.pdf');
    }

    public function inventDownloadExcel(){
        return Excel::download(new InventExport, 'Inventario.xlsx');
    }


    public function inventByBranchOfficeId(Request $request){
        $d = new DateTime('NOW',new DateTimeZone('America/Mexico_City')); 
        $date =  $d->format('Y-m-d H:m:s');
        $bo = BranchOffice::find($request->branchOfficeId);
        return view("reports.reportInventByBranchOfficeId",[
            "branchOffice" => $bo,
            "products" => Product::All(),
            "user" => Auth::user(),
            "date" =>$date,
        ]);
    }

    public function inventByBranchOfficeIdDownload(Request $request){
        $d = new DateTime('NOW',new DateTimeZone('America/Mexico_City')); 
        $date =  $d->format('Y-m-d H:m:s');
        $bo = BranchOffice::find($request->branchOfficeId);
        view()->share([
            "branchOffice" => $bo,
            "products" => Product::All(),
            "user" => Auth::user(),
            "date" =>$date,
        ]);

        $pdf = PDF::loadView("reports.reportInventByBranchOfficeId");
  
        // download PDF file with download method
        return $pdf->download('report_inventario_sucursal.pdf');
    }

    public function inventByBranchOfficeIdDownloadExcel(Request $request){
        return Excel::download(new InventByBranchOfficeIdExport($request), 'report_inventario_sucursal.xlsx');
    }



}
