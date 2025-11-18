<?php
// app/Traits/SavesCalculationHistory.php

namespace App\Traits;

use App\Models\CalculationHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait SavesCalculationHistory
{
    /**
     * Автоматическое сохранение расчета в историю
     */
    private function saveToHistory($operationType, $title, $inputParameters, $calculationResults, $notes = null)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return;
            }

            CalculationHistory::create([
                'user_id' => $user->id,
                'operation_type' => $operationType,
                'title' => $title,
                'input_parameters' => $inputParameters,
                'calculation_results' => $calculationResults,
                'notes' => $notes,
                'is_favorite' => false
            ]);

        } catch (\Exception $e) {
            Log::error('Error saving calculation history: ' . $e->getMessage());
        }
    }
}
