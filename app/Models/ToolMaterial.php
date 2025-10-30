<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolMaterial extends Model
{
    use HasFactory;

    protected $table = 'tool_materials';

    protected $fillable = [
        'name',
        'material_type',
        'grade',
        'max_cutting_speed',
        'wear_resistance_factor',
        'application_notes'
    ];

    protected $casts = [
        'max_cutting_speed' => 'decimal:2',
        'wear_resistance_factor' => 'decimal:4'
    ];

    public function getMaterialTypeNameAttribute()
    {
        return [
            'hard_alloy' => 'Твердый сплав',
            'high_speed_steel' => 'Быстрорежущая сталь'
        ][$this->material_type] ?? $this->material_type;
    }
}
