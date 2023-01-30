<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'price' => 'float',
    ];

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attribute');
    }
}
