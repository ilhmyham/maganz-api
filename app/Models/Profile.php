<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'address',
        'photo_url',
        'birthdate',
        'gender',
        'university',
        'skills',
        'company_name',
        'company_description'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
