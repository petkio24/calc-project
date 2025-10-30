<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolGeometry extends Model
{
    use HasFactory;

    protected $table = 'tool_geometries';

    protected $fillable = [
        'name',
        'rake_angle',
        'clearance_angle',
        'cutting_edge_angle',
        'feed_factor'
    ];
}
