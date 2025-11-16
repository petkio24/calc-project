<?php
// app/Models/DrillingMaterial.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrillingMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'material_group',
        'material_group_name',
        'hardness_range',
        'cutting_speed_min',
        'cutting_speed_max',
        'feed_per_rev_min',
        'feed_per_rev_max',
        'power_factor',
        'specific_pressure',
        'thermal_conductivity',
        'description'
    ];
}
