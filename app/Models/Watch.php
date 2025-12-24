<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Watch extends Model
{
    protected $fillable = ['name', 'price', 'description', 'image', 'stock', 'featured'];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }
    public function reviews()
{
    return $this->hasMany(Review::class);
}

}
