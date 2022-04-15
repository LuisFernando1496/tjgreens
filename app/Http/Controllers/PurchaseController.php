<?php

namespace App\Http\Controllers;

use App\Purchase;
use App\Expense;
use App\BranchOffice;
use App\CashClosing;
use App\Exports\ReportPurchases;
use App\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Excel;
class PurchaseController extends Controller
{
    public function index()
    {
        if (Auth::user()->rol_id == 1 ) {
            $products = Product::where('status', true)->where('branch_office_id',Auth::user()->branch_office_id)->get();
            return view('purchase/index', ['products' => $products]);
        } else {
        if (Auth::user()->rol_id == 3 ) {
            $products = Product::where('status', true)->where('branch_office_id',Auth::user()->branch_office_id)->get();
            return view('purchase/index', ['products' => $products]);
        } 
            return back()->withErrors(["error" => "No tienes permisos"]);
        }
    }
    public function store(Request $request)
    {
        $caja = CashClosing::where('status', false)->where('user_id', Auth::user()->id)->first();
        if($caja==null){
            return back()->withErrors(["error" => "Necesita tener una caja abierta para comprar."]);
        }else{
            $request['user_id'] = Auth::user()->id;
        if(Purchase::create($request->all())){
            $product=Product::findOrFail($request->product_id);
            $request['stock'] = $product->stock+$request->quantity;
            if ($product->update($request->all())) {
                $newExpense = [
                    'quantity' => $request->quantity,
                    'description'=>$request->name,
                    'price'=>$request->total,
                    'user_id'=>$request->user_id,
                    'cash_closing_id' => $caja->id,
                    'branch_office_id' => Auth::user()->branch_office_id,
                ];
                if(Expense::create($newExpense)){
                    return back()->with(["success" => "Éxito al realizar la operación."]);
                }else{
                    return back()->withErrors(["error" => "No se pudo realizar la operación."]);
                }
                
            } else {
                return back()->withErrors(["error" => "No se pudo realizar la operación."]);
            }
        }else{
            return back()->withErrors(["error" => "Ups, algo falló"]);
        }
        }
        
        
    }

    public function reportPurchase(Request $request)
    {
        $from = Carbon::parse($request->from)->format('y-m-d');
        $to = Carbon::parse($request->to)->format('y-m-d');
        if($request->today = 'on'){
            $from = Carbon::now()->format('y-m-d');
        $to = Carbon::now()->format('y-m-d');
        }
          return Excel::download(new ReportPurchases($request), "reporte-compras-$from-a-$to.xlsx");
     
    }

    public function paginate($items, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return (new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options))->withPath(url()->current());
    }
    public function getHistory()
    {
        if (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3) {
            $products = Purchase::where('user_id',Auth::user()->id)->get();
            return view('purchase/history', ['products' => $this->paginate($products)]);
        } else {
            return back()->withErrors(["error" => "No tienes permisos"]);
        }
        
    }
}
