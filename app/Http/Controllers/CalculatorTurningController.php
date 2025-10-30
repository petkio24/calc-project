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
        $request->validate([
            'material_id' => 'required|exists:turning_materials,id',
            'tool_material_id' => 'required|exists:tool_materials,id',
            'tool_geometry_id' => 'required|exists:tool_geometries,id',
            'machine_type_id' => 'required|exists:machine_types,id',
            'initial_diameter' => 'required|numeric|min:0.1',
            'final_diameter' => 'required|numeric|min:0.1',
            'operation_type' => 'required|in:roughing,finishing',
            'surface_quality' => 'required|in:normal,good,excellent'
        ]);

        // Получаем данные из БД
        $material = TurningMaterial::find($request->material_id);
        $toolMaterial = ToolMaterial::find($request->tool_material_id);
        $toolGeometry = ToolGeometry::find($request->tool_geometry_id);
        $machineType = MachineType::find($request->machine_type_id);

        // Входные параметры
        $initialDiameter = $request->initial_diameter;
        $finalDiameter = $request->final_diameter;
        $operationType = $request->operation_type;
        $surfaceQuality = $request->surface_quality;

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

        // Проверка ограничений станка
        $isRpmValid = $spindleRPM <= $machineType->max_rpm;
        $isPowerValid = $cuttingPower <= 7.5; // Предполагаемая мощность станка

        return view('calculators.turning', [
            'title' => 'Профессиональный калькулятор точения',
            'operation' => 'turning',
            'materials' => TurningMaterial::all(),
            'toolMaterials' => ToolMaterial::all(),
            'toolGeometries' => ToolGeometry::all(),
            'machineTypes' => MachineType::all(),
            'result' => [
                'material' => $material->name,
                'tool_material' => $toolMaterial->name,
                'tool_geometry' => $toolGeometry->name,
                'machine_type' => $machineType->name,
                'initial_diameter' => $initialDiameter,
                'final_diameter' => $finalDiameter,
                'depth_of_cut' => round($depthOfCut, 3),
                'cutting_speed' => round($cuttingSpeed, 1),
                'feed' => round($feed, 4),
                'spindle_rpm' => round($spindleRPM),
                'cutting_power' => round($cuttingPower, 2),
                'cutting_time' => round($cuttingTime, 1),
                'is_rpm_valid' => $isRpmValid,
                'is_power_valid' => $isPowerValid,
                'operation_type' => $operationType,
                'surface_quality' => $surfaceQuality
            ]
        ]);
    }

    /**
     * Расчет базовой скорости резания
     */
    private function calculateBaseCuttingSpeed($material, $toolMaterial)
    {
        $baseSpeed = ($material->cutting_speed_min + $material->cutting_speed_max) / 2;

        // Корректировка по материалу инструмента
        $toolFactor = $toolMaterial->wear_resistance_factor;

        return $baseSpeed * $toolFactor;
    }

    /**
     * Применение корректирующих коэффициентов
     */
    private function applyCorrectionFactors($baseSpeed, $toolMaterial, $toolGeometry, $operationType, $surfaceQuality)
    {
        $speed = $baseSpeed;

        // Корректировка по геометрии инструмента
        $speed *= $toolGeometry->feed_factor;

        // Корректировка по типу операции
        if ($operationType === 'roughing') {
            $speed *= 0.8; // Снижение скорости для черновой обработки
        } else {
            $speed *= 1.2; // Повышение скорости для чистовой обработки
        }

        // Корректировка по качеству поверхности
        if ($surfaceQuality === 'excellent') {
            $speed *= 1.3;
        } elseif ($surfaceQuality === 'good') {
            $speed *= 1.1;
        }

        // Ограничение по максимальной скорости инструмента
        $speed = min($speed, $toolMaterial->max_cutting_speed * 0.9);

        return $speed;
    }

    /**
     * Расчет подачи
     */
    private function calculateFeed($material, $toolGeometry, $operationType, $surfaceQuality, $depthOfCut)
    {
        $baseFeed = ($material->recommended_feed_min + $material->recommended_feed_max) / 2;

        // Корректировка по геометрии инструмента
        $feed = $baseFeed * $toolGeometry->feed_factor;

        // Корректировка по типу операции
        if ($operationType === 'roughing') {
            $feed *= 1.5; // Увеличение подачи для черновой обработки
        } else {
            $feed *= 0.6; // Уменьшение подачи для чистовой обработки
        }

        // Корректировка по качеству поверхности
        if ($surfaceQuality === 'excellent') {
            $feed *= 0.5;
        } elseif ($surfaceQuality === 'good') {
            $feed *= 0.8;
        }

        // Корректировка по глубине резания
        if ($depthOfCut > 3) {
            $feed *= 0.7; // Уменьшение подачи при большой глубине резания
        }

        return max($feed, $material->recommended_feed_min);
    }

    /**
     * Расчет оборотов шпинделя
     */
    private function calculateSpindleRPM($cuttingSpeed, $diameter, $machineType)
    {
        $rpm = ($cuttingSpeed * 1000) / (pi() * $diameter);

        // Ограничение по максимальным оборотам станка
        return min($rpm, $machineType->max_rpm * 0.9);
    }

    /**
     * Расчет мощности резания
     */
    private function calculateCuttingPower($depthOfCut, $feed, $cuttingSpeed, $material, $toolGeometry)
    {
        $materialRemovalRate = $depthOfCut * $feed * $cuttingSpeed;
        $specificPower = $material->power_factor * $toolGeometry->feed_factor;

        return ($materialRemovalRate * $specificPower) / 60;
    }

    /**
     * Расчет времени обработки
     */
    private function calculateCuttingTime($initialDiameter, $finalDiameter, $feed, $rpm)
    {
        $cuttingLength = abs($initialDiameter - $finalDiameter) * 10; // Условная длина резания
        $feedRate = $feed * $rpm; // Минутная подача

        return $feedRate > 0 ? $cuttingLength / $feedRate : 0;
    }
}
