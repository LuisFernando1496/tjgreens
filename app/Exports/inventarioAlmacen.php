<?php

namespace App\Exports;

use App\Inventory;
use App\BranchOffice;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
class inventarioAlmacen implements FromView
{
    private $dataGlobal;
    public function __construct($option)
    {
        $this->dataGlobal = $option;
    }
    
    public function view(): View
    {
        if ($this->dataGlobal == 'Todos') {
        $productos = Inventory::with(['marca', 'categoria', 'almacen'])->get();
        return view('warehouse.inventarioAlmacenExcel', compact('productos'));
        }
        else{
            $branch = BranchOffice::find($this->dataGlobal);
            $productos = Inventory::leftJoin('branch_prices','branch_prices.inventory_id','inventories.id')
                                  ->where('branch_prices.office_id',$this->dataGlobal)
                                  ->with(['marca', 'categoria', 'almacen'])->get();
              return view('warehouse.inventarioSucursalAlmacen', compact('productos','branch'));
        }
    }
}
