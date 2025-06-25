<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
class Product extends Model
{
    protected $fillable = [
        'uuid',
        'name',
        'description',
        'price',
        'quantity',
        'user_id'
    ];

    /**
     * Add UUID to the Product Model
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    /**
     * Relationship with User Model
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
