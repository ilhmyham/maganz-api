<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Internship extends Model
{
    protected $fillable = [
        'company_id', 'title', 'description', 'location',
        'required_skills', 'start_date', 'end_date', 'is_active'
    ];

    public function company() : BelongsTo
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
}
