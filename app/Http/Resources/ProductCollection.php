<?php

namespace App\Http\Resources;

use App\Image;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\ProductImage;
use App\Product;

class ProductCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($element) {
                $temp = Product::where('id', $element->id)->first();
                $imgTemp = Image::where('product_id', $temp->id)->pluck('path')->first();
                if ($imgTemp == null) {
                    $img = 'https://granrueda-bucket.s3.amazonaws.com/images/products/Lr3Lmw5MWl2Dduxm6ib9deNQJKUY4PKQ9U3fzuc6.jpeg';
                } else {
                    $img = 'https://granrueda-bucket.s3.amazonaws.com/' . $imgTemp;
                }
                if ($element->brand == null) {
                    return [
                        'id' => $element->id,
                        'name' => $element->name,
                        'stock' => $element->stock,
                        'cost' => $element->cost,
                        'price_1' => $element->price_1,
                        'price_2' => $element->price_2,
                        'price_3' => $element->price_3,
                        'bar_code' => $element->bar_code,
                        'category_name' => $element->category->name,
                        'brand_name' => $element->brand,
                        'image' => $img,
                        'status' => $element->status
                    ];
                } else {
                    return [
                        'id' => $element->id,
                        'name' => $element->name,
                        'stock' => $element->stock,
                        'cost' => $element->cost,
                        'price_1' => $element->price_1,
                        'price_2' => $element->price_2,
                        'price_3' => $element->price_3,
                        'bar_code' => $element->bar_code,
                        'category_name' => $element->category->name,
                        'brand_name' => $element->brand->name,
                        'image' => $img,
                        'status' => $element->status
                    ];
                }
            })
        ];
    }
}
