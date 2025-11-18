<?php
// app/Models/CalculationHistory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalculationHistory extends Model
{
    use HasFactory;

    protected $table = 'calculation_history';

    protected $fillable = [
        'user_id',
        'operation_type',
        'title',
        'input_parameters',
        'calculation_results',
        'is_favorite',
        'notes'
    ];

    protected $casts = [
        'input_parameters' => 'array',
        'calculation_results' => 'array',
        'is_favorite' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Отношение к пользователю
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope для фильтрации по типу операции
     */
    public function scopeByOperationType($query, $operationType)
    {
        return $query->where('operation_type', $operationType);
    }

    /**
     * Scope для избранных расчетов
     */
    public function scopeFavorites($query)
    {
        return $query->where('is_favorite', true);
    }

    /**
     * Scope для поиска по названию
     */
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where('title', 'like', "%{$searchTerm}%")
            ->orWhere('notes', 'like', "%{$searchTerm}%");
    }

    /**
     * Получить краткую информацию о расчете
     */
    public function getSummaryAttribute(): string
    {
        $params = $this->input_parameters;

        switch ($this->operation_type) {
            case 'turning':
                return sprintf(
                    "Точение: Ø%s→Ø%s мм, %s",
                    $params['initial_diameter'] ?? '',
                    $params['final_diameter'] ?? '',
                    $this->material_info ?? ''
                );

            case 'milling':
                return sprintf(
                    "Фрезерование: Ø%s мм, %s зубьев",
                    $params['cutter_diameter'] ?? '',
                    $params['number_of_teeth'] ?? ''
                );

            case 'drilling':
                return sprintf(
                    "Сверление: Ø%s мм, глубина %s мм",
                    $params['diameter'] ?? '',
                    $params['hole_depth'] ?? ''
                );

            default:
                return $this->title;
        }
    }

    /**
     * Информация о материале для отображения
     */
    public function getMaterialInfoAttribute(): string
    {
        $results = $this->calculation_results;
        return $results['material']['name'] ?? 'Не указан';
    }

    /**
     * Основные результаты для быстрого просмотра
     */
    public function getQuickResultsAttribute(): array
    {
        $results = $this->calculation_results;

        return [
            'speed' => $results['cutting_speed'] ?? null,
            'feed' => $results['feed_rate'] ?? null,
            'rpm' => $results['spindle_rpm'] ?? null,
            'power' => $results['effective_power'] ?? null
        ];
    }
}
