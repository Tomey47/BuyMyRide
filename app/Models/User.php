<?php

namespace App\Models;

use App\Notifications\CustomVerifyEmailNotification;
use App\Notifications\CustomResetPasswordNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'phone_number',
        'avatar',
        'is_admin',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmailNotification);
    }

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoriteCars()
    {
        return $this->belongsToMany(Car::class, 'favorites')->withTimestamps();
    }

    public function ratingsGiven()
    {
        return $this->hasMany(Rating::class, 'user_id');
    }

    public function ratingsReceived()
    {
        return $this->hasMany(Rating::class, 'rated_user_id');
    }

    public function averageRating()
    {
        return $this->ratingsReceived()->avg('rating') ?? 0;
    }

    public function totalRatings()
    {
        return $this->ratingsReceived()->count();
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function unreadNotifications()
    {
        return $this->notifications()->where('is_read', false);
    }
}
