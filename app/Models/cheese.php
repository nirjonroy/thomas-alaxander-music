<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cheese extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'product_id', 'name'];

    public function cheese()
    {
        return $this->belongsTo(Product::class);
    }
}
