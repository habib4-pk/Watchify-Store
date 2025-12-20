<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'watch_id', 'quantity'];

    // Link to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Link to watch (product)
    public function watch()
    {
        return $this->belongsTo(Watch::class);
    }
}
