<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Events\UserCreated;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'plan_rate_limit'
    ];
    protected $dispatchesEvents=[
        'created' => UserCreated::class,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function Assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function posts():HasOne
    {
        return $this->hasOne(Post::class)->oldestOfMany();
    }
    public function comments():HasMany
    {
        return $this->hasMany(Comment::class,'user_id','id');
    }
    public function emergencyPost(): HasOne
    {
        return $this->hasOne(Post::class)->ofMany('created_at',
            'max');
    }
    public function contacts():BelongsToMany
    {
        return $this->belongsToMany(Contact::class);
    }

    public function stars():HasMany
    {
        return $this->hasMany(Star::class);
    }
}
