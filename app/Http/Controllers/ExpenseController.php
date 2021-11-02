<?php

namespace App\Http\Controllers;

use App\Expense;
use App\BranchOffice;
use App\CashClosing;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return Expense::where('branch_office_id',1)->get();
        if (Auth::user()->rol_id == 1) {
            return view('expenses.index', ['expenses' => Expense::all()]);
        } else {
            return back()->withErrors(["error" => "No tiene permisos."]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('expenses.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->rol_id == 1) {
            $caja = CashClosing::where('status', false)->where('user_id', Auth::user()->id)->first();
            if ($caja == []) {
                return response('bad', 500)->json('bad');
            } else {

                DB::beginTransaction();
                try {
                    foreach ($request->all() as $key => $item) {
                        Expense::create([
                            'quantity' => $item['Cantidad'],
                            'description' => $item['Descripcion'],
                            'price' => $item['Precio/pza'],
                            'user_id' => Auth::user()->id,
                            'branch_office_id' => Auth::user()->branch_office_id,
                            'cash_closing_id' => $caja->id,
                        ]);
                    }
                    DB::commit();
                    return response()->json('ok');
                } catch (\Throwable $th) {
                    throw $th;
                    DB::rollBack();
                }
            }
        }else{
            return back()->withErrors(["error" => "No tiene permisos."]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        return $expense;
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
    public function update(Request $request, Expense $expense)
    {
        if (Auth::user()->rol_id==1) {
            try {
                //return $request;
                $expense->edit($request->only(['description', 'quantity', 'price']));
                return back()->with(["success" => "Éxito al realizar la operación."]);
            } catch (\Throwable $th) {
                return back()->withErrors(["error" => "No se pudo realizar la operación."]);
            }
        } else {
            return back()->withErrors(["error" => "No tiene permisos."]);
        }
        
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        if (Auth::user()->rol_id==1) {
            $expense->del();
            return back()->with(["success" => "Éxito al realizar la operación."]);
        } else {
            return back()->withErrors(["error" => "No tiene permisos."]);
        }
    }
}
