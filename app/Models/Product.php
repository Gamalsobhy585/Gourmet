<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'sku',
        'description',
        'category_id'
    ];
    protected $appends = ['price_for_store_b'];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

   
    public function getPriceInStoreBAttribute()
    {
        $price = $this->price * (1 + $this->category->B_percentage / 100);
        return round($price, 2);  
    }
    
}