<?php
namespace App\Transformers;

use App\Models\Product;
use League\Fractal;

class ProductTransformer extends Fractal\TransformerAbstract
{

    public function transform(Product $product)
    {
        return [
            'id' => (int) $product->id,
            'product_name' => $product->product_name,
            'product_description' => $product->product_description,
            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
            'links' => [
                "uri" => "products/$product->id",
            ],
        ];
    }

}