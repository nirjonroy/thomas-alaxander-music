<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productVariation extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'product_id', 'name', 'products_price'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
