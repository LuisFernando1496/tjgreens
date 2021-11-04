<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Brand;
use App\Category;
use App\Image;
use App\BranchOffice;
use App\Provider;
use App\Http\Resources\ProductCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use DB;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->rol_id == 1) {
            $products = Product::where('status', true)->get();
            $offices = BranchOffice::where('status', true)->get();
            $providers = Provider::all();
            return view('products.index', ['products' => $products, 'brands' => Brand::where('status', true)->get(), 'categories' => Category::where('status', true)->get(), 'offices' => $offices, 'providers' => $providers]);
        } else {
            return back()->withErrors(["error" => "No tienes permisos"]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $product = new Product();
        return view('', ['product' => $product]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3) {
            DB::beginTransaction();
            try {
                $exist = Product::where('bar_code', $request->bar_code)->where('branch_office_id', $request->branch_office_id)->where('status', true)->get();

                if (count($exist) != 0) {
                    return back()->withErrors(["error" => 'Ya hay un producto con ese codigo de barras en la sucursal']);
                }
              
                    $cost = $request->cost * 20.68;
           


                $product = Product::create(
                    [
                        'name' => $request->name,
                        'stock' => $request->stock,
                        'cost' => $cost,
                        'expiration' => $request->expiration,
                        'iva' => $request->iva,
                        'product_key' => $request->product_key,
                        'unit_product_key' => $request->unit_product_key,
                        'lot' => $request->lot,
                        'ieps' => $request->ieps,
                        'price_1' => $request->price_1,
                        'price_2' => $request->price_2,
                        'price_3' => $request->price_3,
                        'bar_code' => $request->bar_code,
                        'branch_office_id' => $request->branch_office_id,
                        'category_id' => $request->category_id,
                        'brand_id' => $request->brand_id,
                    ]
                );

                if ($request->has('image')) {
                    $path = Storage::disk('s3')->put('images/products', $request->image);
                    Image::create(["path" => $path, "title" => $product->name, "size" => $request->image->getSize(), "product_id" => $product->id]);
                }
                DB::commit();
                return back()->with(["success" => "Éxito al realizar la operación."]);
            } catch (\Throwable $th) {
                DB::rollback();
                //return $th->getMessage();
                return back()->withErrors(["error" => $th->getMessage()]);
            }
        } else {
            return back()->withErrors(["error" => "No tienes permisos"]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //return $product;
        try {
            $url_temp = $product->image->getUrlAttribute();
        } catch (\Throwable $th) {
            $url_temp = "https://granrueda-bucket.s3.amazonaws.com/images/products/Lr3Lmw5MWl2Dduxm6ib9deNQJKUY4PKQ9U3fzuc6.jpeg";
        }
        return ["product" => $product, "image" => $url_temp];
        // return $product->image->getUrlAttribute();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        if (Auth::user()->rol_id == 1) {
            try {
                $this->validate($request, [
                    'name' => 'required',
                    'stock' => 'required',
                    'cost' => 'required',
                    'price_1' => 'required',
                    'bar_code' => 'required',
                ]);
                $cost = $request->cost;
                if ($request->dollar) {
                    $cost = $request->cost * 20.68;
                }
                $product->edit([
                    'name' => $request->name,
                    'stock' => $request->stock,
                    'cost' => $cost,
                    'expiration' => $request->expiration,
                    'iva' => $request->iva,
                    'product_key' => $request->product_key,
                    'unit_product_key' => $request->unit_product_key,
                    'lot' => $request->lot,
                    'ieps' => $request->ieps,
                    'price_1' => $request->price_1,
                    'price_2' => $request->price_2,
                    'price_3' => $request->price_3,
                    'bar_code' => $request->bar_code,
                    'branch_office_id' => $request->branch_office_id,
                    'category_id' => $request->category_id,
                    'brand_id' => $request->brand_id,
                ]);
                if ($request->hasFile('image')) {
                    $path = Storage::disk('s3')->put('images/products', $request->image);
                    try {
                        $product->image->del();
                    } catch (\Throwable $th) {
                    }
                    Image::create(["path" => $path, "title" => $product->name, "size" => $request->image->getSize(), "product_id" => $product->id]);
                }
                return back()->with(["success" => "Éxito al realizar la operación."]);
            } catch (\Throwable $th) {
                return back()->withErrors(["error" => "No se pudo realizar la operación."]);
            }
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if (Auth::user()->rol_id == 1) {
            if ($product->image == null) {
                $product->changeStatus();
                return back()->with(["success" => "Éxito al realizar la operación."]);
            } else {
                try {
                    $product->image->deleteImageS3($product->image->getUrlAttribute());
                    $product->image->delete();
                    $product->changeStatus();
                    return back()->with(["success" => "Éxito al realizar la operación."]);
                } catch (\Throwable $th) {
                    return back()->withErrors(["error" => $th->getMessage()]);
                }
            }
        } else {
            return back()->withErrors(["error" => "No tienes permisos"]);
        }
    }

    public function changeStatus(Request $request, Product $product)
    {
        if (Auth::user()->rol_id == 1) {
            try {
                $product->changeStatus($request->status);
                return back()->with(["success" => "Éxito al realizar la operación."]);
            } catch (\Throwable $th) {
                return back()->withErrors(["error" => "No se pudo realizar la operación."]);
            }
        } else {
            return back()->withErrors(["error" => "No tienes permisos"]);
        }
    }

    public function stock()
    {
        $user = Auth::user();
        $idbranch = $user->branch_office_id;
        $offices = BranchOffice::where('status', true)->get();
        $providers = Provider::all();

        $products = Product::where('branch_office_id', '=', $idbranch)->paginate();

        return view('gerente.stock', [
            'products' => $products,
            'brands' => Brand::where('status', true)->get(),
            'categories' => Category::where('status', true)->get(),
            'offices' => $offices,
            'providers' => $providers
        ]);
    }
}
