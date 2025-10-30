<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TurningMaterial;
use App\Models\ToolMaterial;
use App\Models\ToolGeometry;
use App\Models\MachineType;

class CalculatorTurningController extends Controller
{
    /**
     * Показываем калькулятор точения
     */
    public function index()
    {
        $materials = TurningMaterial::all();
        $toolMaterials = ToolMaterial::all();
        $toolGeometries = ToolGeometry::all();
        $machineTypes = MachineType::all();

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
     * Выполняем расчет
     */
    public function calculate(Request $request)
    {
        // Обязательные поля
        $request->validate([
            'material_id' => 'required|exists:turning_materials,id',
            'tool_material_id' => 'required|exists:tool_materials,id',
            'initial_diameter' => 'required|numeric|min:0.1',
            'final_diameter' => 'required|numeric|min:0.1',
        ]);

        // Необязательные поля с значениями по умолчанию
        $toolGeometryId = $request->tool_geometry_id;
        $machineTypeId = $request->machine_type_id;
        $operationType = $request->operation_type ?? 'roughing';
        $surfaceQuality = $request->surface_quality ?? 'normal';

        // Получаем данные из БД
        $material = TurningMaterial::find($request->material_id);
        $toolMaterial = ToolMaterial::find($request->tool_material_id);

        // Значения по умолчанию для необязательных полей
        $toolGeometry = $toolGeometryId ? ToolGeometry::find($toolGeometryId) : $this->getDefaultToolGeometry();
        $machineType = $machineTypeId ? MachineType::find($machineTypeId) : $this->getDefaultMachineType();

        // Входные параметры
        $initialDiameter = $request->initial_diameter;
        $finalDiameter = $request->final_diameter;

        // Расчет глубины резания
        $depthOfCut = ($initialDiameter - $finalDiameter) / 2;

        // Расчет скорости резания с учетом всех факторов
        $baseCuttingSpeed = $this->calculateBaseCuttingSpeed($material, $toolMaterial);
        $cuttingSpeed = $this->applyCorrectionFactors(
            $baseCuttingSpeed,
            $toolMaterial,
            $toolGeometry,
            $operationType,
            $surfaceQuality
        );

        // Расчет подачи
        $feed = $this->calculateFeed($material, $toolGeometry, $operationType, $surfaceQuality, $depthOfCut);

        // Расчет оборотов шпинделя
        $averageDiameter = ($initialDiameter + $finalDiameter) / 2;
        $spindleRPM = $this->calculateSpindleRPM($cuttingSpeed, $averageDiameter, $machineType);

        // Расчет мощности резания
        $cuttingPower = $this->calculateCuttingPower($depthOfCut, $feed, $cuttingSpeed, $material, $toolGeometry);

        // Расчет времени обработки (приблизительно)
        $cuttingTime = $this->calculateCuttingTime($initialDiameter, $finalDiameter, $feed, $spindleRPM);

        // Расчет материалоемкости
        $materialRemovalRate = $this->calculateMaterialRemovalRate($depthOfCut, $feed, $cuttingSpeed);

        // Проверка ограничений станка (только если выбран станок)
        $isRpmValid = $machineTypeId ? $spindleRPM <= $machineType->max_rpm : true;
        $isPowerValid = $machineTypeId ? $cuttingPower <= $this->getMachinePower($machineType) : true;

        // Флаги использования значений по умолчанию
        $usedDefaultToolGeometry = !$toolGeometryId;
        $usedDefaultMachineType = !$machineTypeId;

        // Дополнительные расчеты
        $feedRate = $feed * $spindleRPM; // Минутная подача
        $specificPower = $material->power_factor * $toolGeometry->feed_factor;

        return view('calculators.turning', [
            'title' => 'Профессиональный калькулятор точения',
            'operation' => 'turning',
            'materials' => TurningMaterial::all(),
            'toolMaterials' => ToolMaterial::all(),
            'toolGeometries' => ToolGeometry::all(),
            'machineTypes' => MachineType::all(),
            'result' => [
                // Основные параметры
                'material' => $material->name,
                'tool_material' => $toolMaterial->name,
                'tool_geometry' => $toolGeometry->name,
                'machine_type' => $machineType->name,
                'initial_diameter' => $initialDiameter,
                'final_diameter' => $finalDiameter,
                'depth_of_cut' => round($depthOfCut, 3),

                // Режимы резания
                'cutting_speed' => round($cuttingSpeed, 1),
                'feed' => round($feed, 4),
                'spindle_rpm' => round($spindleRPM),
                'feed_rate' => round($feedRate, 1),

                // Мощность и энергетика
                'cutting_power' => round($cuttingPower, 2),
                'specific_power' => round($specificPower, 3),
                'material_removal_rate' => round($materialRemovalRate, 2),

                // Время обработки
                'cutting_time' => round($cuttingTime, 1),

                // Проверки и статусы
                'is_rpm_valid' => $isRpmValid,
                'is_power_valid' => $isPowerValid,
                'operation_type' => $operationType,
                'surface_quality' => $surfaceQuality,

                // Флаги значений по умолчанию
                'used_default_tool_geometry' => $usedDefaultToolGeometry,
                'used_default_machine_type' => $usedDefaultMachineType,

                // Дополнительная информация
                'tool_material_max_speed' => $toolMaterial->max_cutting_speed,
                'machine_max_rpm' => $machineType->max_rpm,
                'machine_power_range' => $machineType->power_range,
                'tool_geometry_angles' => [
                    'rake' => $toolGeometry->rake_angle,
                    'clearance' => $toolGeometry->clearance_angle,
                    'cutting_edge' => $toolGeometry->cutting_edge_angle
                ]
            ]
        ]);
    }

    /**
     * Значения по умолчанию для необязательных полей
     */
    private function getDefaultToolGeometry()
    {
        return new \App\Models\ToolGeometry([
            'name' => 'Универсальная (стандарт)',
            'rake_angle' => 6.00,
            'clearance_angle' => 7.00,
            'cutting_edge_angle' => 60.00,
            'feed_factor' => 1.0000
        ]);
    }

    private function getDefaultMachineType()
    {
        return new \App\Models\MachineType([
            'name' => 'Токарный станок (стандарт)',
            'power_range' => '5-10 кВт',
            'max_rpm' => 2500,
            'rigidity_factor' => 1.0000
        ]);
    }

    private function getMachinePower($machineType)
    {
        // Извлекаем максимальную мощность из диапазона "5-15 кВт"
        if (preg_match('/(\d+)-(\d+)/', $machineType->power_range, $matches)) {
            return (float) $matches[2]; // Берем верхнюю границу диапазона
        }
        return 7.5; // Значение по умолчанию
    }

    private function calculateBaseCuttingSpeed($material, $toolMaterial)
    {
        $baseSpeed = ($material->cutting_speed_min + $material->cutting_speed_max) / 2;
        $toolFactor = $toolMaterial->wear_resistance_factor;
        return $baseSpeed * $toolFactor;
    }

    private function applyCorrectionFactors($baseSpeed, $toolMaterial, $toolGeometry, $operationType, $surfaceQuality)
    {
        $speed = $baseSpeed;
        $speed *= $toolGeometry->feed_factor;

        if ($operationType === 'roughing') {
            $speed *= 0.8;
        } else {
            $speed *= 1.2;
        }

        if ($surfaceQuality === 'excellent') {
            $speed *= 1.3;
        } elseif ($surfaceQuality === 'good') {
            $speed *= 1.1;
        }

        $speed = min($speed, $toolMaterial->max_cutting_speed * 0.9);
        return $speed;
    }

    private function calculateFeed($material, $toolGeometry, $operationType, $surfaceQuality, $depthOfCut)
    {
        $baseFeed = ($material->recommended_feed_min + $material->recommended_feed_max) / 2;
        $feed = $baseFeed * $toolGeometry->feed_factor;

        if ($operationType === 'roughing') {
            $feed *= 1.5;
        } else {
            $feed *= 0.6;
        }

        if ($surfaceQuality === 'excellent') {
            $feed *= 0.5;
        } elseif ($surfaceQuality === 'good') {
            $feed *= 0.8;
        }

        if ($depthOfCut > 3) {
            $feed *= 0.7;
        }

        return max($feed, $material->recommended_feed_min);
    }

    private function calculateSpindleRPM($cuttingSpeed, $diameter, $machineType)
    {
        $rpm = ($cuttingSpeed * 1000) / (pi() * $diameter);
        return min($rpm, $machineType->max_rpm * 0.9);
    }

    private function calculateCuttingPower($depthOfCut, $feed, $cuttingSpeed, $material, $toolGeometry)
    {
        $materialRemovalRate = $depthOfCut * $feed * $cuttingSpeed;
        $specificPower = $material->power_factor * $toolGeometry->feed_factor;
        return ($materialRemovalRate * $specificPower) / 60;
    }

    private function calculateMaterialRemovalRate($depthOfCut, $feed, $cuttingSpeed)
    {
        return $depthOfCut * $feed * $cuttingSpeed;
    }

    private function calculateCuttingTime($initialDiameter, $finalDiameter, $feed, $rpm)
    {
        $cuttingLength = abs($initialDiameter - $finalDiameter) * 10;
        $feedRate = $feed * $rpm;
        return $feedRate > 0 ? $cuttingLength / $feedRate : 0;
    }
}
