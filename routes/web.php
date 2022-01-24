<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShoppingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use App\Warehouse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Route::get('/', 'HomeController@mensajes');

Route::get('/', function () {
    return redirect('caja');
});


Auth::routes();
Route::get('/registro', 'UserController@create');
Route::post('/registro', 'UserController@store');
Route::get('/home', 'HomeController@index')->name('home');

//Route::resource('user', 'UserController');
Route::group(['middleware'=>'auth'], function(){

    //Rutas AJAX
    Route::get('/users-ajax',[UserController::class,'ajaxget']);

    Route::resource('users', 'UserController');
    Route::get('clients', 'UserController@indexClient');
    Route::resource('expense', 'ExpenseController');
    Route::get('/expenses', 'ExpenseController@create');
    Route::post('/expenses-create', 'ExpenseController@store');
    Route::post('changePassword/{id}', 'UserController@changePassword');
    Route::get('sale-detail/{id}', 'SaleController@showDetails');
    Route::get('sale-detail-history/{id}', 'ClientController@showDetailsHistory');
    Route::post('abonar', 'ClientController@abonar');
    Route::post('reprint', 'SaleController@reprint');
    Route::post('sendReprint', 'SaleController@sendReprint');
    Route::resource('marcas', 'BrandController');
    Route::resource('categorias', 'CategoryController');
    Route::get('perfil', function(){
        return view('user/profile',['user'=>auth()->user()]);
    });
    Route::resource('BranchOffice','BranchOfficeController');
    // Route::resource('expense', 'ExpenseController');
    Route::resource('reportes', 'ReportController');
    Route::get('employeeByOffice/{id}','ReportController@employeeByOffice');
    Route::get('reporte', function(){
        return view('reports/report',['user'=>auth()->user()]);
    });
    Route::resource('branchOffice','BranchOfficeController');

    Route::get('search', 'SaleController@search');
    Route::get('searchClient', 'SaleController@searchClient');
    Route::get('searchByCode', 'SaleController@searchByCode');
    Route::get('caja', 'SaleController@showCaja');
    Route::get('showCanceled', 'SaleController@showCanceledSale');
    Route::get('credits', 'ClientController@showCredits');
    Route::post('reprint/{id}', 'SaleController@reprint');
    Route::resource('expense', 'ExpenseController');
    Route::resource('sale', 'SaleController');
    Route::get('sales/busqueda','SaleController@buscarVen');
    Route::resource('purchase', 'PurchaseController');
    Route::get('purchase-history','PurchaseController@getHistory');
    Route::get('products/busqueda','ProductController@buscar');
    Route::post('products/guardar','ProductController@guardar');
    Route::post('purchase-history/download','PurchaseController@reportPurchase')->name('reportPurchase');
    Route::resource('provider', 'ProviderController');
    Route::get('sale/productsCategory/{id}', 'SaleController@productsByCategory');
    Route::resource('box','BoxController');
    Route::resource('cashClosing','CashClosingController');
    Route::resource('initialCash','InitialCashController');
    Route::post('closeBox/{cashClosing}','CashClosingController@closeBox');
    Route::post('/validatePromotion', 'UserController@checkAdmin');
    Route::get('getBox/{branchOffice}','BoxController@getAvailableBoxByBranchOfficeId');

    Route::post('reporte/generalReport', 'ReportController@generalReport');
    Route::post('reporte/download/generalReport', 'ReportController@generalReportDownload');
    Route::post('reporte/download/excel/generalReport', 'ReportController@generalReportDownloadExcel');
    Route::post('reporte/branchOffice', 'ReportController@branchOfficeReport');
    Route::post('reporte/download/branchOffice', 'ReportController@branchOfficeReportDownload');
    Route::post('reporte/download/excel/branchOffice', 'ReportController@branchOfficeReportDownloadExcel');
    Route::post('reporte/userReport', 'ReportController@userReport');
    Route::post('reporte/download/userReport', 'ReportController@userReportDownload');
    Route::post('reporte/download/excel/userReport', 'ReportController@userReportDownloadExcel');
    Route::post('reporte/cutBox', 'ReportController@CutBox');
    Route::post('reporte/download/cutBox', 'ReportController@CutBoxDownload');
    Route::post('reporte/download/excel/cutBox', 'ReportController@CutBoxDownloadExcel');
    Route::get('reporte/reportInvent', 'ReportController@invent');
    Route::get('reporte/download/reportInvent', 'ReportController@inventDownload');
    Route::get('reporte/download/excel/reportInvent', 'ReportController@inventDownloadExcel');

    Route::post('reporte/reportInventByBranchOfficeId', 'ReportController@inventByBranchOfficeId');
    Route::post('reporte/download/reportInventByBranchOfficeId', 'ReportController@inventByBranchOfficeIdDownload');
    Route::post('reporte/download/excel/reportInventByBranchOfficeId', 'ReportController@inventByBranchOfficeIdDownloadExcel');
    Route::post('/product-del', 'UserController@create');
    Route::resource('image','ImageController', ['only' => ['store', 'destroy']]);
    Route::resource('product', 'ProductController')->except(['update']);
    Route::post('product/{product}', 'ProductController@update');
    Route::resource('transfers','TransferController');

    Route::get('/stock','ProductController@stock');

    Route::get('/tag/{product}','ProductController@tag')->name('tag');

    Route::get('/almacen',[WarehouseController::class,'index'])->name('almacen.index');
    Route::post('/almacen',[WarehouseController::class,'store'])->name('almacen.store');
    Route::patch('/almacen-status/{id}',[WarehouseController::class,'status'])->name('almacen.status');
    Route::patch('/almacen/{almacen}',[WarehouseController::class,'update'])->name('almacen.update');
    Route::get('/ventas',[WarehouseController::class,'ventas'])->name('almacen.ventas');

    Route::post('/inventario',[InventoryController::class,'store'])->name('inventario.store');
    Route::patch('/inventario/{id}',[InventoryController::class,'update'])->name('inventario.update');

    Route::post('/addInventario/{id}',[CartController::class,'store'])->name('add');
    Route::get('/getCarrito',[CartController::class,'index']);
    Route::post('/concluir',[CartController::class,'concluir'])->name('concluir');
    Route::patch('/venta-pagada/{id}',[CartController::class,'pagado'])->name('venta.pagada');

    Route::get('/getOrder',[WarehouseController::class,'generateOrder'])->name('getOrder');

    Route::post('/addCompra/{id}',[CartController::class,'addcart'])->name('addCompra');
    Route::post('/concluirCompra',[CartController::class,'concluirCompra'])->name('concluir.compra');

    Route::post('/reporteVentas',[ShoppingController::class,'ventas'])->name('reporte.ventas');
    Route::post('/reporteCompras',[ShoppingController::class,'compras'])->name('reporte.compras');
    Route::get('/ticket-venta/{id}',[WarehouseController::class,'ticket'])->name('generate.ticket');

});

Route::get('/productos',[ProductController::class,'allProductos']);
Route::get('/productos/{name}',[ProductController::class,'searchProduct']);


// Route::post('/upload-image', 'ImageController@store');
// Route::get('/image/{image}', 'ImageController@show');
