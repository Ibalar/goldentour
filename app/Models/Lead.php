<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'message',
        'service_id',
        'calculated_price',
        'calculated_area',
        'source',
        'status',
        'ip_address',
        'user_agent',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'processed_at',
        'processed_by',
    ];

    protected $casts = [
        'calculated_price' => 'decimal:2',
        'calculated_area' => 'integer',
        'processed_at' => 'datetime',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(\MoonShine\Models\MoonshineUser::class, 'processed_by');
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function isNew(): bool
    {
        return $this->status === 'new';
    }
}
