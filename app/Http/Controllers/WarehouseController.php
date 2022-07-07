<?php

namespace App\Http\Controllers;

use App\BranchOffice;
use App\Brand;
use App\Cart;
use App\CartShopping;
use App\Category;
use App\Inventory;
use App\InventoryShipment;
use App\InventoryShopping;
use App\Product;
use App\Shipment;
use App\Shopping;
use App\User;
use App\Warehouse;
use App\Exports\inventarioAlmacen;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        try {
            $user = Auth::user();
            //$this->devolucion();
            if ($user->rol_id == 1) {
                $almacenes = Warehouse::with(['oficina', 'user', 'inventarios'])
                    ->where('office_id', '=', $user->branch_office_id)->paginate();
                $usuarios = User::where('rol_id', '=', '4')->where('branch_office_id', '=', 1)->get();
               // return $usuarios;
                return view('warehouse.index', [
                    'almacenes' => $almacenes,
                    'usuarios' => $usuarios
                ]);
            } else {
                
                $almacen = Warehouse::where('user_id', '=', $user->id)->get();
             // return $almacen;
                if (empty($almacen) ) {
                    $inventarios = [];
                } else {
                    $inventarios = Inventory::where('warehouse_id', '=', $almacen[0]->id)->with(['marca', 'categoria', 'almacen'])->orderBy('id','DESC')->paginate(5);
                    $invetories = Inventory::where('warehouse_id', '=', $almacen[0]->id)->with(['marca', 'categoria', 'almacen'])->orderBy('id','DESC')->get();
                }
                $categorias = Category::all();
                $carrito = Cart::where('user_id', '=', $user->id)
                    ->where('status', '=', true)->with(['inventario'])->get();
                $carritoCompras = CartShopping::where('user_id', '=', $user->id)->where('status', '=', true)->get();
                $marcas = Brand::all();
                $oficinas = BranchOffice::where('status', '=', true)->get();
             //  return $inventario;
                return view('warehouse.index', [
                    'almacenes' => $almacen,
                    'inventarios' => $inventarios,
                    'categorias' => $categorias,
                    'marcas' => $marcas,
                    'carrito' => $carrito,
                    'carritoCompras' => $carritoCompras,
                    'oficinas' => $oficinas,
                    'invetories' => $invetories,
                ]);
            }
        } catch (\Throwable $th) {
            return $th;
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
            DB::table('warehouses')->where('id', '=', $id)->update([
                'status' => $status
            ]);
            DB::commit();
            return redirect()->route('almacen.index');
        } catch (\Error $th) {
            DB::rollBack();
            return $th;
        }
    }

    public function devolucion()
    {
        try {
            DB::beginTransaction();
            $venta = Shipment::findOrFail(5);
            $productos = InventoryShipment::where('shipment_id', '=', $venta->id)->get();
            foreach ($productos as $item) {
                $inventario = Inventory::findOrFail($item->inventory_id);
                $producto = Product::where('branch_office_id', '=', 5)->where('bar_code', '=', $inventario->bar_code)->get();
                if (sizeof($producto) > 0) {
                    DB::table('products')->where('id', '=', $producto[0]->id)->update([
                        'stock' => $producto[0]->stock - $item->quantity
                    ]);
                }
            }
            DB::commit();
        } catch (\Error $th) {
            DB::rollBack();
            return $th;
        }

        try {
            DB::beginTransaction();
            $venta = Shipment::findOrFail(8);
            $productos = InventoryShipment::where('shipment_id', '=', $venta->id)->get();
            foreach ($productos as $item) {
                $inventario = Inventory::findOrFail($item->inventory_id);
                $producto = Product::where('branch_office_id', '=', 5)->where('bar_code', '=', $inventario->bar_code)->get();
                if (sizeof($producto) > 0) {
                    DB::table('products')->where('id', '=', $producto[0]->id)->update([
                        'stock' => $producto[0]->stock - $item->quantity
                    ]);
                }
            }
            DB::commit();
        } catch (\Error $th) {
            DB::rollBack();
            return $th;
        }

        try {
            DB::beginTransaction();
            $venta = Shipment::findOrFail(9);
            $productos = InventoryShipment::where('shipment_id', '=', $venta->id)->get();
            foreach ($productos as $item) {
                $inventario = Inventory::findOrFail($item->inventory_id);
                $producto = Product::where('branch_office_id', '=', 5)->where('bar_code', '=', $inventario->bar_code)->get();
                if (sizeof($producto) > 0) {
                    DB::table('products')->where('id', '=', $producto[0]->id)->update([
                        'stock' => $producto[0]->stock - $item->quantity
                    ]);
                }
            }
            DB::commit();
        } catch (\Error $th) {
            DB::rollBack();
            return $th;
        }

        try {
            DB::beginTransaction();
            $venta = Shipment::findOrFail(10);
            $productos = InventoryShipment::where('shipment_id', '=', $venta->id)->get();
            foreach ($productos as $item) {
                $inventario = Inventory::findOrFail($item->inventory_id);
                $producto = Product::where('branch_office_id', '=', 5)->where('bar_code', '=', $inventario->bar_code)->get();
                if (sizeof($producto) > 0) {
                    DB::table('products')->where('id', '=', $producto[0]->id)->update([
                        'stock' => $producto[0]->stock - $item->quantity
                    ]);
                }
            }
            DB::commit();
        } catch (\Error $th) {
            DB::rollBack();
            return $th;
        }

        try {
            DB::beginTransaction();
            $venta = Shipment::findOrFail(11);
            $productos = InventoryShipment::where('shipment_id', '=', $venta->id)->get();
            foreach ($productos as $item) {
                $inventario = Inventory::findOrFail($item->inventory_id);
                $producto = Product::where('branch_office_id', '=', 5)->where('bar_code', '=', $inventario->bar_code)->get();
                if (sizeof($producto) > 0) {
                    DB::table('products')->where('id', '=', $producto[0]->id)->update([
                        'stock' => $producto[0]->stock - $item->quantity
                    ]);
                }
            }
            DB::commit();
        } catch (\Error $th) {
            DB::rollBack();
            return $th;
        }
    }

    public function inventarioDownload()
    {
        $productos = Inventory::with(['marca', 'categoria', 'almacen'])->get();
     
        return view('warehouse.inventarioAlmacen', compact('productos'));
        return $productos;
    }
    public function inventarioDownloadExcel()
    {
        $fecha = Carbon::now();
       
       return Excel::download(new inventarioAlmacen, "Reporte-inventario-$fecha.xlsx");
   
    }

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
            DB::table('warehouses')->where('id', '=', $almacen->id)->update([
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
        $ventas = Shipment::where('user_id', '=', $user->id)->paginate(10);
        $sales = Shipment::where('user_id', '=', $user->id)->orderBy('id','DESC')->paginate(10);
        return view('warehouse.ventas', [
            'ventas' => $ventas,
            'sales' => $sales
        ]);
    }

    public function generateOrder()
    {
        $user = Auth::user();
        $hoy = date('Y-m-d');
        $mod_date = strtotime($hoy . "+ 1 days");
        $tomorrow = date("d-m-Y", $mod_date);


        $almacen = Warehouse::where('user_id', '=', $user->id)->get();
        $inventario = Inventory::where('warehouse_id', '=', $almacen[0]->id)
            ->whereDate('created_at', $hoy)
            /*->orwhereDate('updated_at',$hoy)*/->get();

        $compra = Shopping::where('warehouse_id', '=', $almacen[0]->id)
            ->whereBetween('created_at', [$hoy, $tomorrow])->get();
        //return $compra;
        if (sizeof($compra) > 0) {
            $compras = InventoryShopping::where('shopping_id', '=', $compra[0]->id)->get();
        } else {
            $compras = [];
        }


        $totalCosto = 0;
        $totalPrecio = 0;

        return view('warehouse.order', [
            'compras' => $inventario,
            'recompras' => $compras,
        ]);
    }

    public function ticket($id)
    {
        $venta = Shipment::findOrFail($id);
        
        return view('warehouse.ticket', [
            'venta' => $venta
        ]);
    }

    public function factura($id)
    {
        $venta = Shipment::where('id',$id)->with(['productos.inventario.branchPrice'])->first();
        //return $venta;
        return view('warehouse.factura',[
            'venta' => $venta
        ]);
    }

    public function buscadorP($codigo)
    {
        //$producto = Product::join("branch_offices","branch_offices.id","=","products.branch_office_id")
        $producto = Product::where('bar_code', '=', $codigo)
        ->orWhere('name', 'LIKE', $codigo)
        ->with(['categoria', 'brand'])
        ->first();
        //$p = ProductInSale::join("sales" ,"sales.id", "=" ,"product_in_sales.sale_id")
            //->join("users","users.id","=","sales.user_id")
        if ($producto != null) {
            return response()->json($producto);
        } else {
            return null;
        }
    }

    public function codigoAlmacen(Inventory $almacen)
    {
        //return $almacen;
        return view('products.tag',[
            'product' => $almacen,
        ]);
    }
}
