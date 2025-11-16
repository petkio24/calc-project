<?php
// app/Models/OperationFactor.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationFactor extends Model
{
    use HasFactory;

    protected $fillable = [
        'operation_type',
        'operation_type_name',
        'surface_quality',
        'surface_quality_name',
        'speed_factor',
        'feed_factor',
        'power_factor'
    ];
}
