<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vaggi extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'product_id', 'name'];

    public function vaggi()
    {
        return $this->belongsTo(vaggi::class);
    }
}
