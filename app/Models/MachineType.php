<?php
// app/Models/MachineType.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineType extends Model
{
    use HasFactory;

    protected $table = 'machine_types';

    protected $fillable = [
        'name',
        'power_range',
        'max_rpm',
        'rigidity_factor',
        'efficiency',
        'max_power_kw',
        'machine_category',
        'description'
    ];

    protected $casts = [
        'max_rpm' => 'integer',
        'rigidity_factor' => 'decimal:2',
        'efficiency' => 'decimal:2',
        'max_power_kw' => 'decimal:2'
    ];

    /**
     * Scope для фильтрации по категории станка
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('machine_category', $category);
    }

    /**
     * Получить название категории
     */
    public function getCategoryNameAttribute()
    {
        return [
            'turning' => 'Токарные станки',
            'milling' => 'Фрезерные станки',
            'drilling' => 'Сверлильные станки'
        ][$this->machine_category] ?? $this->machine_category;
    }
}
