<?php

namespace App\Http\Controllers;

use App\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProviderController extends Controller
{
    public function index()
    {
        if (Auth::user()->rol_id == 1) {
            $providers = Provider::all();
            return view('provider.index', ['providers' => $providers]);
        } else {
            return back()->withErrors(["error" => "No se pudo realizar la operación."]);
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->rol_id == 1) {
            $provider = new Provider($request->all());
            if ($provider->save()) {
                return back()->with(["success" => "Éxito al realizar la operación."]);
            } else {
                return back()->withErrors(["error" => "No se pudo realizar la operación."]);
            }
        } else {
            return back()->withErrors(["error" => "No tienes permisos"]);
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->rol_id == 1) {
            $provider = Provider::findOrFail($id);

            if ($provider->update($request->all())) {
                return back()->with(["success" => "Éxito al realizar la operación."]);
            } else {
                return back()->withErrors(["error" => "No se pudo realizar la operación."]);
            }
        } else {
            return back()->withErrors(["error" => "No tienes permisos."]);
        }
    }

    public function destroy($id){
        if (Auth::user()->rol_id == 1) {
            $provider = Provider::findOrFail($id);
            // $newStatus['status'] = false;

            if ($provider->delete()) {
                return back()->with(["success" => "Éxito al realizar la operación."]);
            } else {
                return back()->withErrors(["error" => "No se pudo realizar la operación."]);
            }
        } else {
            return back()->withErrors(["error" => "No tienes permisos"]);
        }
    }
}
