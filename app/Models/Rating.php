<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'user_id',
        'rated_user_id',
        'rating',
        'comment'
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ratedUser()
    {
        return $this->belongsTo(User::class, 'rated_user_id');
    }
}
