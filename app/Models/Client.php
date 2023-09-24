<?php

namespace App\Models;

use App\Traits\HasComments;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Tags\HasTags;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Illuminate\Support\Str;

class Client extends Model
{
    use HasFactory, LogsActivity, HasRelationships, CausesActivity, HasComments, HasTags;

    protected $fillable  = ['name', 'contact_information', 'classification'];

    protected $casts = [
        'contact_information' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function lead(): HasOne
    {
        return $this->hasOne(Lead::class);
    }

    public function sites(): HasManyThrough
    {
        return $this->hasManyThrough(Site::class, Address::class);
    }

    public function jobOrders(): HasManyThrough
    {
        return $this->hasManyThrough(JobOrder::class, Address::class);
    }

    public function entom(): HasMany
    {
        return $this->hasMany(Entom::class);
    }

    public function fullAddress()
    {
        $fullAddress = '';
        foreach ($this->addresses as $address) {
            $fullAddress .= $address->street . ' ' . $address->barangay?->name . ' ' . $address->city->name . '<br/>';
        }
        return Str::of($fullAddress)->toHtmlString();
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    public function concerns(): HasMany
    {
        return $this->hasMany(Concern::class);
    }
}
