<?php
// app/Http/Controllers/CalculatorDrillingController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DrillingMaterial;
use App\Models\DrillingTool;
use App\Models\MachineType;

class CalculatorDrillingController extends Controller
{
    /**
     * Показываем калькулятор сверления
     */
    public function index()
    {
        $materials = DrillingMaterial::all()->groupBy('material_group');
        $tools = DrillingTool::all();
        $machineTypes = MachineType::all();

        return view('calculators.drilling', [
            'title' => 'Калькулятор сверления',
            'operation' => 'drilling',
            'materials' => $materials,
            'tools' => $tools,
            'machineTypes' => $machineTypes
        ]);
    }

    /**
     * Выполняем расчет с реальными формулами
     */
    public function calculate(Request $request)
    {
        try {
            $request->validate([
                'material_id' => 'required|exists:drilling_materials,id',
                'tool_id' => 'required|exists:drilling_tools,id',
                'diameter' => 'required|numeric|min:0.1|max:100',
                'hole_depth' => 'required|numeric|min:1',
                'machine_type_id' => 'nullable|exists:machine_types,id',
                'operation_type' => 'nullable|in:roughing,finishing',
                'coolant_used' => 'nullable|boolean',
                'machine_age' => 'nullable|in:new,normal,worn,custom',
                'custom_years' => 'nullable|integer|min:0|max:50'
            ]);

            // Получаем данные
            $material = DrillingMaterial::findOrFail($request->material_id);
            $tool = DrillingTool::findOrFail($request->tool_id);
            $diameter = floatval($request->diameter);
            $holeDepth = floatval($request->hole_depth);
            $operationType = $request->operation_type ?? 'roughing';
            $coolantUsed = $request->coolant_used ?? true;

            // Получаем станок или используем стандартный
            if ($request->machine_type_id) {
                $machineType = MachineType::findOrFail($request->machine_type_id);
                // Обновляем состояние станка на основе введенных данных
                $machineType = $this->updateMachineCondition($machineType, $request);
            } else {
                $machineType = $this->getDefaultDrillingMachine();
                $machineType = $this->updateMachineCondition($machineType, $request);
            }

            // РЕАЛЬНЫЕ РАСЧЕТЫ ПО ФОРМУЛАМ

            // 1. Расчет скорости резания (Vc)
            $cuttingSpeed = $this->calculateCuttingSpeed($material, $tool, $operationType, $coolantUsed);

            // 2. Применяем коэффициент состояния станка к скорости
            $cuttingSpeed = $this->applyMachineConditionFactor($cuttingSpeed, $machineType);

            // 3. Расчет оборотов шпинделя (n = (1000 × V) / (π × D))
            $spindleRPM = $this->calculateSpindleRPM($cuttingSpeed, $diameter);

            // 4. Ограничение оборотов по возможностям станка (с учетом износа)
            $maxRpmWithAge = $machineType->max_rpm * $machineType->condition_factor;
            $spindleRPM = min($spindleRPM, $maxRpmWithAge);

            // 5. Корректировка скорости резания с учетом ограничений станка
            $actualCuttingSpeed = $this->calculateActualCuttingSpeed($spindleRPM, $diameter);

            // 6. Расчет подачи на оборот (S)
            $feedPerRevolution = $this->calculateFeedPerRevolution($material, $tool, $diameter, $operationType);

            // 7. Применяем коэффициент состояния станка к подаче
            $feedPerRevolution = $this->applyMachineConditionFactor($feedPerRevolution, $machineType);

            // 8. Расчет минутной подачи (F = S × n)
            $feedRate = $this->calculateFeedRate($feedPerRevolution, $spindleRPM);

            // 9. Расчет осевой силы (P)
            $thrustForce = $this->calculateThrustForce($diameter, $feedPerRevolution, $material);

            // 10. Расчет крутящего момента (M)
            $torque = $this->calculateTorque($diameter, $feedPerRevolution, $material);

            // 11. Расчет мощности резания (P = (M × n) / 9550)
            $cuttingPower = $this->calculateCuttingPower($torque, $spindleRPM);

            // 12. Расчет эффективной мощности с учетом КПД и износа
            $effectivePower = $this->calculateEffectivePower($cuttingPower, $machineType);

            // 13. Расчет времени обработки одного отверстия
            $cuttingTimePerHole = $this->calculateCuttingTime($holeDepth, $feedRate);

            // 14. Расчет съема материала
            $materialRemovalRate = $this->calculateMaterialRemovalRate($diameter, $feedRate);

            // Проверка ограничений станка (учитываем износ)
            $isRpmValid = $spindleRPM <= $maxRpmWithAge;
            $isPowerValid = $effectivePower <= ($machineType->max_power_kw * $machineType->condition_factor);

            // Флаги использования значений по умолчанию
            $usedDefaultMachineType = !$request->machine_type_id;
            $usedCustomAge = $request->machine_age === 'custom';

            return view('calculators.drilling', [
                'title' => 'Калькулятор сверления',
                'operation' => 'drilling',
                'materials' => DrillingMaterial::all(),
                'tools' => DrillingTool::all(),
                'machineTypes' => MachineType::all(),
                'result' => [
                    // Основные параметры
                    'material' => $material->name,
                    'tool' => $tool->name,
                    'diameter' => $diameter,
                    'hole_depth' => $holeDepth,
                    'machine_type' => $machineType->name,

                    // Режимы резания
                    'cutting_speed' => round($actualCuttingSpeed, 1),
                    'feed_per_revolution' => round($feedPerRevolution, 4),
                    'spindle_rpm' => round($spindleRPM),
                    'feed_rate' => round($feedRate, 1),

                    // Силовые параметры
                    'thrust_force' => round($thrustForce, 1),
                    'torque' => round($torque, 2),
                    'cutting_power' => round($cuttingPower, 2),
                    'effective_power' => round($effectivePower, 2),

                    // Время обработки
                    'cutting_time_per_hole' => round($cuttingTimePerHole, 2),
                    'material_removal_rate' => round($materialRemovalRate, 2),

                    // Дополнительные параметры
                    'operation_type' => $operationType,
                    'coolant_used' => $coolantUsed,

                    // Проверки
                    'is_rpm_valid' => $isRpmValid,
                    'is_power_valid' => $isPowerValid,
                    'is_calculations_valid' => $isRpmValid && $isPowerValid,

                    // Информация о материале и инструменте
                    'material_hardness' => $material->hardness_range,
                    'tool_type' => $tool->tool_type_name,
                    'tool_material' => $tool->material_type_name,
                    'point_angle' => $tool->point_angle,

                    // Состояние станка
                    'used_default_machine_type' => $usedDefaultMachineType,
                    'machine_age' => $request->machine_age,
                    'custom_years' => $request->custom_years,
                    'years_in_service' => $machineType->years_in_service,
                    'machine_condition' => $machineType->machine_condition,
                    'condition_factor' => $machineType->condition_factor,
                    'condition_reduction_percent' => round((1 - $machineType->condition_factor) * 100),
                    'used_custom_age' => $usedCustomAge,
                ]
            ]);

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Ошибка расчета: ' . $e->getMessage()]);
        }
    }

    /**
     * РЕАЛЬНЫЕ ФОРМУЛЫ ДЛЯ СВЕРЛЕНИЯ
     */

    // 1. Расчет скорости резания
    private function calculateCuttingSpeed($material, $tool, $operationType, $coolantUsed)
    {
        // Базовая скорость из материала
        $baseSpeed = ($material->cutting_speed_min + $material->cutting_speed_max) / 2;

        // Коэффициент инструмента
        $toolFactor = $tool->wear_resistance_factor;

        // Коэффициент типа операции
        $operationFactor = $operationType === 'roughing' ? 1.0 : 1.2;

        // Коэффициент охлаждения
        $coolantFactor = $coolantUsed ? 1.1 : 0.9;

        // Расчет скорости
        $speed = $baseSpeed * $toolFactor * $operationFactor * $coolantFactor;

        // Ограничение по максимальной скорости инструмента
        return min($speed, $tool->max_cutting_speed * 0.9);
    }

    // 2. Формула оборотов шпинделя: n = (1000 × V) / (π × D)
    private function calculateSpindleRPM($cuttingSpeed, $diameter)
    {
        if ($diameter <= 0) return 0;
        return ($cuttingSpeed * 1000) / (pi() * $diameter);
    }

    // 3. Расчет фактической скорости резания: V = (π × D × n) / 1000
    private function calculateActualCuttingSpeed($rpm, $diameter)
    {
        if ($diameter <= 0 || $rpm <= 0) return 0;
        return (pi() * $diameter * $rpm) / 1000;
    }

    // 4. Расчет подачи на оборот
    private function calculateFeedPerRevolution($material, $tool, $diameter, $operationType)
    {
        // Базовая подача из материала
        $baseFeed = ($material->feed_per_rev_min + $material->feed_per_rev_max) / 2;

        // Коэффициент диаметра (чем больше диаметр, тем больше подача)
        $diameterFactor = $this->getDiameterFactor($diameter);

        // Коэффициент типа операции
        $operationFactor = $operationType === 'roughing' ? 1.0 : 0.7;

        // Коэффициент инструмента
        $toolFactor = $tool->toughness_factor;

        // Расчет подачи
        $feed = $baseFeed * $diameterFactor * $operationFactor * $toolFactor;

        // Ограничение по материалу
        $minFeed = $material->feed_per_rev_min;
        $maxFeed = $material->feed_per_rev_max;

        return max($minFeed, min($feed, $maxFeed));
    }

    // 5. Расчет минутной подачи: F = S × n
    private function calculateFeedRate($feedPerRevolution, $rpm)
    {
        return $feedPerRevolution * $rpm;
    }

    // 6. Расчет осевой силы (упрощенная формула)
    private function calculateThrustForce($diameter, $feed, $material)
    {
        // P = K × D × S^0.8 (Н)
        $k = $material->specific_pressure;
        return $k * $diameter * pow($feed, 0.8);
    }

    // 7. Расчет крутящего момента (упрощенная формула)
    private function calculateTorque($diameter, $feed, $material)
    {
        // M = C × D^2 × S^0.8 (Н·м)
        $c = $material->specific_pressure / 200; // Эмпирический коэффициент
        return $c * pow($diameter, 2) * pow($feed, 0.8);
    }

    // 8. Расчет мощности резания: P = (M × n) / 9550
    private function calculateCuttingPower($torque, $rpm)
    {
        return ($torque * $rpm) / 9550;
    }

    // 9. Расчет эффективной мощности
    private function calculateEffectivePower($cuttingPower, $machineType)
    {
        $efficiency = $machineType->efficiency ?? 0.85;
        return $cuttingPower / $efficiency;
    }

    // 10. Расчет времени обработки: T = L / F (мин)
    private function calculateCuttingTime($depth, $feedRate)
    {
        if ($feedRate <= 0) return 0;
        return ($depth / $feedRate) * 1.1; // +10% на подход и отвод
    }

    // 11. Расчет съема материала: Q = (π × D² × F) / 4 (см³/мин)
    private function calculateMaterialRemovalRate($diameter, $feedRate)
    {
        return (pi() * pow($diameter, 2) * $feedRate) / 4000; // делим на 4000 для перевода в см³/мин
    }

    /**
     * ФУНКЦИИ ДЛЯ УЧЕТА ИЗНОСА СТАНКА
     */

    // Определяем состояние станка на основе введенных данных
    private function updateMachineCondition($machineType, $request)
    {
        $machineAge = $request->machine_age ?? 'normal';
        $customYears = $request->custom_years ?? null;

        if ($machineAge === 'custom' && $customYears) {
            $years = intval($customYears);
            $machineType->years_in_service = $years;

            // Рассчитываем коэффициент износа на основе точного количества лет
            if ($years <= 2) {
                $machineType->machine_condition = 'new';
                $machineType->condition_factor = 1.0;
            } elseif ($years <= 7) {
                $machineType->machine_condition = 'normal';
                // Каждые 5-7 лет точность уменьшается на 5-10%
                $reduction = min(0.25, ($years - 2) * 0.02); // Макс 25% снижение
                $machineType->condition_factor = 1.0 - $reduction;
            } else {
                $machineType->machine_condition = 'worn';
                // Для станков старше 7 лет снижение 15-25%
                $reduction = 0.15 + min(0.10, ($years - 7) * 0.02); // 15-25%
                $machineType->condition_factor = 1.0 - $reduction;
            }
        } else {
            // Используем предустановленные значения
            switch ($machineAge) {
                case 'new':
                    $machineType->years_in_service = 1;
                    $machineType->machine_condition = 'new';
                    $machineType->condition_factor = 1.0;
                    break;
                case 'normal':
                    $machineType->years_in_service = 5;
                    $machineType->machine_condition = 'normal';
                    $machineType->condition_factor = 0.9;
                    break;
                case 'worn':
                    $machineType->years_in_service = 10;
                    $machineType->machine_condition = 'worn';
                    $machineType->condition_factor = 0.75;
                    break;
                default:
                    $machineType->years_in_service = 5;
                    $machineType->machine_condition = 'normal';
                    $machineType->condition_factor = 0.9;
            }
        }

        return $machineType;
    }

    // Применяем коэффициент состояния станка
    private function applyMachineConditionFactor($value, $machineType)
    {
        return $value * $machineType->condition_factor;
    }

    /**
     * ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ
     */

    private function getDiameterFactor($diameter)
    {
        if ($diameter <= 3) return 0.5;
        if ($diameter <= 6) return 0.7;
        if ($diameter <= 10) return 0.9;
        if ($diameter <= 20) return 1.0;
        if ($diameter <= 30) return 1.1;
        return 1.2;
    }

    private function getDefaultDrillingMachine()
    {
        return new MachineType([
            'name' => 'Станок сверлильный (стандарт)',
            'power_range' => '3-5.5 кВт',
            'max_rpm' => 3000,
            'rigidity_factor' => 1.0,
            'efficiency' => 0.85,
            'max_power_kw' => 5.5,
            'years_in_service' => 5,
            'condition_factor' => 0.9,
            'machine_condition' => 'normal'
        ]);
    }
}
