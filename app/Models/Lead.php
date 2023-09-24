<?php

namespace App\Models;

use App\Traits\HasComments;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Tags\HasTags;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Lead extends Model
{
    use HasFactory, HasTags, HasRelationships, LogsActivity, HasComments;

    protected $fillable = ['concerns', 'status', 'client_id', 'assigned_to', 'source', 'received_on', 'service_type'];

    protected $casts = [
        'received_on' => 'date',
    ];

    public function sites(): HasManyDeep
    {
        return $this->hasManyDeep(Site::class, [Client::class, Address::class], ['id', 'client_id'])
            ->withIntermediate(Address::class)
            ->withIntermediate(Client::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function addresses(): HasManyThrough
    {
        return $this->hasManyThrough(Address::class, Client::class);
    }

    public function entom(): HasOne
    {

        return $this->hasOne(Entom::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
