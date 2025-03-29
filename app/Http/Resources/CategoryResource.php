<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'B_percentage' => $this->B_percentage,
            'products_count' => $this->whenLoaded('products', function() {
                return $this->products->count();
            }),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'links' => [
                'self' => route('categories.show', $this->id),
                'products' => route('categories.products.index', $this->id),
            ]
        ];
    }
}