<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'short_description',
        'full_description',
        'price_from',
        'price_to',
        'area_from',
        'area_to',
        'duration',
        'image',
        'gallery',
        'features',
        'is_active',
        'show_in_menu',
        'meta_title',
        'meta_description',
        'meta_keywords',
        // Блок "Почему выбирают"
        'why_choose_title',
        'why_choose_subtitle',
        'why_choose_items',
        // Блок "Что входит"
        'offer_title',
        'offer_subtitle',
        'offer_list',
        'offer_items',
        // Блок "Процесс работы"
        'process_title',
        'process_subtitle',
        'process_items',
        'process_image',
        // Блок FAQ
        'faq_title',
        'faq_items',
    ];

    protected $casts = [
        'price_from' => 'decimal:2',
        'price_to' => 'decimal:2',
        'area_from' => 'integer',
        'area_to' => 'integer',
        'features' => 'array',
        'gallery' => 'array',
        'is_active' => 'boolean',
        'show_in_menu' => 'boolean',
        'why_choose_items' => 'array',
        'offer_list' => 'array',
        'offer_items' => 'array',
        'process_items' => 'array',
        'faq_items' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    public function portfolios(): HasMany
    {
        return $this->hasMany(Portfolio::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeShowInMenu($query)
    {
        return $query->where('show_in_menu', true);
    }
}
