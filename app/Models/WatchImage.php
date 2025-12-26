<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WatchImage extends Model
{
    protected $fillable = ['watch_id', 'image_url', 'sort_order', 'is_primary'];

    protected $casts = [
        'is_primary' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the watch that owns this image.
     */
    public function watch()
    {
        return $this->belongsTo(Watch::class);
    }
}
