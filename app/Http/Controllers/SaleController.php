<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductInSale;
use App\Sale;
use App\CashClosing;
use App\Category;
use App\Box;
use App\ShoppingCart as AppShoppingCart;
use Barryvdh\DomPDF\Facade as PDF;
use App\BranchOffice;
use App\Client;
use App\CommentSales;
use App\Exports\ReportTransfer;
use App\Http\Resources\ProductCollection;
use App\SendProduct;
use App\User;
use DateTime;
use DateTimeZone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Transfer;
use Excel;
class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

        if (Auth::user()->rol_id == 1) {
            $sales = Sale::where('status', true)->with(['productsInSale.product.category', 'branchOffice', 'user'])
            ->orderBy('id','DESC')
            ->paginate(10);
        }if(Auth::user()->rol_id == 3) {
            $sales = Sale::where('branch_office_id', Auth::user()->branch_office_id)->where('status', true)
            ->with(['productsInSale.product.category', 'branchOffice', 'user'])->orderBy('id','DESC')
            ->paginate(10);
        } 
        else {
            $sales = Sale::where('branch_office_id', Auth::user()->branch_office_id)->where('status', true)
            ->with(['productsInSale.product.category', 'branchOffice', 'user'])->orderBy('id','DESC')
            ->paginate(10);
        }

        return view('sales.index', [
            'sales' => $sales,
            'box' => CashClosing::where('user_id', '=', Auth::user()->id)->where('status', '=', false)->first()
        ]);
    }


    public function buscarVen(Request $request)
    {
        //return back()->withErrors(["error" => "No tienes permisos", $request->search]);
        //join("sales" ,"sales.id", "=" ,"product_in_sales.sale_id")
        $buscar = Sale::join('branch_offices','sales.branch_office_id','branch_offices.id')
        ->join('users', 'sales.user_id', 'users.id')
        //->join('product_in_sales', 'sales.id', 'product_in_sales.sale_id')
        //->join("product_in_sales" ,"sales.id", "=" ,"product_in_sales.sale_id")
        ->where("users.name", "LIKE", "%{$request->search}%")
        ->where("users.status", "=", true)
        ->orWhere("sales.id", "LIKE", "%{$request->search}%")
        ->where("sales.status", "=", true)
        ->orWhere("sales.folio_branch_office", "LIKE", "%{$request->search}%")
        ->where("sales.status", "=", true)
        ->orWhere("branch_offices.name", "LIKE", "%{$request->search}%")
        ->where("branch_offices.status", "=", true)
        ->select(
            "sales.id as id",
            "sales.folio_branch_office as folio_branch_office",
            "users.name as name",
            "branch_offices.name as branch_offices_name",
            "sales.cart_subtotal as cart_subtotal",
            "sales.amount_discount as amount_discount",
            "sales.cart_total as cart_total",
            "sales.created_at as created_at",
            "sales.user_id as user_id",
        )
        //->orderBy('id','DESC')
        ->paginate(20);
        //return back()->withErrors(["error" => "No tienes permisos", $buscar]);
        return response()->json($buscar);
    }

    public function factura(Request $request){
        $sale = Sale::where('id', $request->sale_id)->with(['branchOffice.address', 'productsInSale.product'])->first();
        $products = ProductInSale::join("sales" ,"sales.id", "=" ,"product_in_sales.sale_id")
            ->join("users","users.id","=","sales.user_id")
            ->join("products","products.id","=","product_in_sales.product_id")
            ->join("brands","brands.id","=","products.brand_id")
            ->join("categories","categories.id","=","products.category_id")
            ->select("products.name as product_name",
            "categories.name as category",
            "brands.name as brand",
            "product_in_sales.quantity as quantity",
            "products.cost as cost",
            "product_in_sales.sale_price as sale_price",
            "sales.amount_discount as amount_discount",
            "product_in_sales.total as total",
            "users.name as name",
            "users.last_name as last_name",
            "product_in_sales.created_at as date",
            "sales.branch_office_id"
            )
            ->where("sales.id", "=", $request->sale_id)
            ->where("sales.status",  "=", true)
            ->get();
        return view('sales.factura', ['sale' => $sale, 'sales' => $products]);
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
    public function paginate($items, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return (new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options))->withPath(url()->current());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /**sale_type
        saletype
        branch_office
        'status','provincial_branch_office_id','destination_branch_office_id','user_id','details'
        */

        $folioBranch = Sale::latest()->where('branch_office_id', Auth::user()->branchOffice->id)->pluck('folio_branch_office')->first();
        $sale = $request->all()["sale"];
        //return response()->json(['error'=>'Espere tantito,',$sale['comentario']]);
        $oficce = $request->all()["sale_type"];
        $total_cost_sale = 0;
        //traspaso con factura

        if($oficce['saletype'] == 1)
        {
            DB::beginTransaction();
            try {

                $transfer = Transfer::create([
                    'status'=>'Transferido',
                    'provincial_branch_office_id' => Auth::user()->branch_office_id ,
                    'destination_branch_office_id' => $oficce['branch_office'],
                    'user_id' => Auth::user()->id,
                    'details'=> 'Transferencia de Productos'
                ]);

               foreach ($request->all()["products"] as $key => $item) {
                    $product = Product::findOrFail($item['id']);
                    $product->stock = $product->stock - $item['quantity'];
                    $product->save();
                    $addProduct = Product::where('branch_office_id',$oficce['branch_office'])->where('bar_code',$product->bar_code)->first();

                    if(!empty($addProduct))
                    {
                      $addProduct->update([
                          'stock' => $addProduct->stock + $item['quantity'],
                      ]);
                    }
                    else
                    {
                      Product::create([
                        'name' => $product->name,
                        'stock' => $item['quantity'],
                        'cost' => $product->cost,
                        'expiration' => $product->expiration,
                        'iva' => $product->iva,
                        'product_key' => $product->product_key,
                        'unit_product_key' => $product->unit_product_key,
                        'lot' => $product->lot,
                        'ieps' => $product->ieps,
                        'price_1' => $product->price_1,
                        'price_2' => $product->price_2,
                        'price_3' => $product->price_3,
                        'bar_code' => $product->bar_code,
                        'branch_office_id' =>$oficce['branch_office'],
                        'category_id' => $product->category_id,
                        'brand_id' => $product->brand_id,
                        'status' => $product->status
                      ]);
                    }

                    SendProduct::create([
                        'product_id' => $item['id'],
                        'transfer_id' => $transfer->id,
                        'quantity' => $item['quantity'],
                        'subtotal' => $item['subtotal'],
                        'sale_price' => $item['sale_price'],
                        //'cost' => $item['costo'],
                        'total' => $item['total'],
                        'discount' => $item['discount']
                    ]);

                   // $total_cost_sale = $total_cost_sale + $newProductTraspace['total_cost'];

                }
                DB::commit();
                return response()->json(['success' => true, 'data' =>$oficce, 'transfer'=>$transfer]);
            } catch (\Throwable $th) {
               // Product::rollBack();
               // SendProduct::rollBack();
               // Transfer::rolBack();
                return response()->json(['error'=>'no se pudo realizar esta accion','data'=>$th]);
                //throw $th;
            }

        }
///////////////////////////////////////*****venta normal*******////////////////////////////////////////////////////
        if ($sale['client_id'] != null) {
            $client = Client::findOrFail($sale['client_id']);
            if ($sale['payment_type'] == 2) {
                if ($client->authorized_credit - $sale['cart_total'] >= 0) {


                    // $request['branch_office_id'] = Auth::user()->branch_office_id;
                    $sale['branch_office_id'] = Auth::user()->branch_office_id;
                    // $request['user_id'] = Auth::user()->id;
                    $sale['user_id'] = Auth::user()->id;

                    if ($folioBranch == null) {
                        $sale['folio_branch_office'] = 1;
                    } else {
                        $sale['folio_branch_office'] = $folioBranch + 1;
                    }

                    try {
                        DB::beginTransaction();
                        $client->authorized_credit = $client->authorized_credit - $sale['cart_total'];
                        $client->save();
                        $shopping_cart_id = new AppShoppingCart();
                        $shopping_cart_id->save();
                        //AGREGAR PRODUCTOS DE LA VENTA
                        $sale['shopping_cart_id'] = $shopping_cart_id->id;
                        $sale['status_credit'] = 'adeudo';
                        $comments = $oficce['comentario'];
                        if ($sale['card_ingress'] == null){
                            $sale['card_ingress'] = 0;
                        }
                        //$sale['shopping_cart_id'] = $shopping_cart_id->id;
                        $sale = new Sale($sale);
                        $sale->save();
                        if($comments != null){
                            $commentSales = new CommentSales;
                            $commentSales->sale_id = $sale->id;
                            $commentSales->comentario = $comments;
                            $commentSales->save();
                        }
                        //$sale = new Sale($sale);
                        //$sale->save();
                        foreach ($request->all()["products"] as $key => $item) {
                            $product = Product::findOrFail($item['id']);

                                $product->stock = $product->stock - $item['quantity'];

                            $product->save();
                            $newProductInSale = [
                                'product_id' => $item['id'],
                                'sale_id' => $sale->id,
                                'quantity' => $item['quantity'],
                                'subtotal' => $item['subtotal'],
                                'sale_price' => $item['sale_price'],
                                'cost' => $item['costo'],
                                'total' => $item['total'],
                                'total_cost' => $product->cost * $item['quantity'],
                                'discount' => $item['discount']
                            ];
                            $total_cost_sale = $total_cost_sale + $newProductInSale['total_cost'];
                            $productInSale = new ProductInSale($newProductInSale);
                            $productInSale->save();
                        }
                        $sale->cash_closing_id = CashClosing::where('user_id', '=', Auth::user()->id)->where('status', false)->pluck('id')->first();
                        $sale->total_cost = $total_cost_sale;
                        $sale->save();
                        DB::commit();
                        return response()->json(['success' => true, 'data' => Sale::where('id', $sale->id)->with(['productsInSale.product.category', 'branchOffice', 'user'])->first()]);
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        return response()->json(['success' => false, 'error' => $th]);
                    }
                } else {
                    return response()->json(['ERROR' => 'Verifique la venta, saldo de credito insuficiente']);
                }
            } else {
                $total_cost_sale = 0;
                // $request['branch_office_id'] = Auth::user()->branch_office_id;
                $sale['branch_office_id'] = Auth::user()->branch_office_id;
                // $request['user_id'] = Auth::user()->id;
                $sale['user_id'] = Auth::user()->id;

                if ($folioBranch == null) {
                    $sale['folio_branch_office'] = 1;
                } else {
                    $sale['folio_branch_office'] = $folioBranch + 1;
                }

                try {
                    DB::beginTransaction();
                    $shopping_cart_id = new AppShoppingCart();
                    $shopping_cart_id->save();
                    //AGREGAR PRODUCTOS DE LA VENTA
                    $comments = $oficce['comentario'];
                    $sale['shopping_cart_id'] = $shopping_cart_id->id;
                    if ($sale['card_ingress'] == null){
                        $sale['card_ingress'] = 0;
                    }
                    $sale = new Sale($sale);
                    $sale->save();
                    if($comments != null){
                        $commentSales = new CommentSales;
                        $commentSales->sale_id = $sale->id;
                        $commentSales->comentario = $comments;
                        $commentSales->save();
                    }
                    foreach ($request->all()["products"] as $key => $item) {
                        $product = Product::findOrFail($item['id']);

                            $product->stock = $product->stock - $item['quantity'];

                        $product->save();
                        $newProductInSale = [
                            'product_id' => $item['id'],
                            'sale_id' => $sale->id,
                            'quantity' => $item['quantity'],
                            'subtotal' => $item['subtotal'],
                            'sale_price' => $item['sale_price'],
                            'cost' => $item['costo'],
                            'total' => $item['total'],
                            'total_cost' => $product->cost * $item['quantity'],
                            'discount' => $item['discount']
                        ];
                        $total_cost_sale = $total_cost_sale + $newProductInSale['total_cost'];
                        $productInSale = new ProductInSale($newProductInSale);
                        $productInSale->save();
                    }
                    $sale->cash_closing_id = CashClosing::where('user_id', '=', Auth::user()->id)->where('status', false)->pluck('id')->first();
                    $sale->total_cost = $total_cost_sale;
                    $sale->save();
                    DB::commit();
                    return response()->json(['success' => true, 'data' => Sale::where('id', $sale->id)->with(['productsInSale.product.category', 'branchOffice', 'user'])->first()]);
                } catch (\Throwable $th) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'error' => $th]);
                }
            }
        } elseif ($sale['payment_type'] == 2) {
            return response()->json(['ERROR' => 'Verifique la venta, seleccione un cliente para credito']);
        } else {
            $total_cost_sale = 0;
            // $request['branch_office_id'] = Auth::user()->branch_office_id;
            $sale['branch_office_id'] = Auth::user()->branch_office_id;
            // $request['user_id'] = Auth::user()->id;
            $sale['user_id'] = Auth::user()->id;

            if ($folioBranch == null) {
                $sale['folio_branch_office'] = 1;
            } else {
                $sale['folio_branch_office'] = $folioBranch + 1;
            }

            try {
                DB::beginTransaction();
                $shopping_cart_id = new AppShoppingCart();
                $shopping_cart_id->save();
                //AGREGAR PRODUCTOS DE LA VENTA
                $comments = $oficce['comentario'];
                $sale['shopping_cart_id'] = $shopping_cart_id->id;
                if ($sale['card_ingress'] == null){
                    $sale['card_ingress'] = 0;
                }
                $sale = new Sale($sale);
                $sale->save();
                if($comments != null){
                    $commentSales = new CommentSales;
                    $commentSales->sale_id = $sale->id;
                    $commentSales->comentario = $comments;
                    $commentSales->save();
                }
                foreach ($request->all()["products"] as $key => $item) {
                    $product = Product::findOrFail($item['id']);

                        $product->stock = $product->stock - $item['quantity'];

                    $product->save();
                    $newProductInSale = [
                        'product_id' => $item['id'],
                        'sale_id' => $sale->id,
                        'quantity' => $item['quantity'],
                        'subtotal' => $item['subtotal'],
                        'sale_price' => $item['sale_price'],
                        'cost' => $item['costo'],
                        'total' => $item['total'],
                        'total_cost' => $product->cost * $item['quantity'],
                        'discount' => $item['discount']
                    ];
                    $total_cost_sale = $total_cost_sale + $newProductInSale['total_cost'];
                    $productInSale = new ProductInSale($newProductInSale);
                    $productInSale->save();

                }
                $sale->cash_closing_id = CashClosing::where('user_id', '=', Auth::user()->id)->where('status', false)->pluck('id')->first();
                $sale->total_cost = $total_cost_sale;
                $sale->save();
                DB::commit();
                return response()->json(['success' => true, 'data' => Sale::where('id', $sale->id)->with(['productsInSale.product.category', 'branchOffice', 'user'])->first()]);
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json(['success' => false, 'error' => $th]);
            }
        }
    }

    public function search(Request $request)
    {
        $user = auth()->user();

        if($user->rol_id == 2 || $user->rol_id == 3){

                $datas = Product::join('brands', 'products.brand_id', 'brands.id')
                ->join('categories', 'products.category_id', 'categories.id')
                    ->where("products.name", "LIKE", "%{$request->search}%")
                    ->where("products.stock", ">", 0)->where("products.status", "=", true)
                    ->where('products.branch_office_id', Auth::user()->branch_office_id)
                    ->select('products.*', 'brands.name as brand_name', 'brands.id as brand_id','categories.name as category_name')
                    ->orderBy('products.name', 'ASC')
                    ->get();
                if(count($datas) == 0)
                {
                    $datas = Product::join('brands', 'products.brand_id', 'brands.id')
                     ->join('categories', 'products.category_id', 'categories.id')

                    ->where("brands.name", "LIKE", "%{$request->search}%")
                    ->where("products.stock", ">", 0)->where("products.status", "=", true)
                    ->where('products.branch_office_id', Auth::user()->branch_office_id)
                    ->select('products.*', 'brands.name as brand_name', 'brands.id as brand_id','categories.name as category_name')
                    ->orderBy('products.name', 'ASC')
                    ->get();
                }

            return response()->json($datas);
        }
      if($user->rol_id == 1)
      {
           $datas = Product::join('brands', 'products.brand_id', 'brands.id')
                ->join('categories', 'products.category_id', 'categories.id')
                ->join('branch_offices','products.branch_office_id','branch_offices.id')
                ->where("products.name", "LIKE", "%{$request->search}%")
                ->where("products.stock", ">", 0)->where("products.status", "=", true)
                ->orWhere('branch_offices.name', "LIKE", "%{$request->search}%")
                ->orWhere("brands.name", "LIKE", "%{$request->search}%")
                ->where("branch_offices.status",1)
                ->select('products.*','products.cost as costo','branch_offices.name as office_name','brands.name as brand_name', 'brands.id as brand_id', 'categories.name as category_name', 'categories.id as category_id')
                ->orderBy('products.name', 'ASC')
                ->get();

        return response()->json($datas);
      }

    }

    public function searchClient(Request $request){
        $datas = Client::where("clients.name", "LIKE", "%{$request->search}%")
        ->where('clients.status', '=', true)
        ->orWhere('clients.last_name', "LIKE", "%{$request->search}%")
        ->where('clients.status', '=', true)
        ->get();
        return response()->json($datas);
    }

    public function searchByCode(Request $request)
    {
        $datas = Product::join('brands', 'products.brand_id', 'brands.id')
            ->join('categories', 'products.category_id', 'categories.id')
            ->where('products.branch_office_id', Auth::user()->branch_office_id)->where("products.bar_code", "=", $request->search)
            ->where("products.stock", ">", 0)->where("products.status", "=", true)
            ->select('products.*', 'brands.name as brand_name', 'brands.id as brand_id', 'categories.name as category_name', 'categories.id as category_id')
            ->get();


        return response()->json($datas);
    }

    public function productsByCategory($id)
    {

        $products = collect(
            json_decode(
                response()->json(
                    new ProductCollection(
                        Product::join('categories', 'products.category_id', 'categories.id')
                            ->where('products.category_id', $id)
                            ->where('products.status', 1)
                            ->select('products.*', 'categories.name as category_name', 'categories.id as category_id')
                            ->get()
                    )
                )->content()
            )->data
        );
        return response()->json($products);
    }
    public function reprint(Request $request)
    {
        $sale = Sale::where('id', $request->sale_id)->with(['branchOffice.address', 'productsInSale.product'])->first();
        return view('sales.ticket_new', ['sale' => $sale]);
    }

    public function sendReprint(Request $request)
    {
        $ahora = Carbon::now()->format('y-m-d');
        $send = SendProduct::where('transfer_id',$request->transfer_id)->get();
      /*  return view('reports.traspacing',compact('send'));*/
      $oficce = $send[0]->trasnfer->branchOffice->name;
      $fecha = $send[0]->trasnfer->created_at->format('d-m-y');
        return Excel::download(new ReportTransfer($request), "report-Transfer-to-$oficce-$fecha.xlsx");
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Sale $sale)
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
        $sale = Sale::findOrFail($id);
        $productInSale = ProductInSale::where('sale_id', $sale->id)->get();

        try {
            DB::beginTransaction();
            foreach ($productInSale as $item) {
                $product = Product::findOrFail($item->product_id);
                $product->stock = $product->stock + $item->quantity;
                $product->save();
            }
            $sale->changeStatus(false);
            DB::commit();
            return back()->with(["success" => "Éxito al realizar la operación."]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return back()->withErrors(["error" => "No se pudo realizar la operación."]);
        }
    }
    public function showDetails($id)
    {
        $details = ProductInSale::join('products', 'products.id', 'product_id')->where('sale_id', $id)->get();
        $sale = Sale::where('id', $id)->first();
        return view('sales.details', [
            'details' => $details,
            'sale' => $sale,
            'coment' => CommentSales::all(),
        ]);
    }
    public function showCanceledSale()
    {
        $sale = Sale::with('productsInSale')->where('status', false)->get();


        return view('sales.refound', ['sale' => $sale]);
    }

    public function showCaja()
    {
        if (Auth::user()->rol_id == 4) {
            return redirect()->route('almacen.index');
        }
        else {
            $branches;
            $traspacing = BranchOffice::where('status',1)->get();
            if (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3) {
                $branches = BranchOffice::all();
            } else {
                // return back()->withErrors(["error" => "No tienes permisos"]);
                $branches = [Auth::user()->branchOffice];
            }
            $d = new DateTime('NOW',new DateTimeZone('America/Mexico_City'));
            $from = $d->format('Y-m-d');
            $to = date('Y-m-d', strtotime('-7 day', strtotime($from)));
            $ventas =ProductInSale::join("sales" ,"sales.id", "=" ,"product_in_sales.sale_id")
                ->join("users","users.id","=","sales.user_id")
                ->join("products","products.id","=","product_in_sales.product_id")
                ->join("brands","brands.id","=","products.brand_id")
                ->join("categories","categories.id","=","products.category_id")
                ->select(DB::raw("product_in_sales.product_id as product_id,
                products.name as product_name,
                brands.name as brand,
                categories.name as category,
                sum(product_in_sales.quantity) as quantity,
                products.cost as cost,
                product_in_sales.sale_price as sale_price,
                product_in_sales.discount as discount,
                sum(product_in_sales.total_cost) as total_cost,
                sum(product_in_sales.total) as total,
                users.name as seller,
                users.last_name as seller_lastName,
                product_in_sales.created_at as date"))
                ->whereBetween('sales.created_at',[$to, $from])
                ->where("sales.status",  "=", true)
                ->groupBy("product_in_sales.product_id","product_in_sales.sale_price")
                ->orderBy('quantity', 'DESC')
                ->get();
            $userBranchOficce = BranchOffice::where('id', "=", Auth::user()->branch_office_id)->get();
            if (CashClosing::where('user_id', '=', Auth::user()->id)->where('status', '=', false)->count() == 0) {
                return view('sales.create', ["branches" => $branches, 'traspacing'=>  $traspacing, "userAuth" => $userBranchOficce,]);
            } else {
                return view('sales.create', [
                    'box' => CashClosing::where('user_id', '=', Auth::user()->id)->where('status', '=', false)->first(),
                    "branches" => $branches,
                    'categories' => Category::all(),
                    'clients' => Client::where('status', true)->get(),
                    'traspacing' =>  $traspacing,
                    "ventasS" => $ventas,
                    "userAuth" => $userBranchOficce,
                ]);
            }
        }

    }
}

class service
{
    public $quantity;
    public $name;

    public function __construct($quantity = '', $name = '')
    {
        $this->name = $name;
        $this->quantity = $quantity;
    }
}
