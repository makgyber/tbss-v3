<?php

namespace App\Models;

use App\Traits\HasComments;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\HasTags;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Illuminate\Database\Eloquent\Casts\Attribute;

class JobOrder extends Model implements HasMedia
{
    use HasFactory, HasRelationships, LogsActivity, HasTags, InteractsWithMedia, HasComments;

    protected $fillable = [
        'code', 'summary', 'target_date', 'completed', 'confirmed', 'client_id',
        'created_by', 'job_order_type', 'address_id', 'site_id',
        'jobable_type', 'jobable_id', 'status'
    ];

    protected $casts = [
        'target_date' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function jobable()
    {
        return $this->morphTo(__FUNCTION__, 'jobable_type', 'jobable_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot(['alloted', 'consumed'])
            ->withTimestamps();
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(Recommendation::class);
    }

    public function instructions(): HasMany
    {
        return $this->hasMany(Instruction::class);
    }

    public function findings(): HasMany
    {
        return $this->hasMany(Finding::class);
    }

    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class);
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function serviceInterval(): Attribute
    {
        return Attribute::make(
            get: function () {
                $started = null;
                $completed = null;
                foreach ($this->activities()->getResults() as $activity) {

                    foreach ($activity->properties as $item) {

                        if (isset($item['status']) && $item['status'] == 'started') {
                            $started = $item['updated_at'];
                        }
                        if (isset($item['status']) && $item['status'] == 'completed') {
                            $completed = isset($item['updated_at']) ? $item['updated_at'] : $this->updated_at;
                        }
                    }
                }
                if (is_null($started)) {
                    return 'Not started';
                }

                if (is_null($completed)) {
                    return 'Started ' . Carbon::make($started)->setTimezone('Asia/Manila')->format('Y-m-d h:i a');
                }

                return 'Completed ' . Carbon::make($started)->setTimezone('Asia/Manila')->format('Y-m-d h:i a') . ' - ' . Carbon::make($completed)->setTimezone('Asia/Manila')->format('Y-m-d h:i a');
            }
        );
    }

    public function concerns(): HasMany
    {
        return $this->hasMany(Concern::class);
    }

    protected static function booted(): void
    {
        static::created(function (JobOrder $jo) {
            $instructions = JobOrderType::where('name', $jo->job_order_type)
                ->get("instruction_list")->first()->instruction_list;

            if ($instructions) {
                foreach ($instructions as $instruction) {
                    Instruction::create([
                        'job_order_id' => $jo->id,
                        'instruction' => $instruction
                    ]);
                }
            }
        });
    }
}
