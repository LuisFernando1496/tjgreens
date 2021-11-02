<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\BranchOffice;
use App\Address;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BranchOfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->rol_id == 1) {
            return view('branchOffice.index', ['office' => BranchOffice::where('status', '=', true)->get()]);
        } else {
            return back()->withErrors(["error" => "No se pudo realizar la operación."]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('branchOffice.create', ["branchOffice" => new BranchOffice, "Address" => new Address]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $offices = BranchOffice::all();
        DB::beginTransaction();

        if (Auth::user()->rol_id == 1) {
            if (count($offices) == 10) {
                return back()->withErrors(["error" => "Ya existen 10 sucursales registradas"]);
            } elseif (count($offices) < 10) {
                try {
                    $request['address_id'] = Address::create($request->all())->id;
                    BranchOffice::create($request->all());
                    DB::commit();
                    return redirect('BranchOffice');
                } catch (\Throwable $th) {
                    DB::rollback();
                    return $th->getMessage();
                }
            } else {
                //Nunca entra y esta OK
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
    public function show(BranchOffice $branchOffice)
    {
        return view("branchOffice.show", ["branchOffice" => $branchOffice]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(BranchOffice $branchOffice)
    {
        return view('branchOffice.edit', ["branchOffice" => $branchOffice]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BranchOffice $branchOffice)
    {
        DB::beginTransaction();
        try {
            $branchOffice->address->edit($request->all());
            $request['address_id'] = $branchOffice->address_id;
            $branchOffice->edit($request->all());
            DB::commit();
            return redirect('BranchOffice');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->withErrors(["error" => "Ah ocurrido un error al actualizar los datos"]);
            return $th->getMessage();
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
        if (Auth::user()->rol_id == 1) {
            try {
                $branchOffice = BranchOffice::findOrFail($id);
                $branchOffice->changeStatus(false);
                return redirect('BranchOffice');
            } catch (\Throwable $th) {
                return back()->withErrors(["error" => "Ocurrió un error al deshabilitar la sucursal"]);
                return $th->getMessage();
            }
        } else {
            return back()->withErrors(["error" => "No tienes permisos"]);
        }
    }
}
