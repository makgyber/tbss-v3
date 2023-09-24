<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Team extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['user_id', 'code'];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->as('technicians')->withTimestamps();
    }

    public function jobOrders(): BelongsToMany
    {
        return $this->belongsToMany(JobOrder::class)->withTimestamps()->orderBy("target_date", "asc");
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function tracker(): HasOne
    {
        return $this->hasOne(Tracker::class);
    }
}
