<?php
// app/Http/Controllers/CalculationHistoryController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CalculationHistory;
use Illuminate\Support\Facades\Log;

class CalculationHistoryController extends Controller
{
    /**
     * Показать историю расчетов пользователя
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $operationType = $request->get('operation_type');
        $search = $request->get('search');

        $query = CalculationHistory::where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        // Фильтрация по типу операции
        if ($operationType && in_array($operationType, ['turning', 'milling', 'drilling'])) {
            $query->byOperationType($operationType);
        }

        // Поиск
        if ($search) {
            $query->search($search);
        }

        $calculations = $query->paginate(15);

        return view('history.index', [
            'calculations' => $calculations,
            'operationType' => $operationType,
            'search' => $search,
            'title' => 'История расчетов'
        ]);
    }

    /**
     * Сохранить расчет в историю
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();

            $validated = $request->validate([
                'operation_type' => 'required|in:turning,milling,drilling',
                'title' => 'required|string|max:255',
                'input_parameters' => 'required|array',
                'calculation_results' => 'required|array',
                'notes' => 'nullable|string|max:1000'
            ]);

            $calculation = CalculationHistory::create([
                'user_id' => $user->id,
                'operation_type' => $validated['operation_type'],
                'title' => $validated['title'],
                'input_parameters' => $validated['input_parameters'],
                'calculation_results' => $validated['calculation_results'],
                'notes' => $validated['notes'] ?? null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Расчет сохранен в историю',
                'id' => $calculation->id
            ]);

        } catch (\Exception $e) {
            Log::error('Error saving calculation history: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Ошибка при сохранении расчета'
            ], 500);
        }
    }

    /**
     * Показать детали расчета
     */
    public function show($id)
    {
        $calculation = CalculationHistory::where('user_id', Auth::id())
            ->findOrFail($id);

        return view('history.show', [
            'calculation' => $calculation,
            'title' => 'Детали расчета: ' . $calculation->title
        ]);
    }

    /**
     * Обновить заметки или избранное
     */
    public function update(Request $request, $id)
    {
        try {
            $calculation = CalculationHistory::where('user_id', Auth::id())
                ->findOrFail($id);

            $validated = $request->validate([
                'title' => 'sometimes|string|max:255',
                'notes' => 'nullable|string|max:1000',
                'is_favorite' => 'sometimes|boolean'
            ]);

            $calculation->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Расчет обновлен'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при обновлении расчета'
            ], 500);
        }
    }

    /**
     * Удалить расчет из истории
     */
    public function destroy($id)
    {
        try {
            $calculation = CalculationHistory::where('user_id', Auth::id())
                ->findOrFail($id);

            $calculation->delete();

            return response()->json([
                'success' => true,
                'message' => 'Расчет удален из истории'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при удалении расчета'
            ], 500);
        }
    }

}
