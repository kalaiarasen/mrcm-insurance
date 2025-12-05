<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
    ];

    /**
     * Check if this type is used by any products
     */
    public function isUsed(): bool
    {
        return Product::where('type', $this->name)->exists();
    }

    /**
     * Get count of products using this type
     */
    public function productsCount(): int
    {
        return Product::where('type', $this->name)->count();
    }
}
