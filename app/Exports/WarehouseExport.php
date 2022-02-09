<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
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
use Illuminate\Contracts\View\View;

class WarehouseExport implements FromView //implements FromCollection

{
    /**
    * @return \Illuminate\Support\Collection
    */
    /*public function collection()
    {
        //
    }*/
    private $dataGlobal;
    public function __construct(Request $request)
    {
        $this->dataGlobal = $request;
    }
    
    public function view(): View
    {
        $user = Auth::user();
        $almacen = Warehouse::where('user_id','=',$user->id)->get();
        $carrito = CartShopping::where('user_id','=',$user->id)->where('status',true)->get();
        try {
            DB::beginTransaction();
            $compra = new Shopping();
            $compra->warehouse_id = $almacen[0]->id;
            $compra->office_id = $this->dataGlobal->office_id;//$request->office_id;
            $compra->total = $this->dataGlobal->total;//$request->total;
            $compra->type = $this->dataGlobal->type;//$request->type;
            $compra->subtotal = $this->dataGlobal->subtotal;//$request->subtotal;
            $compra->discount = $this->dataGlobal->discount;//$request->discount;
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
            $allProducts = [];
            foreach ($this->dataGlobal->productsId as $key => $value) {
                $allProducts[$key] = Inventory::join("brands", "brands.id", "inventories.brand_id")
                ->join("categories", "categories.id", "inventories.category_id")
                ->select(
                    "inventories.name as name",
                    "categories.name as category_name",
                    "brands.name as brand_name",
                    "inventories.price as price",
                    "inventories.bar_code as code",
                )
                ->where("inventories.id", "=", $value)
                ->get();
            }
            return view('warehouse.sale',[
                'products' => $allProducts,
                'compra' => $compra,
                'cantidad' => $this->dataGlobal->quantityProducts,
            ]);
            //return redirect()->route('almacen.index');

        } catch (\Error $th) {
            DB::rollBack();
            return $th;
        }
    }
}
