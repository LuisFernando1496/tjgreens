<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\Request;
use App\User;
use App\ProductInSale;
use App\Sale;
use App\Payment;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }
    public function showCredits()
    {
        $user = Auth::user();
        if ($user->rol_id == 1) {
            //dd(Sale::whereNotNull('client_id')->with('payments')->orderBy('id', 'DESC')->get());
            $credit=Sale::whereNotNull('client_id')->with('payments')->orderBy('id', 'DESC')->get();
            //dd($credit);
            return view('client.credit', ['credit' => $credit]);
        } else {
            return back()->withErrors(["error" => "No tienes permisos"]);
        }
    }
    public function paginate($items, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return (new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options))->withPath(url()->current());
    }
    public function showDetailsHistory($id)
    {
        $details=ProductInSale::join('products','products.id','product_id')->where('sale_id',$id)->get();
        $sale=Sale::where('id', $id)->first();
        
        $history=Payment::where('sale_id', $id)->get();
        $last_payment=Payment::where('sale_id', $id)->latest()->first();
       
        
        return view('client.history', ['details' =>$details,'sale'=>$sale,'history'=>$history,'last_payment'=>$last_payment]);
    }
    public function abonar(Request $request)
    {

        $client= Client::findOrFail($request->client_id);
        $sale= Sale::findOrFail($request->sale_id);
        if($request->leftover==0){
            try {
                DB::beginTransaction();
                
                $client->authorized_credit = $client->authorized_credit +$request->deposit;
                $client->save();
                $sale->status_credit ='pagado';
                $sale->save();
                $newPayment = [
      
                    'sale_id' => $request->sale_id,
                    'deposit' => $request->deposit,
                    'leftover' => $request->leftover,
        
        
                ];
    
                $payment = new Payment($newPayment);
                $payment->save();
                DB::commit();
                return back()->with(["success" => "Éxito al realizar la operación."]);
            } catch (\Throwable $th) {
                DB::rollback();
                return back()->withErrors(["error" => "No se pudo realizar la operación."]);
            }

        }else{
            try {
                DB::beginTransaction();
                
                $client->authorized_credit = $client->authorized_credit +$request->deposit;
                $client->save();
                $newPayment = [
      
                    'sale_id' => $request->sale_id,
                    'deposit' => $request->deposit,
                    'leftover' => $request->leftover,
        
        
                ];
    
                $payment = new Payment($newPayment);
                $payment->save();
                DB::commit();
                return back()->with(["success" => "Éxito al realizar la operación."]);
            } catch (\Throwable $th) {
                DB::rollback();
                return back()->withErrors(["error" => "No se pudo realizar la operación."]);
            }

        }
        
     
        //return view('client.history', ['details' =>$details,'sale'=>$sale,'history'=>$history]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
