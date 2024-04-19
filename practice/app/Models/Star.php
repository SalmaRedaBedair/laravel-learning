<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Star extends Model
{
    use HasFactory;
    protected $fillable = ['user_id'];
    public function starrable()
    {
        return $this->morphTo();
        // The relationship is defined by two columns in the database: `starrable_type` and `starrable_id`.
        //` starrable_type` stores the class name of the related model, while `starrable_id` stores the primary key value of the related model.
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
