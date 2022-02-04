<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\Shipment;
use App\Shopping;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShoppingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function ventas(Request $request)
    {
        $user = Auth::user();
        $fecha = "";
        if ($request->today == "on") {
            $today = date('Y-m-d');
            $fecha = $today;
            $reporte = Shipment::where('user_id','=',$user->id)->whereDate('created_at',$today)->with(['almacen','oficina','usuario','productos'])->get();
            //return $reporte;
            return view('warehouse.reporte_ventas',[
                'reportes' => $reporte,
                'fecha' => $fecha

            ]);

        } else {
            $from = $request->from;
            $to = $request->to;
            $fecha = $from . " al " . $to;
            $reporte = Shipment::where('user_id','=',$user->id)->whereBetween('created_at',[$from,$to])->with(['almacen','oficina','usuario','productos'])->get();
            return view('warehouse.reporte_ventas',[
                'reportes' => $reporte,
                'fecha' => $fecha
            ]);
        }
    }

    public function compras(Request $request)
    {
        $user = Auth::user();
        $almacen = Warehouse::where('user_id','=',$user->id)->first();
        $fecha = "";
        if ($request->today == "on") {
            $today = date('Y-m-d');
            $fecha = $today;
            $reporte = Shopping::where('user_id','=',$user->id)->whereDate('created_at',$today)->with(['almacen','oficina','usuario','productos'])->get();
            $nuevo = Inventory::where('warehouse_id','=',$almacen->id)->whereDate('created_at',$today)->get();
            return view('warehouse.reporte_compras',[
                'reportes' => $reporte,
                'fecha' => $fecha,
                'nuevas' => $nuevo
            ]);
        } else {
            $from = $request->from;
            $to = $request->to;
            $fecha = $from . " al " . $to;
            $reporte = Shopping::where('user_id','=',$user->id)->whereBetween('created_at',[$from,$to])->with(['almacen','oficina','usuario','productos'])->get();
            $nuevo = Inventory::where('warehouse_id','=',$almacen->id)->whereBetween('created_at',[$from,$to])->get();
            return view('warehouse.reporte_compras',[
                'reportes' => $reporte,
                'fecha' => $fecha,
                'nuevas' => $nuevo
            ]);
        }

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
        try {
            DB::beginTransaction();
            DB::table('shipments')->where('id',$id)->delete();
            DB::commit();
            return redirect()->route('almacen.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th;
        }
    }
}
