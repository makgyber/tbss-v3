<?php

namespace App\Models;

use App\Traits\HasActivityStatusInterval;
use App\Traits\HasComments;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Contract extends Model implements HasMedia
{
    use HasFactory, LogsActivity, InteractsWithMedia, HasComments, HasActivityStatusInterval;

    protected $fillable = [
        'client_id', 'contract_type', 'code', 'engagement',
        'visits', 'frequency', 'start', 'end', 'assigned_to', 'status',
        'contract_price', 'payment_status', 'payment_terms',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, "assigned_to", "id");
    }

    public function serviceOrders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class);
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function lead(): HasOne
    {
        return $this->hasOne(Lead::class);
    }

    public function entom(): HasOne
    {
        return $this->hasOne(Entom::class);
    }

    public function jobOrders(): MorphMany
    {
        return $this->morphMany(JobOrder::class, 'jobable');
    }

    public function addresses(): BelongsToMany
    {
        return $this->belongsToMany(Address::class)->withTimestamps();
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('allotment', "unit")
            ->withTimestamps();
    }

    public function latestJobOrder(): MorphOne
    {
        return $this->morphOne(JobOrder::class, 'jobable')->latestOfMany();
    }

    public function contractExtensions(): HasMany
    {
        return $this->hasMany(ContractExtension::class);
    }
}
