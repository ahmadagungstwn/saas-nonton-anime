<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoinPackage extends Model
{
    protected $fillable = [
        'title',
        'coin_amount',
        'bonus_amount',
        'price',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    public function coinTopUps()
    {
        return $this->hasMany(CoinTopUp::class);
    }
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function ($query) {
            $query->orderBy('display_order');
        });

        static::creating(function ($model) {
            $model->display_order = self::max('display_order') + 1;
        });

        static::deleting(function ($model) {
            self::where('display_order', '>', $model->display_order)->decrement('display_order');
        });
    }
}
