<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Brand;

class BrandController extends Controller
{
    public function index()
    {
        if (Auth::user()->rol_id == 1) {
            $brands = Brand::where('status', true)->get();
            return view('products/brand', ['brands' => $brands]);
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
        $brand = new Brand();
        return view('products/brand', ['brand' => $brand]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->rol_id == 1) {
            $brand = new Brand([
                'name' => $request['name'],
                'status' => true
            ]);
            if ($brand->save()) {
                return back()->with(["success" => "Éxito al realizar la operación."]);
            } else {
                return back()->withErrors(["error" => "No se pudo realizar la operación."]);
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
        $brand = Brand::findOrFail($id);
        return view('products/brand', ['brand' => $brand]);
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
        if (Auth::user()->rol_id == 1) {
            $brand = Brand::findOrFail($id);

            if ($brand->update($request->all())) {
                return back()->with(["success" => "Éxito al realizar la operación."]);
            } else {
                return back()->withErrors(["error" => "No se pudo realizar la operación."]);
            }
        } else {
            return back()->withErrors(["error" => "No tienes permisos."]);
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
            $brand = Brand::findOrFail($id);
            $newStatus['status'] = false;

            if ($brand->update($newStatus)) {
                return back()->with(["success" => "Éxito al realizar la operación."]);
            } else {
                return back()->withErrors(["error" => "No se pudo realizar la operación."]);
            }
        } else {
            return back()->withErrors(["error" => "No tienes permisos"]);
        }
    }
}
