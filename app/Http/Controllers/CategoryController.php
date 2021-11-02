<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->rol_id == 1) {
            $categories = Category::where('status', true)->get();
            return view('products/category', ['categories' => $categories]);
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
        $category = new Category();
        return view('products/category', ['category' => $category]);
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
            $category = new Category([
                'name' => $request['name'],
                'status' => true
            ]);

            if ($category->save()) {
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
        $category = Category::findOrFail($id);
        return view('products/category', ['category' => $category]);
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
            $category = Category::findOrFail($id);
            if ($category->update($request->all())) {
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
            $category = Category::findOrFail($id);
            $newStatus['status'] = false;

            if ($category->update($newStatus)) {
                return back()->with(["success" => "Éxito al realizar la operación."]);
            } else {
                return back()->withErrors(["error" => "No se pudo realizar la operación."]);
            }
        }else{
            return back()->withErrors(["error" => "No tienes permisos."]);
        }
    }
}
