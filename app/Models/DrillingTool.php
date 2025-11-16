<?php
// app/Models/DrillingTool.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrillingTool extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tool_type',
        'tool_type_name',
        'material_type',
        'material_type_name',
        'point_angle',
        'helix_angle',
        'max_cutting_speed',
        'wear_resistance_factor',
        'toughness_factor',
        'application'
    ];
}
