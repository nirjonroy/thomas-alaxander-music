<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class protin extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'product_id', 'name', 'protin_price'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
