<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DrillingMaterial;

class CalculatorDrillingController extends Controller
{
    /**
     * Показываем калькулятор сверления
     */
    public function index()
    {
        $materials = DrillingMaterial::all();

        return view('calculators.drilling', [
            'title' => 'Калькулятор сверления',
            'operation' => 'drilling',
            'materials' => $materials
        ]);
    }

    /**
     * Выполняем расчет
     */
    public function calculate(Request $request)
    {
        $request->validate([
            'material_id' => 'required|exists:drilling_materials,id',
            'diameter' => 'required|numeric|min:0.1',
            'machine_power' => 'required|numeric|min:0.1'
        ]);

        $material = DrillingMaterial::find($request->material_id);
        $diameter = $request->diameter;
        $machinePower = $request->machine_power;

        // Расчет средней скорости резания и подачи
        $cuttingSpeed = ($material->cutting_speed_min + $material->cutting_speed_max) / 2;
        $feed = ($material->feed_per_rev_min + $material->feed_per_rev_max) / 2;

        // Расчет оборотов шпинделя (N = Vc * 1000 / (π * D))
        $spindleRPM = ($cuttingSpeed * 1000) / (pi() * $diameter);

        // Расчет мощности резания
        $cuttingPower = ($diameter * $feed * $cuttingSpeed * $material->power_factor) / 60;

        return view('calculators.drilling', [
            'title' => 'Калькулятор сверления',
            'operation' => 'drilling',
            'materials' => DrillingMaterial::all(),
            'result' => [
                'material' => $material->name,
                'diameter' => $diameter,
                'cutting_speed' => round($cuttingSpeed, 2),
                'feed' => round($feed, 4),
                'spindle_rpm' => round($spindleRPM),
                'cutting_power' => round($cuttingPower, 2),
                'machine_power' => $machinePower,
                'is_power_sufficient' => $cuttingPower <= $machinePower
            ]
        ]);
    }
}
