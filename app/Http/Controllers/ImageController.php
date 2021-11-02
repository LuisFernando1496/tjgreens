<?php

namespace App\Http\Controllers;

use App\Image;
use Illuminate\Http\Request;
use App\Http\Requests\StoreImage;
use Illuminate\Support\Facades\Storage;
use DB;
class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(StoreImage $request)
    {
        DB::beginTransaction();
        try {
            $path = Storage::disk('s3')->put('images/products', $request->file);
            $request->merge([
                'size' => $request->file->getSize(),
                'path' => $path
            ]);
            Image::create($request->only('path', 'title', 'size', 'product_id'));
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Imagen almacenada con exito.'],200);
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
            //throw $th;
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function show(Image $image)
    {
        return $image->getUrlAttribute();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function edit(Image $image)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Image $image)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy(Image $image)
    {
        Storage::disk('s3')->delete($image->path);
        $image->del();
        return 'Todo ok';
    }
}
