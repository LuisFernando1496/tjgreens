<?php

namespace App\Http\Controllers;

use App\Cart;
use App\CartShopping;
use App\Inventory;
use App\InventoryShipment;
use App\InventoryShopping;
use App\Product;
use App\Shipment;
use App\Shopping;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\ShoppingCart;
use Illuminate\Http\Response;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $carrito = Cart::where('user_id','=',$user->id)
        ->where('status','=',true)->get();

        return response()->json($carrito);
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
    public function store(Request $request,$id)
    {
        $user = Auth::user();
        $search = Cart::where('inventory_id','=',$id)->where('status','=',true)->get();
        if (sizeof($search) > 0) {

            $descuento = ($request->discount + $search[0]->discount)/100;
            $cantidad = ($request->quantity + $search[0]->quantity);
            $subtotal = ($request->subtotal + $search[0]->subtotal);
            $total = $subtotal - ($subtotal * $descuento);

            try {
                DB::beginTransaction();
                DB::table('carts')->where('inventory_id','=',$id)
                ->where('status','=',true)->update([
                    'quantity' => $cantidad,
                    'subtotal' => $subtotal,
                    'total' => $total,
                    'discount' => $descuento * 100
                ]);
                DB::commit();
                return redirect()->route('almacen.index');
            } catch (\Error $th) {
                DB::rollBack();
                return $th;
            }
        } else {
            try {
                DB::beginTransaction();
                $carrito = new Cart();
                $carrito->inventory_id = $id;
                $carrito->user_id = $user->id;
                $carrito->quantity = $request->quantity;
                $carrito->subtotal = $request->subtotal;
                $carrito->total = $request->total;
                $carrito->discount = $request->discount;
                $carrito->status = true;
                $carrito->save();
                DB::commit();
                return redirect()->route('almacen.index');
            } catch (\Error $th) {
                DB::rollBack();
                return $th;
            }
        }
    }

    public function addcart(Request $request,$id)
    {
        $user = Auth::user();
        $search = CartShopping::where('inventory_id','=',$id)->where('status','=',true)->get();
        if (sizeof($search) > 0) {
            $descuento = ($request->discount + $search[0]->discount)/100;
            $cantidad = ($request->quantity + $search[0]->quantity);
            $subtotal = ($request->subtotal + $search[0]->subtotal);
            $total = $subtotal - ($subtotal * $descuento);

            try {
                DB::beginTransaction();
                DB::table('cart_shoppings')->where('inventory_id','=',$id)
                ->where('status','=',true)->update([
                    'quantity' => $cantidad,
                    'subtotal' => $subtotal,
                    'total' => $total,
                    'discount' => $descuento * 100
                ]);
                DB::commit();
                return redirect()->route('almacen.index');
            } catch (\Error $th) {
                DB::rollBack();
                return $th;
            }
        } else {
            try {
                DB::beginTransaction();
                $carrito = new CartShopping();
                $carrito->inventory_id = $id;
                $carrito->user_id = $user->id;
                $carrito->quantity = $request->quantity;
                $carrito->subtotal = $request->subtotal;
                $carrito->total = $request->total;
                $carrito->discount = $request->discount;
                $carrito->status = true;
                $carrito->save();
                DB::commit();
                return redirect()->route('almacen.index');
            } catch (\Error $th) {
                DB::rollBack();
                return $th;
            }
        }

    }

    public function concluir(Request $request)
    {
        $user = Auth::user();
        $almacen = Warehouse::where('user_id','=',$user->id)->get();
        $carrito = Cart::where('user_id','=',$user->id)->where('status','=',true)->get();
        try {
            DB::beginTransaction();
            $traspaso = new Shipment();
            $traspaso->warehouse_id = $almacen[0]->id;
            $traspaso->office_id = $request->office_id;
            $traspaso->total = $request->total;
            $traspaso->type = $request->type;
            $traspaso->subtotal = $request->subtotal;
            $traspaso->discount = $request->discount;
            $traspaso->user_id = $user->id;
            $traspaso->save();

            foreach ($carrito as $cart) {
                $product = new InventoryShipment();
                $product->inventory_id = $cart->inventory_id;
                $product->shipment_id = $traspaso->id;
                $product->quantity = $cart->quantity;
                $product->total = $cart->total;
                $product->discount = $cart->discount;
                $product->save();

                DB::table('carts')->where('id','=',$cart->id)->update([
                    'status' => false
                ]);
            }
            DB::commit();
            return redirect()->route('almacen.index');

        } catch (\Error $th) {
            DB::rollBack();
            return $th;
        }
    }

    public function concluirCompra(Request $request)
    {
        $user = Auth::user();
        $almacen = Warehouse::where('user_id','=',$user->id)->get();
        $carrito = CartShopping::where('user_id','=',$user->id)->where('status',true)->get();
        try {
            DB::beginTransaction();
            $compra = new Shopping();
            $compra->warehouse_id = $almacen[0]->id;
            $compra->office_id = $request->office_id;
            $compra->total = $request->total;
            $compra->type = $request->type;
            $compra->subtotal = $request->subtotal;
            $compra->discount = $request->discount;
            $compra->user_id = $user->id;
            $compra->save();

            foreach ($carrito as $cart) {
                $product = new InventoryShopping();
                $product->inventory_id = $cart->inventory_id;
                $product->shopping_id = $compra->id;
                $product->quantity = $cart->quantity;
                $product->total = $cart->total;
                $product->discount = $cart->discount;
                $product->save();

                DB::table('cart_shoppings')->where('id','=',$cart->id)->update([
                    'status' => false
                ]);
                $inventario = Inventory::findOrFail($cart->inventory_id);
                DB::table('inventories')->where('id','=',$cart->inventory_id)->update([
                    'stock' => $inventario->stock + $cart->quantity
                ]);
            }
            DB::commit();
            return redirect()->route('almacen.index');

        } catch (\Error $th) {
            DB::rollBack();
            return $th;
        }
    }

    public function pagado($id)
    {
        $user = Auth::user();
        $productos = InventoryShipment::where('shipment_id','=',$id)->get();
        $venta = Shipment::findOrFail($id);
        try {
            DB::beginTransaction();
            DB::table('shipments')->where('id','=',$id)->update([
                'status' => "Pagado"
            ]);
            foreach ($productos as $producto) {
                $inventario = Inventory::findOrFail($producto->inventory_id);

                /*$item = Product::where('name','=',$inventario->name)->where('category_id','=',$inventario->category_id)
                ->where('brand_id','=',$inventario->brand_id)->where('branch_office_id','=',$user->branch_office_id)->get();*/
                $item = Product::where('bar_code',$inventario->bar_code)->where('branch_office_id','=',$venta->office_id)->get();
                if (sizeof($item) > 0) {
                    //return $item;
                    DB::table('products')->where('id','=',$item[0]->id)->update([
                        'stock' => $item[0]->stock + $producto->quantity
                    ]);
                } else {
                    $new = new Product();
                    $new->name = $inventario->name;
                    $new->bar_code = $inventario->bar_code;
                    $new->cost = $inventario->cost;
                    $new->price_1 = $inventario->price;
                    $new->price_2 = $inventario->price;
                    $new->price_3 = $inventario->price;
                    $new->category_id = $inventario->category_id;
                    $new->brand_id = $inventario->brand_id;
                    $new->status = true;
                    $new->stock = $producto->quantity;
                    $new->branch_office_id = $venta->office_id;
                    $new->save();

                }

                DB::table('inventories')->where('id','=',$inventario->id)->update([
                    'stock' => $inventario->stock - $producto->quantity
                ]);

            }
            DB::commit();
            return redirect()->route('almacen.ventas');
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
            DB::table('carts')->where('id','=',$id)->delete();
            DB::commit();
            return $this->successResponse();
        } catch (\Error $th) {
            DB::rollBack();
            return $th;
        }
    }

    public function eliminar($id)
    {
        try {
            DB::beginTransaction();
            DB::table('cart_shoppings')->where('id','=',$id)->delete();
            DB::commit();
            return $this->successResponse();
        } catch (\Error $th) {
            DB::rollBack();
            return $th;
        }
    }

    public function successResponse($code = Response::HTTP_OK, $message = "Operación realizada exitosamente")
    {
        return response()->json([
            'status_code' => $code,
            'message' => $message,
        ]);
    }
}
