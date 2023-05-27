<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'product_code',
        'purchase_price',
        'sale_price',
        'discount',
        'quantity'
    ];

    public function productDetails()
    {
        return $this->HasMany(ProductDetail::class);
    }

    public function legders()
    {
        return $this->HasMany(Ledger::class);
    }
}
