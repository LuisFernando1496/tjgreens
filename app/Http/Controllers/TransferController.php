<?php

namespace App\Http\Controllers;

use App\BranchOffice;
use App\Category;
use App\Product;
use App\ProductsTransfer;
use App\Transfer;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transfers = Transfer::with('branchOffice', 'user', 'products.product')
            ->where('provincial_branch_office_id', auth()->user()->branch_office_id)
            ->orWhere('destination_branch_office_id', auth()->user()->branch_office_id)
            ->get();
        // return dd($transfers);
        return view('tranfers.index', ['transfers' => $this->paginate($transfers)]);
    }

    public function paginate($items, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return (new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options))->withPath(url()->current());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $branches = BranchOffice::where('status', true)->where('id', '!=', auth()->user()->branch_office_id)->get();
        return view('tranfers.create', ['branches' => $branches, 'categories' => Category::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $transfer = $request->all()["transfer"];
            $transfer['provincial_branch_office_id'] = auth()->user()->branch_office_id;
            $transfer['user_id'] = auth()->user()->id;

            $transfer = new Transfer($transfer);
            $transfer->save();
            foreach ($request->all()["products"] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $productDestinity = Product::where('bar_code', $item['barcode'])->where('branch_office_id', $transfer->destination_branch_office_id)->first();

                if ($productDestinity == '') {
                    return response()->json(['success' => false, 'error' => 'No existe el producto en la sucursal destino']);
                }

                if ($product->category_id != 1) {
                    $product->stock = $product->stock - $item['quantity'];
                } else {
                    //No se descuenta porque es servicio
                }
                $product->save();
                $productDestinity->save();
                $newProductInTransfer = [
                    'transfer_id' => $transfer->id,
                    'product_id' => $item["product_id"],
                    'quantity' => $item["quantity"],
                ];
                $productInTransfer =  new ProductsTransfer($newProductInTransfer);
                $productInTransfer->save();
            }
            DB::commit();
            return response()->json(['success' => true, 'good' => 'Transacción exitosa']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => 'No se ha podido generar el traspaso']);
            // return $th;
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
        $transfer = Transfer::with('branchOffice', 'user', 'products.product.brand')
            ->where('id', $id)->first();
        return view('tranfers.details', ['transfer' => $transfer]);
        // return dd($transfer->products);
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
        $transfer = Transfer::with('branchOffice', 'user', 'products.product.brand')
            ->where('id', $id)->first();

        DB::beginTransaction();
        try {
            foreach ($transfer->products as $item) {
                $productDestinity = Product::where('bar_code', $item->product->bar_code)->where('branch_office_id', $transfer->destination_branch_office_id)->first();
                $productDestinity->stock = $productDestinity->stock + $item['quantity'];
                $productDestinity->save();
            }
            $transfer->status = "Recibido";
            $transfer->save();
            DB::commit();
            return redirect('transfers')->with(["success" => "Éxito al realizar la operación."]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withErrors(["error" => "No se pudo realizar la operación."]);
            //throw $th;
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
