<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable =[
        'name',
        'description',
        'price',
        'quantity'
    ];

    /**
     * Relationship with User Model
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
