<?php
// app/Http/Controllers/CalculatorTurningController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TurningMaterial;
use App\Models\ToolMaterial;
use App\Models\ToolGeometry;
use App\Models\MachineType;
use App\Models\OperationFactor;

class CalculatorTurningController extends Controller
{
    /**
     * Показываем калькулятор точения
     */
    public function index()
    {
        $materials = TurningMaterial::all()->groupBy('material_group');
        $toolMaterials = ToolMaterial::all()->groupBy('material_type');
        $toolGeometries = ToolGeometry::all();
        $machineTypes = MachineType::byCategory('turning')->get();

        return view('calculators.turning', [
            'title' => 'Профессиональный калькулятор точения',
            'operation' => 'turning',
            'materials' => $materials,
            'toolMaterials' => $toolMaterials,
            'toolGeometries' => $toolGeometries,
            'machineTypes' => $machineTypes
        ]);
    }

    /**
     * Выполняем расчет с реальными формулами
     */
    public function calculate(Request $request)
    {
        try {
            // Валидация
            $request->validate([
                'material_id' => 'required|exists:turning_materials,id',
                'tool_material_id' => 'required|exists:tool_materials,id',
                'tool_geometry_id' => 'required|exists:tool_geometries,id',
                'initial_diameter' => 'required|numeric|min:0.1',
                'final_diameter' => 'required|numeric|min:0.1',
                'cutting_length' => 'required|numeric|min:1',
            ]);

            // Получаем данные
            $material = TurningMaterial::findOrFail($request->material_id);
            $toolMaterial = ToolMaterial::findOrFail($request->tool_material_id);
            $toolGeometry = ToolGeometry::findOrFail($request->tool_geometry_id);
            if ($request->machine_type_id) {
                $machineType = MachineType::findOrFail($request->machine_type_id);
                // Обновляем состояние станка на основе введенных данных
                $machineType = $this->updateMachineCondition($machineType, $request);
            } else {
                $machineType = $this->getDefaultMachineType();
                $machineType = $this->updateMachineCondition($machineType, $request);
            }
            // Параметры обработки
            $operationType = $request->operation_type ?? 'roughing';
            $operationSubtype = $request->operation_subtype ?? 'external_turning';
            $surfaceQuality = $request->surface_quality ?? 'normal';
            $cuttingLength = floatval($request->cutting_length) ?: 100;
            $allowance = floatval($request->allowance) ?: 0;

            // Геометрические параметры
            $initialDiameter = floatval($request->initial_diameter) ?: 50;
            $finalDiameter = floatval($request->final_diameter) ?: 45;

            // Проверка корректности диаметров
            if ($initialDiameter <= $finalDiameter) {
                throw new \Exception('Исходный диаметр должен быть больше получаемого диаметра');
            }

            // РЕАЛЬНЫЕ РАСЧЕТЫ ПО ФОРМУЛАМ

            // 1. Расчет глубины резания
            $depthOfCut = $this->calculateDepthOfCut($initialDiameter, $finalDiameter);

            // 2. Расчет количества проходов
            $passes = $this->calculateNumberOfPasses($depthOfCut, $toolGeometry, $operationType, $operationSubtype);

            // 3. Расчет глубины резания на проход
            $depthPerPass = $this->calculateDepthPerPass($depthOfCut, $passes);

            // 4. Расчет рекомендуемой скорости резания
            $cuttingSpeed = $this->calculateCuttingSpeed($material, $toolMaterial, $toolGeometry, $operationType, $surfaceQuality);

            // 5. Применяем коэффициент состояния станка к скорости
            $cuttingSpeed = $this->applyMachineConditionFactor($cuttingSpeed, $machineType);

            // 6. Расчет подачи на оборот
            $feedPerRevolution = $this->calculateFeedPerRevolution($material, $toolGeometry, $depthPerPass, $operationType, $surfaceQuality, $operationSubtype);

            // 7. Применяем коэффициент состояния станка к подаче
            $feedPerRevolution = $this->applyMachineConditionFactor($feedPerRevolution, $machineType);

            // 8. Расчет оборотов шпинделя
            $averageDiameter = $this->calculateAverageDiameter($initialDiameter, $finalDiameter);
            $spindleRPM = $this->calculateSpindleRPM($cuttingSpeed, $averageDiameter);

            // 9. Ограничение оборотов по возможностям станка
            $maxRpmWithAge = $machineType->max_rpm * $machineType->condition_factor;
            $spindleRPM = min($spindleRPM, $maxRpmWithAge);

            // 10. Корректировка скорости резания с учетом ограничений станка
            $actualCuttingSpeed = $this->calculateActualCuttingSpeed($spindleRPM, $averageDiameter);

            // 11. Расчет минутной подачи
            $feedRate = $this->calculateFeedRate($feedPerRevolution, $spindleRPM);

            // 12. Расчет мощности резания
            $cuttingPower = $this->calculateCuttingPower($depthPerPass, $feedPerRevolution, $actualCuttingSpeed, $material, $toolGeometry);

            // 13. Расчет эффективной мощности с учетом КПД
            $effectivePower = $this->calculateEffectivePower($cuttingPower, $machineType);

            // 14. Расчет времени обработки на проход
            $cuttingTimePerPass = $this->calculateCuttingTime($cuttingLength, $feedRate);

            // 15. Расчет общего времени обработки
            $totalCuttingTime = $this->calculateTotalCuttingTime($cuttingTimePerPass, $passes);

            // 16. Расчет съема материала
            $materialRemovalRate = $this->calculateMaterialRemovalRate($depthPerPass, $feedPerRevolution, $actualCuttingSpeed);

            // 17. Расчет усилия резания
            $cuttingForce = $this->calculateCuttingForce($depthPerPass, $feedPerRevolution, $material);

            // 18. Расчет крутящего момента
            $torque = $this->calculateTorque($cuttingForce, $averageDiameter);

            // 19. Проверка соответствия радиуса инструмента и подачи
            $isFeedRadiusCompatible = $this->checkFeedRadiusCompatibility($feedPerRevolution, $toolGeometry->corner_radius);

            // 20. Расчет рекомендуемого радиуса инструмента
            $recommendedRadius = $this->calculateRecommendedRadius($feedPerRevolution);

            // Проверка ограничений станка (учитываем возраст)
            $isRpmValid = $spindleRPM <= $maxRpmWithAge;
            $isPowerValid = $effectivePower <= ($machineType->max_power_kw * $machineType->condition_factor);
            $isDepthValid = $depthPerPass <= $toolGeometry->max_depth_of_cut;

            // Флаги использования значений по умолчанию
            $usedDefaultMachineType = !$request->machine_type_id;
            $usedCustomAge = $request->machine_age === 'custom';

            return view('calculators.turning', [
                'title' => 'Профессиональный калькулятор точения',
                'operation' => 'turning',
                'materials' => TurningMaterial::all()->groupBy('material_group'),
                'toolMaterials' => ToolMaterial::all()->groupBy('material_type'),
                'toolGeometries' => ToolGeometry::all(),
                'machineTypes' => MachineType::all(),
                'result' => [
                    // Основные параметры
                    'material' => $material,
                    'tool_material' => $toolMaterial,
                    'tool_geometry' => $toolGeometry,
                    'machine_type' => $machineType,
                    'initial_diameter' => $initialDiameter,
                    'final_diameter' => $finalDiameter,
                    'cutting_length' => $cuttingLength,

                    // Геометрические параметры
                    'depth_of_cut' => round($depthOfCut, 3),
                    'depth_per_pass' => round($depthPerPass, 3),
                    'number_of_passes' => $passes,
                    'average_diameter' => round($averageDiameter, 2),

                    // Режимы резания
                    'cutting_speed' => round($actualCuttingSpeed, 1),
                    'feed_per_revolution' => round($feedPerRevolution, 4),
                    'spindle_rpm' => round($spindleRPM),
                    'feed_rate' => round($feedRate, 1),

                    // Мощность и силовые параметры
                    'cutting_power' => round($cuttingPower, 2),
                    'effective_power' => round($effectivePower, 2),
                    'cutting_force' => round($cuttingForce, 1),
                    'torque' => round($torque, 1),
                    'material_removal_rate' => round($materialRemovalRate, 2),

                    // Время обработки
                    'cutting_time_per_pass' => round($cuttingTimePerPass, 2),
                    'total_cutting_time' => round($totalCuttingTime, 2),

                    // Новые параметры из документа
                    'operation_type' => $operationType,
                    'operation_subtype' => $operationSubtype,
                    'operation_subtype_name' => $operationSubtype == 'external_turning' ? 'Наружное точение' : 'Растачивание',
                    'surface_quality' => $surfaceQuality,
                    'is_feed_radius_compatible' => $isFeedRadiusCompatible,
                    'recommended_radius' => $recommendedRadius,

                    // Проверки и статусы
                    'is_rpm_valid' => $isRpmValid,
                    'is_power_valid' => $isPowerValid,
                    'is_depth_valid' => $isDepthValid,

                    // Флаги значений по умолчанию
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
     * РЕАЛЬНЫЕ ФОРМУЛЫ РАСЧЕТА С ЗАЩИТОЙ ОТ ДЕЛЕНИЯ НА НОЛЬ
     */

    /**
     * Определяем состояние станка на основе введенных данных
     */
    private function updateMachineCondition($machineType, $request)
    {
        $machineAge = $request->machine_age ?? 'normal';
        $customYears = $request->custom_years ?? null;

        // Если выбран конкретный станок из БД, используем его данные как основу
        // но переопределяем состояние на основе выбора пользователя

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

    // 1. Формула глубины резания
    private function calculateDepthOfCut($initialDiameter, $finalDiameter)
    {
        return max(0, ($initialDiameter - $finalDiameter) / 2);
    }

    // 2. Расчет количества проходов с учетом типа обработки
    private function calculateNumberOfPasses($depthOfCut, $toolGeometry, $operationType, $operationSubtype)
    {
        if ($depthOfCut <= 0) return 1;

        $maxDepthPerPass = $toolGeometry->max_depth_of_cut ?: 2.0;

        // Коэффициенты для разных типов обработки
        if ($operationType === 'roughing') {
            $maxDepthPerPass *= 0.8; // Для черновой обработки берем 80% от максимума
        } else {
            $maxDepthPerPass *= 0.3; // Для чистовой - 30%
        }

        // Дополнительный коэффициент для растачивания
        if ($operationSubtype === 'internal_turning') {
            $maxDepthPerPass *= 0.7; // Для растачивания уменьшаем глубину
        }

        $passes = ceil($depthOfCut / max(0.001, $maxDepthPerPass));
        return max(1, $passes);
    }

    // 3. Расчет глубины резания на проход
    private function calculateDepthPerPass($depthOfCut, $passes)
    {
        if ($passes <= 0) return 0;
        return $depthOfCut / $passes;
    }

    // 4. Расчет средней диаметра
    private function calculateAverageDiameter($initialDiameter, $finalDiameter)
    {
        return ($initialDiameter + $finalDiameter) / 2;
    }

    // 5. Расчет скорости резания
    private function calculateCuttingSpeed($material, $toolMaterial, $toolGeometry, $operationType, $surfaceQuality)
    {
        // Базовая скорость из материала
        $baseSpeed = (($material->cutting_speed_min ?: 50) + ($material->cutting_speed_max ?: 150)) / 2;

        // Коэффициент инструментального материала
        $toolFactor = $toolMaterial->speed_factor ?: 1.0;

        // Коэффициент геометрии инструмента
        $geometryFactor = $toolGeometry->speed_factor ?: 1.0;

        // Коэффициент операции и качества поверхности
        $operationFactor = $this->getOperationFactor($operationType, 'external_turning', $surfaceQuality, 'speed_factor');

        // Расчет скорости резания
        $speed = $baseSpeed * $toolFactor * $geometryFactor * $operationFactor;

        // Ограничение по максимальной скорости инструмента
        $maxSpeed = $toolMaterial->max_cutting_speed ?: 300;
        return min($speed, $maxSpeed * 0.9);
    }

    // 6. Расчет подачи на оборот с учетом типа обработки
    private function calculateFeedPerRevolution($material, $toolGeometry, $depthPerPass, $operationType, $surfaceQuality, $operationSubtype)
    {
        // Базовая подача из материала
        $baseFeed = (($material->feed_min ?: 0.05) + ($material->feed_max ?: 0.3)) / 2;

        // Коэффициент геометрии инструмента
        $geometryFactor = $toolGeometry->feed_factor ?: 1.0;

        // Коэффициент операции и качества поверхности с учетом типа обработки
        $operationFactor = $this->getOperationFactor($operationType, $operationSubtype, $surfaceQuality, 'feed_factor');

        // Коэффициент глубины резания
        $depthFactor = $this->getDepthFactor($depthPerPass);

        // Расчет подачи
        $feed = $baseFeed * $geometryFactor * $operationFactor * $depthFactor;

        // Ограничение по минимальной и максимальной подаче материала
        $minFeed = $material->feed_min ?: 0.05;
        $maxFeed = $material->feed_max ?: 0.4;
        return max($minFeed, min($feed, $maxFeed));
    }

    // 7. Формула оборотов шпинделя: n = (1000 × V) / (π × D)
    private function calculateSpindleRPM($cuttingSpeed, $diameter)
    {
        if ($diameter <= 0 || $cuttingSpeed <= 0) return 0;
        return ($cuttingSpeed * 1000) / (pi() * max(0.001, $diameter));
    }

    // 8. Формула скорости резания: V = (π × D × n) / 1000
    private function calculateActualCuttingSpeed($rpm, $diameter)
    {
        if ($diameter <= 0 || $rpm <= 0) return 0;
        return (pi() * $diameter * $rpm) / 1000;
    }

    // 9. Расчет минутной подачи
    private function calculateFeedRate($feedPerRevolution, $rpm)
    {
        if ($feedPerRevolution <= 0 || $rpm <= 0) return 0;
        return $feedPerRevolution * $rpm;
    }

    // 10. Формула мощности резания: P = (V × S × t × K) / 60
    private function calculateCuttingPower($depth, $feed, $speed, $material, $toolGeometry)
    {
        if ($depth <= 0 || $feed <= 0 || $speed <= 0) return 0;

        $materialRemovalRate = $depth * $feed * $speed; // см³/мин
        $specificPower = ($material->power_factor ?: 1.0) * ($toolGeometry->power_factor ?: 1.0); // кВт/см³/мин

        return ($materialRemovalRate * $specificPower) / 60; // кВт
    }

    // 11. Расчет эффективной мощности
    private function calculateEffectivePower($cuttingPower, $machineType)
    {
        $efficiency = $machineType->efficiency ?: 0.85;
        if ($efficiency <= 0) return $cuttingPower;
        return $cuttingPower / $efficiency;
    }

    // 12. Формула времени обработки
    private function calculateCuttingTime($length, $feedRate)
    {
        if ($feedRate <= 0) return 0;
        return ($length / $feedRate) * 60; // в минутах
    }

    // 13. Расчет общего времени обработки
    private function calculateTotalCuttingTime($cuttingTimePerPass, $passes)
    {
        return $cuttingTimePerPass * max(1, $passes);
    }

    // 14. Расчет съема материала
    private function calculateMaterialRemovalRate($depth, $feed, $speed)
    {
        if ($depth <= 0 || $feed <= 0 || $speed <= 0) return 0;
        return $depth * $feed * $speed; // см³/мин
    }

    // 15. Расчет усилия резания
    private function calculateCuttingForce($depth, $feed, $material)
    {
        if ($depth <= 0 || $feed <= 0) return 0;
        // P = t × s × k (Н)
        return $depth * $feed * ($material->specific_pressure ?: 1500);
    }

    // 16. Расчет крутящего момента
    private function calculateTorque($cuttingForce, $diameter)
    {
        if ($cuttingForce <= 0 || $diameter <= 0) return 0;
        // M = P × D/2 (Н·м)
        return $cuttingForce * ($diameter / 2000); // перевод в метры
    }

    // 17. Учет состояния станка (новый метод)
    private function applyMachineConditionFactor($value, $machineType)
    {
        return $value * $machineType->condition_factor;
    }

    // 18. Проверка соответствия радиуса инструмента и подачи (новый метод)
    private function checkFeedRadiusCompatibility($feed, $cornerRadius)
    {
        // Правило из документа: чем больше подача, тем больше должен быть радиус
        $minRadiusForFeed = $feed * 0.8; // эмпирическая формула

        return $cornerRadius >= $minRadiusForFeed;
    }

    // 19. Расчет рекомендуемого радиуса инструмента по подаче (новый метод)
    private function calculateRecommendedRadius($feed)
    {
        // На основе данных из документа о стандартных радиусах
        if ($feed <= 0.1) return 0.4;
        if ($feed <= 0.2) return 0.8;
        if ($feed <= 0.4) return 1.2;
        if ($feed <= 0.6) return 1.6;
        if ($feed <= 0.8) return 2.0;
        return 2.4;
    }

    /**
     * ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ
     */

    // Обновленная функция с учетом типа обработки
    private function getOperationFactor($operationType, $operationSubtype, $surfaceQuality, $factorType)
    {
        $factor = OperationFactor::where('operation_type', $operationType)
            ->where('operation_subtype', $operationSubtype)
            ->where('surface_quality', $surfaceQuality)
            ->first();

        return $factor ? ($factor->{$factorType} ?: 1.0) : 1.0;
    }

    private function getDepthFactor($depth)
    {
        if ($depth <= 1) return 1.0;
        if ($depth <= 3) return 0.9;
        if ($depth <= 5) return 0.8;
        return 0.7;
    }

    private function getDefaultMachineType()
    {
        return new MachineType([
            'name' => 'Токарный станок (стандарт)',
            'power_range' => '7.5-11 кВт',
            'max_rpm' => 2000,
            'rigidity_factor' => 1.0,
            'efficiency' => 0.85,
            'max_power_kw' => 11.0,
            'years_in_service' => 5,
            'condition_factor' => 0.9,
            'machine_condition' => 'normal'
        ]);
    }
}
