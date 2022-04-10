<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\InventoryShipment;
use App\Shipment;
use App\Product;
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
        try {
            DB::beginTransaction();
            DB::table('inventories')->where('id','=',$id)->delete();
            DB::commit();
            return redirect()->route('almacen.index');
        } catch (\Error $th) {
            DB::rollBack();
            return $th;
        }
    }


    public function busqueda($palabra)
    {
        $inventario = Inventory::where('name','LIKE',"%$palabra%")->orWhere('bar_code','=',$palabra)
        ->with(['marca','categoria','almacen'])
        ->get();
        return response()->json($inventario);
    }

    public function busquedaAlmacen($palabra){
        /*$sales = Shipment::where('user_id', '=', $user->id)->orderBy('id','DESC')->paginate(10);*/
        $inventario = Shipment::join('branch_offices','shipments.office_id','branch_offices.id')
        ->join("users", "shipments.user_id", "users.id")
        ->where('branch_offices.name','LIKE',"%{$palabra}%")
        ->orWhere("shipments.status", "LIKE", "%{$palabra}%")
        ->orWhere("shipments.type", "LIKE", "%{$palabra}%")
        ->orWhere("shipments.id", "LIKE", "%{$palabra}%")
        ->select(
            "shipments.id as id",
            "branch_offices.name as office",
            "shipments.type as type",
            "shipments.subtotal as subtotal",
            "shipments.discount as descuento",
            "shipments.total as total",
            "shipments.status as estado",
            "users.name as user",
            "users.last_name as last_name",
            "shipments.created_at as created_at",
        )
        ->orderBy('shipments.id','DESC')
        ->get();
        return response()->json($inventario);
    }

    public function busquedaModalVentas($id){
        $venta = InventoryShipment::join("inventories", "inventory_shipments.inventory_id", "inventories.id")
        ->where("inventory_shipments.shipment_id", "=", $id)
        ->select(
            "inventories.id as id",
            "inventories.name as name",
            "inventories.price as price",
            "inventory_shipments.quantity as quantity",
            "inventory_shipments.discount as discount",
            "inventory_shipments.total as total",
        )
        ->get();
        return response()->json($venta);
    }

    public function busquedaSucursal($id)
    {
        $inventario = Product::join('brands', 'products.brand_id', 'brands.id')
        ->join('categories', 'products.category_id', 'categories.id')
        ->where('products.branch_office_id', '=', $id)
        ->where('products.status', "=", true)
        ->where('products.stock', ">", 0)
        ->select(
            "products.id as id",
            "products.bar_code as code",
            "products.name as name",
            "categories.name as categoriname",
            "brands.name as brandname",
            "products.stock as stock",
            "products.price_1 as price",
            "products.cost as cost",
        )
        ->get();
        return response()->json($inventario);
    }
}
