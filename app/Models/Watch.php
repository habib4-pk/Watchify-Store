<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Watch extends Model
{
    protected $fillable = ['name', 'price', 'discount_percentage', 'description', 'image', 'stock', 'featured'];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get all images for this watch.
     */
    public function images()
    {
        return $this->hasMany(WatchImage::class)->orderBy('sort_order');
    }

    /**
     * Get the primary image URL, fallback to main image column.
     */
    public function getPrimaryImageAttribute()
    {
        $primary = $this->images()->where('is_primary', true)->first();
        return $primary ? $primary->image_url : $this->image;
    }

    /**
     * Get all image URLs including the main image.
     */
    public function getAllImagesAttribute()
    {
        $images = $this->images->pluck('image_url')->toArray();
        if (empty($images) && $this->image) {
            return [$this->image];
        }
        return $images;
    }

    /**
     * Check if product has a discount.
     */
    public function getHasDiscountAttribute()
    {
        return $this->discount_percentage > 0;
    }

    /**
     * Get the discounted price.
     */
    public function getDiscountedPriceAttribute()
    {
        if (!$this->has_discount) {
            return $this->price;
        }
        return round($this->price * (1 - $this->discount_percentage / 100));
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
