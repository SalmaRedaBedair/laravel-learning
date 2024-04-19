<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'content'];


    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function comments():HasMany
    {
        return $this->hasMany(Comment::class,'post_id','id');
    }

//    protected function title():Attribute
//    {
//        return Attribute::make(
//            get: fn ($value) => strtoupper($value),
//        );
//    }
    protected function title():Attribute
    {
        return Attribute::make(
            set: fn ($value) => strtoupper($value),
        );
    }
}
