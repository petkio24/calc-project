<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MillingMaterial;

class CalculatorMillingController extends Controller
{
    /**
     * Показываем калькулятор фрезерования
     */
    public function index()
    {
        $materials = MillingMaterial::all();

        return view('calculators.milling', [
            'title' => 'Калькулятор фрезерования',
            'operation' => 'milling',
            'materials' => $materials
        ]);
    }

    /**
     * Выполняем расчет
     */
    public function calculate(Request $request)
    {
        $request->validate([
            'material_id' => 'required|exists:milling_materials,id',
            'cutter_diameter' => 'required|numeric|min:0.1',
            'number_of_teeth' => 'required|numeric|min:1',
            'width_of_cut' => 'required|numeric|min:0.1',
            'depth_of_cut' => 'required|numeric|min:0.1',
            'machine_power' => 'required|numeric|min:0.1'
        ]);

        $material = MillingMaterial::find($request->material_id);
        $cutterDiameter = $request->cutter_diameter;
        $numberOfTeeth = $request->number_of_teeth;
        $widthOfCut = $request->width_of_cut;
        $depthOfCut = $request->depth_of_cut;
        $machinePower = $request->machine_power;

        // Расчет средней скорости резания и подачи на зуб
        $cuttingSpeed = ($material->cutting_speed_min + $material->cutting_speed_max) / 2;
        $feedPerTooth = ($material->feed_per_tooth_min + $material->feed_per_tooth_max) / 2;

        // Расчет оборотов шпинделя (N = Vc * 1000 / (π * D))
        $spindleRPM = ($cuttingSpeed * 1000) / (pi() * $cutterDiameter);

        // Расчет минутной подачи (Vf = fz * z * N)
        $feedRate = $feedPerTooth * $numberOfTeeth * $spindleRPM;

        // Расчет мощности резания для фрезерования
        $materialRemovalRate = $widthOfCut * $depthOfCut * $feedRate / 1000;
        $cuttingPower = ($materialRemovalRate * $material->power_factor) / 60;

        return view('calculators.milling', [
            'title' => 'Калькулятор фрезерования',
            'operation' => 'milling',
            'materials' => MillingMaterial::all(),
            'result' => [
                'material' => $material->name,
                'cutter_diameter' => $cutterDiameter,
                'number_of_teeth' => $numberOfTeeth,
                'width_of_cut' => $widthOfCut,
                'depth_of_cut' => $depthOfCut,
                'cutting_speed' => round($cuttingSpeed, 2),
                'feed_per_tooth' => round($feedPerTooth, 4),
                'feed_rate' => round($feedRate, 1),
                'spindle_rpm' => round($spindleRPM),
                'cutting_power' => round($cuttingPower, 2),
                'machine_power' => $machinePower,
                'is_power_sufficient' => $cuttingPower <= $machinePower
            ]
        ]);
    }
}
