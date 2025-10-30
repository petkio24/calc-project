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
        'shape',
        'clearance_angle',
        'tolerance_class',
        'chipbreaker_type',
        'cutting_edge_length',
        'insert_thickness',
        'corner_radius',
        'rake_angle',
        'cutting_edge_angle',
        'feed_factor'
    ];

    protected $casts = [
        'clearance_angle' => 'decimal:2',
        'cutting_edge_length' => 'decimal:2',
        'insert_thickness' => 'decimal:2',
        'corner_radius' => 'decimal:2',
        'rake_angle' => 'decimal:2',
        'cutting_edge_angle' => 'decimal:2',
        'feed_factor' => 'decimal:4'
    ];

    public function getShapeNameAttribute()
    {
        return [
            'diamond' => 'Ромб',
            'square' => 'Квадрат',
            'circle' => 'Круг',
            'triangle' => 'Треугольник'
        ][$this->shape] ?? $this->shape;
    }

    public function getToleranceClassNameAttribute()
    {
        return [
            'm' => 'Средний (±0.05-0.08 мм)',
            'g' => 'Высокий (±0.025-0.05 мм)',
            'u' => 'Очень высокий (±0.013-0.025 мм)'
        ][$this->tolerance_class] ?? $this->tolerance_class;
    }
}
