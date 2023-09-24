<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name'];

    public function provinces(): HasMany
    {
        return $this->hasMany(Province::class);
    }

    public function address(): HasMany
    {
        return $this->hasMany(Address::class);
    }
}
