<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    //
    protected $fillable = [
        'title', 'path', 'size', 'product_id'
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function getUrlAttribute()
    {
        return Storage::disk('s3')->url($this->path);
    }
    public function deleteImageS3($path)
    {
        Storage::disk('s3')->delete($path);;
    }
    public function getSizeInKbAttribute()
    {
        return round($this->size / 1024, 2);
    }
    public function del()
    {
        Storage::disk('s3')->delete($this->path);
        return $this->delete();
    }
}
