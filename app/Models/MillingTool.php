<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MillingTool extends Model
{
    use HasFactory;

    protected $table = 'milling_tools';

    protected $fillable = [
        'name',
        'tool_type',
        'tool_type_name',
        'material_type',
        'material_type_name',
        'number_of_teeth_min',
        'number_of_teeth_max',
        'helix_angle',
        'max_cutting_speed',
        'wear_resistance_factor',
        'toughness_factor',
        'application'
    ];

    protected $casts = [
        'helix_angle' => 'decimal:2',
        'max_cutting_speed' => 'decimal:2',
        'wear_resistance_factor' => 'decimal:3',
        'toughness_factor' => 'decimal:3'
    ];
}
