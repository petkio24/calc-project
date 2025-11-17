<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MillingMaterial extends Model
{
    use HasFactory;

    protected $table = 'milling_materials';

    protected $fillable = [
        'name',
        'material_group',
        'material_group_name',
        'hardness_range',
        'cutting_speed_min',
        'cutting_speed_max',
        'feed_per_tooth_min',
        'feed_per_tooth_max',
        'power_factor',
        'specific_pressure',
        'thermal_conductivity',
        'description'
    ];

    protected $casts = [
        'cutting_speed_min' => 'decimal:2',
        'cutting_speed_max' => 'decimal:2',
        'feed_per_tooth_min' => 'decimal:4',
        'feed_per_tooth_max' => 'decimal:4',
        'power_factor' => 'decimal:4',
        'specific_pressure' => 'decimal:2',
        'thermal_conductivity' => 'decimal:2'
    ];

    public function getMaterialGroupNameAttribute()
    {
        return [
            'carbon_steel' => '⚙️ Конструкционные стали',
            'alloy_steel' => '🔩 Легированные стали',
            'stainless_steel' => '🔶 Нержавеющие стали',
            'cast_iron' => '🛠️ Чугуны',
            'aluminum' => '📦 Алюминиевые сплавы',
            'copper_alloy' => '🔰 Медные сплавы',
            'plastics' => '🧩 Пластмассы'
        ][$this->material_group] ?? $this->material_group;
    }
}
