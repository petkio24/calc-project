<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MillingMaterial;
use App\Models\MillingTool;
use App\Models\MachineType;

class CalculatorMillingController extends Controller
{
    /**
     * Показываем калькулятор фрезерования
     */
    public function index()
    {
        $materials = MillingMaterial::all()->groupBy('material_group');
        $tools = MillingTool::all();
        $machineTypes = MachineType::byCategory('milling')->get();

        return view('calculators.milling', [
            'title' => 'Профессиональный калькулятор фрезерования',
            'operation' => 'milling',
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
                'material_id' => 'required|exists:milling_materials,id',
                'tool_id' => 'required|exists:milling_tools,id',
                'cutter_diameter' => 'required|numeric|min:0.1|max:200',
                'number_of_teeth' => 'required|numeric|min:1|max:20',
                'width_of_cut' => 'required|numeric|min:0.1',
                'depth_of_cut' => 'required|numeric|min:0.1',
                'operation_type' => 'required|in:roughing,semi_finishing,finishing',
                'machine_type_id' => 'nullable|exists:machine_types,id',
                'machine_age' => 'nullable|in:new,normal,worn,custom',
                'custom_years' => 'nullable|integer|min:0|max:50',
                'coolant_used' => 'nullable|boolean'
            ]);

            // Получаем данные
            $material = MillingMaterial::findOrFail($request->material_id);
            $tool = MillingTool::findOrFail($request->tool_id);
            $cutterDiameter = floatval($request->cutter_diameter);
            $numberOfTeeth = intval($request->number_of_teeth);
            $widthOfCut = floatval($request->width_of_cut);
            $depthOfCut = floatval($request->depth_of_cut);
            $operationType = $request->operation_type;
            $coolantUsed = $request->coolant_used ?? true;

            // Проверка количества зубьев
            if ($numberOfTeeth < $tool->number_of_teeth_min || $numberOfTeeth > $tool->number_of_teeth_max) {
                throw new \Exception("Количество зубьев должно быть в диапазоне {$tool->number_of_teeth_min}-{$tool->number_of_teeth_max} для выбранного инструмента");
            }

            // Получаем станок или используем стандартный
            if ($request->machine_type_id) {
                $machineType = MachineType::findOrFail($request->machine_type_id);
                $machineType = $this->updateMachineCondition($machineType, $request);
            } else {
                $machineType = $this->getDefaultMillingMachine();
                $machineType = $this->updateMachineCondition($machineType, $request);
            }

            // РЕАЛЬНЫЕ РАСЧЕТЫ ПО ФОРМУЛАМ

            // 1. Расчет скорости резания (Vc)
            $cuttingSpeed = $this->calculateCuttingSpeed($material, $tool, $operationType, $coolantUsed);

            // 2. Применяем коэффициент состояния станка к скорости
            $cuttingSpeed = $this->applyMachineConditionFactor($cuttingSpeed, $machineType);

            // 3. Расчет оборотов шпинделя (n = (1000 × V) / (π × D))
            $spindleRPM = $this->calculateSpindleRPM($cuttingSpeed, $cutterDiameter);

            // 4. Ограничение оборотов по возможностям станка
            $maxRpmWithAge = $machineType->max_rpm * $machineType->condition_factor;
            $spindleRPM = min($spindleRPM, $maxRpmWithAge);

            // 5. Корректировка скорости резания с учетом ограничений станка
            $actualCuttingSpeed = $this->calculateActualCuttingSpeed($spindleRPM, $cutterDiameter);

            // 6. Расчет подачи на зуб (Sz)
            $feedPerTooth = $this->calculateFeedPerTooth($material, $tool, $operationType, $depthOfCut, $widthOfCut);

            // 7. Применяем коэффициент состояния станка к подаче
            $feedPerTooth = $this->applyMachineConditionFactor($feedPerTooth, $machineType);

            // 8. Расчет минутной подачи (Sm = Sz × z × n)
            $feedRate = $this->calculateFeedRate($feedPerTooth, $numberOfTeeth, $spindleRPM);

            // 9. Расчет подачи на оборот (So = Sz × z)
            $feedPerRevolution = $this->calculateFeedPerRevolution($feedPerTooth, $numberOfTeeth);

            // 10. Расчет мощности резания
            $cuttingPower = $this->calculateCuttingPower($material, $widthOfCut, $depthOfCut, $feedRate, $actualCuttingSpeed);

            // 11. Расчет эффективной мощности
            $effectivePower = $this->calculateEffectivePower($cuttingPower, $machineType);

            // 12. Расчет материалоемкости (Q)
            $materialRemovalRate = $this->calculateMaterialRemovalRate($widthOfCut, $depthOfCut, $feedRate);

            // 13. Расчет усилия резания
            $cuttingForce = $this->calculateCuttingForce($material, $widthOfCut, $depthOfCut, $feedPerTooth);

            // 14. Расчет крутящего момента
            $torque = $this->calculateTorque($cuttingForce, $cutterDiameter);

            // 15. Расчет времени обработки (для длины 100 мм)
            $cuttingLength = 100; // стандартная длина для расчета
            $cuttingTime = $this->calculateCuttingTime($cuttingLength, $feedRate);

            // 16. Проверка ограничений по глубине и ширине резания
            $isDepthValid = $this->checkDepthOfCut($depthOfCut, $cutterDiameter, $operationType);
            $isWidthValid = $this->checkWidthOfCut($widthOfCut, $cutterDiameter, $operationType);

            // Проверка ограничений станка
            $isRpmValid = $spindleRPM <= $maxRpmWithAge;
            $isPowerValid = $effectivePower <= ($machineType->max_power_kw * $machineType->condition_factor);

            // Флаги использования значений по умолчанию
            $usedDefaultMachineType = !$request->machine_type_id;

            return view('calculators.milling', [
                'title' => 'Профессиональный калькулятор фрезерования',
                'operation' => 'milling',
                'materials' => MillingMaterial::all()->groupBy('material_group'),
                'tools' => MillingTool::all(),
                'machineTypes' => MachineType::all(),
                'result' => [
                    // Основные параметры
                    'material' => $material,
                    'tool' => $tool,
                    'cutter_diameter' => $cutterDiameter,
                    'number_of_teeth' => $numberOfTeeth,
                    'width_of_cut' => $widthOfCut,
                    'depth_of_cut' => $depthOfCut,
                    'machine_type' => $machineType,
                    'operation_type' => $operationType,
                    'operation_type_name' => $this->getOperationTypeName($operationType),

                    // Режимы резания
                    'cutting_speed' => round($actualCuttingSpeed, 1),
                    'feed_per_tooth' => round($feedPerTooth, 4),
                    'feed_per_revolution' => round($feedPerRevolution, 3),
                    'spindle_rpm' => round($spindleRPM),
                    'feed_rate' => round($feedRate, 1),

                    // Мощность и силовые параметры
                    'cutting_power' => round($cuttingPower, 2),
                    'effective_power' => round($effectivePower, 2),
                    'cutting_force' => round($cuttingForce, 1),
                    'torque' => round($torque, 2),
                    'material_removal_rate' => round($materialRemovalRate, 2),

                    // Время обработки
                    'cutting_time' => round($cuttingTime, 2),
                    'cutting_length' => $cuttingLength,

                    // Проверки и статусы
                    'is_rpm_valid' => $isRpmValid,
                    'is_power_valid' => $isPowerValid,
                    'is_depth_valid' => $isDepthValid,
                    'is_width_valid' => $isWidthValid,
                    'is_calculations_valid' => $isRpmValid && $isPowerValid && $isDepthValid && $isWidthValid,

                    // Состояние станка
                    'used_default_machine_type' => $usedDefaultMachineType,
                    'machine_age' => $request->machine_age,
                    'years_in_service' => $machineType->years_in_service,
                    'machine_condition' => $machineType->machine_condition,
                    'condition_factor' => $machineType->condition_factor,
                    'condition_reduction_percent' => round((1 - $machineType->condition_factor) * 100),

                    // Дополнительные параметры
                    'coolant_used' => $coolantUsed,
                    'max_recommended_depth' => $this->getMaxRecommendedDepth($cutterDiameter, $operationType),
                    'max_recommended_width' => $this->getMaxRecommendedWidth($cutterDiameter, $operationType),
                ]
            ]);

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Ошибка расчета: ' . $e->getMessage()]);
        }
    }

    /**
     * РЕАЛЬНЫЕ ФОРМУЛЫ ДЛЯ ФРЕЗЕРОВАНИЯ
     */

    // 1. Расчет скорости резания
    private function calculateCuttingSpeed($material, $tool, $operationType, $coolantUsed)
    {
        // Базовая скорость из материала
        $baseSpeed = ($material->cutting_speed_min + $material->cutting_speed_max) / 2;

        // Коэффициент инструмента
        $toolFactor = $tool->wear_resistance_factor;

        // Коэффициент типа операции
        $operationFactor = $this->getOperationFactor($operationType);

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

    // 4. Расчет подачи на зуб
    private function calculateFeedPerTooth($material, $tool, $operationType, $depthOfCut, $widthOfCut)
    {
        // Базовая подача из материала
        $baseFeed = ($material->feed_per_tooth_min + $material->feed_per_tooth_max) / 2;

        // Коэффициент инструмента
        $toolFactor = $tool->toughness_factor;

        // Коэффициент типа операции
        $operationFactor = $this->getOperationFeedFactor($operationType);

        // Коэффициент глубины резания
        $depthFactor = $this->getDepthFactor($depthOfCut);

        // Коэффициент ширины фрезерования
        $widthFactor = $this->getWidthFactor($widthOfCut);

        // Расчет подачи
        $feed = $baseFeed * $toolFactor * $operationFactor * $depthFactor * $widthFactor;

        // Ограничение по материалу
        $minFeed = $material->feed_per_tooth_min;
        $maxFeed = $material->feed_per_tooth_max;

        return max($minFeed, min($feed, $maxFeed));
    }

    // 5. Расчет минутной подачи: Sm = Sz × z × n
    private function calculateFeedRate($feedPerTooth, $numberOfTeeth, $rpm)
    {
        return $feedPerTooth * $numberOfTeeth * $rpm;
    }

    // 6. Расчет подачи на оборот: So = Sz × z
    private function calculateFeedPerRevolution($feedPerTooth, $numberOfTeeth)
    {
        return $feedPerTooth * $numberOfTeeth;
    }

    // 7. Расчет мощности резания
    private function calculateCuttingPower($material, $width, $depth, $feedRate, $cuttingSpeed)
    {
        // Удельная мощность резания (кВт/см³/мин)
        $specificPower = $material->power_factor;

        // Объем снимаемого материала (см³/мин)
        $materialRemovalRate = ($width * $depth * $feedRate) / 1000;

        return ($materialRemovalRate * $specificPower) / 60;
    }

    // 8. Расчет эффективной мощности
    private function calculateEffectivePower($cuttingPower, $machineType)
    {
        $efficiency = $machineType->efficiency ?? 0.85;
        return $cuttingPower / $efficiency;
    }

    // 9. Расчет материалоемкости (см³/мин)
    private function calculateMaterialRemovalRate($width, $depth, $feedRate)
    {
        return ($width * $depth * $feedRate) / 1000;
    }

    // 10. Расчет усилия резания (Н)
    private function calculateCuttingForce($material, $width, $depth, $feedPerTooth)
    {
        // Удельное давление резания (Н/мм²)
        $specificPressure = $material->specific_pressure;

        return $specificPressure * $width * $depth * $feedPerTooth;
    }

    // 11. Расчет крутящего момента (Н·м)
    private function calculateTorque($cuttingForce, $diameter)
    {
        return $cuttingForce * ($diameter / 2000);
    }

    // 12. Расчет времени обработки (мин)
    private function calculateCuttingTime($length, $feedRate)
    {
        if ($feedRate <= 0) return 0;
        return ($length / $feedRate) * 1.2; // +20% на подход и отвод
    }

    // 13. Проверка глубины резания
    private function checkDepthOfCut($depth, $diameter, $operationType)
    {
        $maxDepth = $this->getMaxRecommendedDepth($diameter, $operationType);
        return $depth <= $maxDepth;
    }

    // 14. Проверка ширины фрезерования
    private function checkWidthOfCut($width, $diameter, $operationType)
    {
        $maxWidth = $this->getMaxRecommendedWidth($diameter, $operationType);
        return $width <= $maxWidth;
    }

    // 15. Максимальная рекомендуемая глубина резания
    private function getMaxRecommendedDepth($diameter, $operationType)
    {
        switch ($operationType) {
            case 'roughing':
                return $diameter * 1.0;  // 1×D
            case 'semi_finishing':
                return $diameter * 1.5;  // 1.5×D
            case 'finishing':
                return $diameter * 0.5;  // 0.5×D
            default:
                return $diameter * 1.0;
        }
    }

    // 16. Максимальная рекомендуемая ширина фрезерования
    private function getMaxRecommendedWidth($diameter, $operationType)
    {
        switch ($operationType) {
            case 'roughing':
                return $diameter * 0.7;  // 0.7×D
            case 'semi_finishing':
                return $diameter * 0.4;  // 0.4×D
            case 'finishing':
                return $diameter * 0.2;  // 0.2×D
            default:
                return $diameter * 0.5;
        }
    }

    /**
     * ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ
     */

    private function getOperationFactor($operationType)
    {
        return [
            'roughing' => 1.0,
            'semi_finishing' => 1.2,
            'finishing' => 1.4
        ][$operationType] ?? 1.0;
    }

    private function getOperationFeedFactor($operationType)
    {
        return [
            'roughing' => 1.0,
            'semi_finishing' => 0.7,
            'finishing' => 0.4
        ][$operationType] ?? 1.0;
    }

    private function getDepthFactor($depth)
    {
        if ($depth <= 1) return 1.0;
        if ($depth <= 3) return 0.9;
        if ($depth <= 5) return 0.8;
        if ($depth <= 8) return 0.7;
        return 0.6;
    }

    private function getWidthFactor($width)
    {
        if ($width <= 0.2) return 0.8;
        if ($width <= 0.5) return 0.9;
        if ($width <= 0.8) return 1.0;
        return 0.9;
    }

    private function getOperationTypeName($operationType)
    {
        return [
            'roughing' => '⚒️ Черновая обработка',
            'semi_finishing' => '🔧 Получистовая обработка',
            'finishing' => '✨ Чистовая обработка'
        ][$operationType] ?? $operationType;
    }

    /**
     * ФУНКЦИИ ДЛЯ УЧЕТА ИЗНОСА СТАНКА
     */

    private function updateMachineCondition($machineType, $request)
    {
        $machineAge = $request->machine_age ?? 'normal';
        $customYears = $request->custom_years ?? null;

        if ($machineAge === 'custom' && $customYears) {
            $years = intval($customYears);
            $machineType->years_in_service = $years;

            if ($years <= 2) {
                $machineType->machine_condition = 'new';
                $machineType->condition_factor = 1.0;
            } elseif ($years <= 7) {
                $machineType->machine_condition = 'normal';
                $reduction = min(0.25, ($years - 2) * 0.02);
                $machineType->condition_factor = 1.0 - $reduction;
            } else {
                $machineType->machine_condition = 'worn';
                $reduction = 0.15 + min(0.10, ($years - 7) * 0.02);
                $machineType->condition_factor = 1.0 - $reduction;
            }
        } else {
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

    private function applyMachineConditionFactor($value, $machineType)
    {
        return $value * $machineType->condition_factor;
    }

    private function getDefaultMillingMachine()
    {
        return new MachineType([
            'name' => 'Фрезерный станок (стандарт)',
            'power_range' => '7.5-15 кВт',
            'max_rpm' => 6000,
            'rigidity_factor' => 1.0,
            'efficiency' => 0.85,
            'max_power_kw' => 15.0,
            'machine_category' => 'milling',
            'years_in_service' => 5,
            'condition_factor' => 0.9,
            'machine_condition' => 'normal'
        ]);
    }
}
