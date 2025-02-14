<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sauce extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'product_id', 'name'];

    public function sauce()
    {
        return $this->belongsTo(sauce::class);
    }
}
