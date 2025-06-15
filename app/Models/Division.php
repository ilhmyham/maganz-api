<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Division extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'company_id'];

    // Sebuah Divisi memiliki banyak Lowongan
    public function internships() : HasMany
    {
        return $this->hasMany(Internship::class);
    }

    public function company() : BelongsTo
    {
        return $this->belongsTo(User::class, 'company_id');
    }
}
