<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\InventoryShipment;
use App\Shipment;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
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
        $user = Auth::user();
        $almacen = Warehouse::where('user_id','=',$user->id)->get();

        try {
            DB::beginTransaction();
            $inventario = new Inventory();
            $inventario->bar_code = $request->bar_code;
            $inventario->name = $request->name;
            $inventario->cost = $request->cost;
            $inventario->price = $request->price;
            $inventario->stock = $request->stock;
            $inventario->category_id = $request->category_id;
            $inventario->brand_id = $request->brand_id;
            $inventario->warehouse_id = $almacen[0]->id;
            $inventario->save();
            DB::commit();
            return redirect()->route('almacen.index');
        } catch (\Error $th) {
            DB::rollBack();
            return $th;
        }
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
        try {
            DB::beginTransaction();
            DB::table('inventories')->where('id','=',$id)->update([
                'bar_code' => $request->bar_code,
                'name' => $request->name,
                'cost' => $request->cost,
                'price' => $request->price,
                'stock' => $request->stock,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
            ]);
            DB::commit();
            return redirect()->route('almacen.index');
        } catch (\Error $th) {
            DB::rollBack();
            return $th;
        }
    }

    public function transfer(Request $request)
    {
        $user = Auth::user();
        $almacen = Warehouse::where('user_id','=',$user)->get();
        $productos = $request['inventarios'];
        $cantidades = $request['cantidades'];
        $totales = $request['totales'];
        $descuentos = $request['descuentos'];

        try {
            DB::beginTransaction();
            $traspaso = new Shipment();
            $traspaso->warehouse_id = $almacen[0]->id;
            $traspaso->office_id = $request->office_id;
            $traspaso->total = $request->total;
            $traspaso->type = $request->type;
            $traspaso->user_id = $user->id;
            $traspaso->save();

            for ($i=0; $i < $productos; $i++) {
                $invtras = new InventoryShipment();
                $invtras->inventory_id = $productos[$i];
                $invtras->shipment_id = $traspaso->id;
                $invtras->quantity = $cantidades[$i];
                $invtras->total = $totales[$i];
                $invtras->discount = $descuentos[$i];
                $invtras->save();
            }

            DB::commit();
            return redirect()->route('almacen.index');

        } catch (\Error $th) {
            DB::rollBack();
            return $th;
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
