<?php

namespace App\Http\Controllers;

use App\BranchOffice;
use App\Brand;
use App\Cart;
use App\CartShopping;
use App\Category;
use App\Inventory;
use App\InventoryShopping;
use App\Shipment;
use App\Shopping;
use App\User;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->rol_id == 1) {
            $almacenes = Warehouse::with(['oficina','user','inventarios'])
            ->where('office_id','=',$user->branch_office_id)->paginate();
            $usuarios = User::where('rol_id','=','4')->where('branch_office_id','=',$user->branch_office_id)->get();

            return view('warehouse.index',[
                'almacenes' => $almacenes,
                'usuarios' => $usuarios
            ]);
        } else {
            $almacen = Warehouse::where('user_id','=',$user->id)->get();
            $inventario = Inventory::where('warehouse_id','=',$almacen[0]->id)->with(['marca','categoria','almacen'])->get();
            $categorias = Category::all();
            $carrito = Cart::where('user_id','=',$user->id)
            ->where('status','=',true)->get();
            $carritoCompras = CartShopping::where('user_id','=',$user->id)->where('status','=',true)->get();
            $marcas = Brand::all();
            $oficinas = BranchOffice::where('status','=',true)->get();
            return view('warehouse.index',[
                'almacenes' => $almacen,
                'inventarios' => $inventario,
                'categorias' => $categorias,
                'marcas' => $marcas,
                'carrito' => $carrito,
                'carritoCompras' => $carritoCompras,
                'oficinas' => $oficinas
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
        $user = Auth::user();

        try {
            DB::beginTransaction();
            $almacen = new Warehouse();
            $almacen->office_id = $user->branch_office_id;
            $almacen->user_id = $request['user_id'];
            $almacen->status = true;
            $almacen->save();
            DB::commit();
            return redirect()->route('almacen.index');
        } catch (\Error $th) {
            DB::rollBack();
            return $th;
        }
    }

    public function status($id)
    {
        $almacen = Warehouse::findOrFail($id);
        if ($almacen->status == true) {
            $status = false;
        } else {
            $status = true;
        }
        try {
            DB::beginTransaction();
            DB::table('warehouses')->where('id','=',$id)->update([
                'status' => $status
            ]);
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
    public function update(Request $request, Warehouse $almacen)
    {
        try {
            DB::beginTransaction();
            DB::table('warehouses')->where('id','=',$almacen->id)->update([
                'user_id' => $request->user_id,
            ]);
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

    public function ventas()
    {
        $user = Auth::user();
        $ventas = Shipment::where('user_id','=',$user->id)->get();
        return view('warehouse.ventas',[
            'ventas' => $ventas
        ]);
    }

    public function generateOrder()
    {
        $user = Auth::user();
        $hoy = date('Y-m-d');
        $almacen = Warehouse::where('user_id','=',$user->id)->get();
        $inventario = Inventory::where('warehouse_id','=',$almacen[0]->id)
        ->whereDate('created_at',$hoy)
        /*->orwhereDate('updated_at',$hoy)*/->get();

        $compra = Shopping::where('warehouse_id','=',$almacen[0]->id)
        ->whereDate('created_at',$hoy)->get();

        $compras = InventoryShopping::where('shopping_id','=',$compra[0]->id)->get();
        $totalCosto = 0;
        $totalPrecio = 0;

        return view('warehouse.order',[
            'compras' => $inventario,
            'recompras' => $compras,
        ]);
    }
}
