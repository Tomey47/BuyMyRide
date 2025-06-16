<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'user_id', 'make', 'model', 'year', 'price',
        'body_type', 'transmission', 'fuel_type', 'mileage',
        'color', 'description', 'location', 'is_active',
        'show_email', 'is_approved', 'status', 'is_reported'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_approved' => 'boolean',
        
    ];

    public function images()
    {
        return $this->hasMany(CarImage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }
}
