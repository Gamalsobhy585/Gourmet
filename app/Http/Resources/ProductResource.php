<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'SKU' => $this->SKU,
            'name' => $this->name,
            'description' => $this->description,
            'category' => $this->whenLoaded('category', function () {
                return [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                ];
            }),
            'price' => $this->price,
            'price_in_store_b' => $this->price_in_store_b,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,

        ];
    }
    public static function collection($resource)
    {
        $collection = parent::collection($resource);
        
        return $collection->additional([
            'pagination' => [
                'total' => $resource->total(),
                'count' => $resource->count(),
                'per_page' => $resource->perPage(),
                'current_page' => $resource->currentPage(),
                'total_pages' => $resource->lastPage(),
                'from' => $resource->firstItem(),
                'to' => $resource->lastItem(),
                'previous_page' => $resource->previousPageUrl(),
                'next_page' => $resource->nextPageUrl(),
                'has_more_pages' => $resource->hasMorePages(),
            ]
        ]);
    }

}