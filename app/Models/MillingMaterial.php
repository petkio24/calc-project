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
        'cutting_speed_min',
        'cutting_speed_max',
        'feed_per_tooth_min',
        'feed_per_tooth_max',
        'power_factor'
    ];

    protected $casts = [
        'cutting_speed_min' => 'decimal:2',
        'cutting_speed_max' => 'decimal:2',
        'feed_per_tooth_min' => 'decimal:4',
        'feed_per_tooth_max' => 'decimal:4',
        'power_factor' => 'decimal:4'
    ];
}
