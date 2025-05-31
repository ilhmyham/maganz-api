<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = ['nama'];

    // Satu role bisa punya banyak user (one-to-many)
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
