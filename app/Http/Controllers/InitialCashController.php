<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\InitialCash;

class InitialCashController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $amount = InitialCash::all();
        if (count($amount)==1) {
            return view ('layouts.InitialCash',['amount'=>$amount,'flag'=>true]);
        } else {
            return view ('layouts.InitialCash',['amount'=>$amount]);
        }
        
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $amount = new InitialCash($request->all());

        if ($amount->save()) {
            return redirect('initialCash');
        } else {
            return back()->withErrors(['error'=>"Ups!, ah ocurrido un error, ya estamos en ello ;)."]);
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
        $amount = InitialCash::findOrFail($id);
        if ($amount->update($request->all())) {
            return redirect('initialCash');
        } else {
            return back()->withErrors(['error'=>"Ups!, ah ocurrido un error, ya estamos en ello ;)."]);
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
        //
    }
}
