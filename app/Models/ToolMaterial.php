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
        'type',
        'max_cutting_speed',
        'wear_resistance_factor'
    ];
}
