<?php

namespace App\Http\Resources\V2\Seller;

use App\Models\UploadProducts;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'data' => $this->collection->map(function ($data) {
                $qty = 0;
                foreach ($data->stocks as $key => $stock) {
                    $qty += $stock->qty;
                }
                return [
                    'id' => $data->id,
                    'name' => $data->name,
                    //'thumbnail_img' => uploaded_asset($data->thumbnail_img),
                    'thumbnail_img' => UploadProducts::where('id_product', $data->id)
                    ->where('type', 'thumbnails')
                    ->value('path') != null ? static_asset(UploadProducts::where('id_product', $data->id)
                    ->where('type', 'thumbnails')
                    ->value('path')) : null,
                    //'price' => format_price($data->unit_price),
                    'price' => format_price($data->getPricingConfiguration()->first()->value("unit_price")),
                    'current_stock' => $qty,
                    'status' => $data->published == 0 ? false : true,
                    'category' => $data->main_category ? $data->main_category->getTranslation('name') : "",
                    'featured' => $data->seller_featured == 0 ? false : true,
                ];
            }),

        ];
    }
}
