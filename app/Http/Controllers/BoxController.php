<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Box;
use App\BranchOffice;

use DB;
class BoxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if(Auth::user()->rol_id == 1){
            return view('boxes.index',["boxes" => Box::where('status', '=',true)->get(),"branch_office" => BranchOffice::where('status','=',true)->get()]);
        }else{
            return view('boxes.index',["boxes" => Box::where('branch_office_id', '=',Auth::user()->branch_office_id)->where('status', '=',true)->get(),"branch_office" =>[Auth::user()->branchOffice]]);
        }
    }


    public function getAvailableBoxByBranchOfficeId(BranchOffice $branchOffice)
    {
        return Box::where("branch_office_id","=",$branchOffice->id)->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->rol_id == 1){
            return view('box.create',["box" => new Box,"branch_office" => BranchOffice::all()]);
        }else{
            return view('box.create',["box" => new Box,"branch_office" =>Auth::user()->branchOffice]);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // $request["branch_office_id"] = Auth::user()->branchOffice->id;
            Box::create($request->all());
            DB::commit();
            return back()->with(["success"=>"Éxito al realizar la operación."]);
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->withErrors(["error"=>"No se pudo realizar la operación."]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Box $box)
    {
        return view("box.show",["box" => $box]);
    }
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Box $box)
    {
        return view('box.edit',["box" => $box]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Box $box)
    {
        DB::beginTransaction();
        try {
            //BRANCH OFFICE SOLO ES DEL USER LOGEADO? 
            // $request["branch_office_id"] = Auth::user()->branchOffice->id;
            $box->edit($request->all());
            DB::commit();
            return back()->with(["success"=>"Éxito al realizar la operación."]);
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->withErrors(["error"=>"No se pudo realizar la operación."]);
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
