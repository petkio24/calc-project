<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurningMaterial extends Model
{
    use HasFactory;

    protected $table = 'turning_materials';

    protected $fillable = [
        'name',
        'material_group',
        'hardness_range',
        'cutting_speed_min',
        'cutting_speed_max',
        'feed_per_rev_min',
        'feed_per_rev_max',
        'power_factor'
    ];

    protected $casts = [
        'cutting_speed_min' => 'decimal:2',
        'cutting_speed_max' => 'decimal:2',
        'feed_per_rev_min' => 'decimal:4',
        'feed_per_rev_max' => 'decimal:4',
        'power_factor' => 'decimal:4'
    ];

    // Геттеры для удобства
    public function getMaterialGroupNameAttribute()
    {
        return [
            'black_metals' => 'Черные металлы',
            'nonferrous_metals' => 'Цветные металлы',
            'non_metals' => 'Неметаллы'
        ][$this->material_group] ?? $this->material_group;
    }
}
