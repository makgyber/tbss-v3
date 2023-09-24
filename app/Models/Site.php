<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Site extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['label', 'contact_person', 'contact_number', 'type', 'serviceable_area'];

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
// 