<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CashClosing;
use Illuminate\Support\Facades\Auth;
use App\BranchOffice;
use App\Box;
use App\InitialCash;
use App\Sale;
use App\Expense;
use DateTime;
use DateTimeZone;
use DB;
use App\ProductInSale;
use Illuminate\Support\Facades\DB as FacadesDB;
use PDF;
// use App\User;

// use App\Product;

// use App\Exports\GeneralExport;
// use App\Exports\BranchOfficeExport;
// use App\Exports\UserExport;
// use App\Exports\CutBoxExport;
// use App\Exports\InventExport;
// use App\Exports\InventByBranchOfficeIdExport;
// use Excel;



class CashClosingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ( $user->rol_id == 1 ) {
            $cashClosings = CashClosing::orderBy('id','DESC')->paginate(10);
        } elseif( $user->rol_id == 3 || $user->rol_id == 5) {
            $cashClosings = CashClosing::where('branch_office_id', $user->branch_office_id)->orderBy('id','DESC')->paginate(10);
        }
        else {
          return ('no tienes permisos para ver este sitio');
        }
       
        return view( 'boxes.historyCashClosing', compact('cashClosings'));
    }

   
    public function searchCashClosing(Request $request)
    {
        $user = Auth::user();
        $search = $request->search;
        if ( $user->rol_id == 1 ) {
            $cashClosings = CashClosing::join('users','users.id','cash_closings.user_id')
                                        ->join('branch_offices', 'branch_offices.id', 'cash_closings.branch_office_id')
                                        ->where('users.name', 'like', '%'.$search.'%')
                                        ->orwhere('branch_offices.name', 'like', '%'.$search.'%')
                                        ->orwhere('cash_closings.created_at', 'like', '%'.$search.'%')
                                        ->orderBy('cash_closings.id','DESC')
                                        ->select('users.name as userName', 'branch_offices.name as officeName', 'cash_closings.*')
                                        ->paginate(10);
        } elseif( $user->rol_id == 3 ) {
           
            $cashClosings = CashClosing::join('users','users.id','cash_closings.user_id')
                                        ->join('branch_offices', 'branch_offices.id', 'cash_closings.branch_office_id')
                                        ->where('cash_closings.branch_office_id', $user->branch_office_id)
                                        ->where('users.name', 'like', '%'.$search.'%')
                                        ->orwhere('cash_closings.created_at', 'like', '%'.$search.'%')
                                        ->orderBy('cash_closings.id','DESC')
                                        ->where('cash_closings.branch_office_id', $user->branch_office_id)
                                        ->select('users.name as userName', 'branch_offices.name as officeName', 'cash_closings.*')
                                        ->paginate(10);
                        
        }
        else {
          return ('no tienes permisos para ver este sitio');
        }
      
        return view( 'boxes.resultSearch', compact('cashClosings','search'));
       
    }

    public function create(CashClosing $cashClosing)
    {
        return view('cashClosing.create',["cashClosing" => new CashClosing]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         DB::beginTransaction();
        try {
            //return back()->withErrors(['error'=>CashClosing::where('user_id','=',Auth::user()->id)->where('status','=',false)->count()]);
            //return back()->withErrors(['error'=>Box::find($request->box_id)->number, Box::find($request->box_id)->status, Box::find($request->box_id)->id]);
            if(CashClosing::where('user_id','=',Auth::user()->id)->where('status','=',false)->count() == 0 && Box::find($request->box_id)->status == true){
                $request["user_id"] = Auth::user()->id;
                $tempBox = Box::find($request->box_id);
                //return back()->withErrors(['error'=>$tempBox->status]);
                //$tempBox->status = true;
                $tempBox->save();
                $request['initial_cash'] = InitialCash::all()->pluck('amount')->first();
                $branchOffice = BranchOffice::where("name", "=", $request->branch_office_id)->where("status", "=", true)->get();
                $request['branch_office_id'] = $branchOffice[0]->id;
                //return back()->withErrors(['error'=>$branchOffice[0]->id]);
                CashClosing::create($request->all());
                DB::commit();
                return redirect('caja');
            }else{
                return back()->withErrors(['error'=>"No puedes abrir porque tienes un corte de caja pendiente."]);
            }
            
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->withErrors(['error'=>"No puedes abrir porque tienes un corte de caja pendiente."]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(CashClosing $cashClosing)
    {
        return view("cashClosing.show",["cashClosing" => $cashClosing]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(CashClosing $cashClosing)
    {
        return view('cashClosing.edit',["cashClosing" => $cashClosing]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,CashClosing $cashClosing)
    {
        // DB::beginTransaction();
        // try {
        //     $cashClosing->edit($request->all());
        //     DB::commit();
        //     return response()->json(['message'=>"caja actualizada con exito."],201);
        // } catch (\Throwable $th) {
        //     DB::rollback();
        //     return $th->getMessage();
        // }
    }

    public function closeBox(Request $request,CashClosing $cashClosing)
    {      
        $flag = false;
          
        $closeTime = new DateTime('NOW',new DateTimeZone('America/Mexico_City'));  
        
        $gastoCaja = Expense::select(DB::raw('sum(price * quantity) as total'))->where('cash_closing_id',$cashClosing->id)->first()->total;        
        $ventaCaja = Sale::where('cash_closing_id',$cashClosing->id)->where('payment_type',1)->where('status',true)->sum('cart_total');
        $endCash = $ventaCaja + $cashClosing->initial_cash - $gastoCaja;
        
        $request["end_cash"] = $endCash;
        
        DB::beginTransaction();
        try {
            if($cashClosing->status){
                return back()->withErrors(["error" => "La caja ya esta cerrada"]);
            }else{
                $flag = true;
                $request["status"]=true;
                $box = Box::find($cashClosing->box_id);
                //$box->status = false;
                $box->save();
                $cashClosing->end_cash = $request->end_cash;
                $cashClosing->status = true;
                $cashClosing->save();
                DB::commit();
            }




        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }

        if($flag){
            $tempCashClosing = $cashClosing;
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
            ->groupBy("sales.payment_type")
            ->get();


            $p = ProductInSale::join("sales" ,"sales.id", "=" ,"product_in_sales.sale_id")
            ->select("*","product_in_sales.discount As PD")
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

            $d0["caja_inicial"] = $tempCashClosing->initial_cash;
            return view('reports.reportCashClosing',["cash" => $d0,
            "card" => $d1,
            "user" => Auth::user(),
            "date" =>$date,
            "products"=>$p,
            "branchOffice" => $b,
            "worker" => $tempCashClosing->user]);
        }
    }
    public function closeBoxPdf(Request $request,$id)
    {      
        $flag = false;
        $cashClosing = CashClosing::find($id);
        $closeTime = new DateTime('NOW',new DateTimeZone('America/Mexico_City'));  
        
        $gastoCaja = Expense::select(DB::raw('sum(price * quantity) as total'))->where('cash_closing_id',$cashClosing->id)->first()->total;        
        $ventaCaja = Sale::where('cash_closing_id',$cashClosing->id)->where('payment_type',1)->where('status',true)->sum('cart_total');
        $endCash = $ventaCaja + $cashClosing->initial_cash - $gastoCaja;
        
        $request["end_cash"] = $endCash;
        
      
                $flag = true;
               

        if($flag){
            $tempCashClosing = $cashClosing;
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
            ->groupBy("sales.payment_type")
            ->get();


            $p = ProductInSale::join("sales" ,"sales.id", "=" ,"product_in_sales.sale_id")
            ->where("sales.status",  "=", true)
            ->where("sales.cash_closing_id" , "=" , $tempCashClosing->id)
            ->select("*","product_in_sales.discount As PD")
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

            $d0["caja_inicial"] = $tempCashClosing->initial_cash;
           
             view()->share(["cash" => $d0,
            "card" => $d1,
            "user" => Auth::user(),
            "date" =>$date,
            "products"=>$p,
            "branchOffice" => $b,
            "worker" => $tempCashClosing->user]);
            $pdf = PDF::loadView('reports.reportCashClosing');
            return $pdf->download("corteCaja-$date.pdf");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
