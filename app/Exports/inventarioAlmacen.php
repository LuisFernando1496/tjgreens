<?php

namespace App\Exports;

use App\Inventory;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
class inventarioAlmacen implements FromView
{
    public function view(): View
    {
        $productos = Inventory::with(['marca', 'categoria', 'almacen'])->get();
        return view('warehouse.inventarioAlmacenExcel', compact('productos'));
    }
}
