<?php

namespace App\Http\Controllers;

use App\BranchPrice;
use Illuminate\Http\Request;

class BranchPriceController extends Controller
{
    public function index()
    {
        //
    }

  
    public function create()
    {
        //
    }

   
    public function store(Request $request)
    {
        //
    }

    
    public function show($id)
    {
        
        $costBranche = BranchPrice::join('branch_offices','branch_offices.id','=','branch_prices.office_id')
                                   ->where('branch_offices.status',true)
                                   ->where('branch_prices.inventory_id',$id)
                                   ->whereNotNull('branch_prices.branch_cost')
                                   ->with('inventory','office')
                                   ->get();
        //return $costBranche;
        return response()->json($costBranche);
    }

  

}
