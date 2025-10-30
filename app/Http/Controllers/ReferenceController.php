<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DrillingMaterial;
use App\Models\TurningMaterial;
use App\Models\MillingMaterial;
use App\Models\ToolMaterial;
use App\Models\ToolGeometry;
use App\Models\MachineType;

class ReferenceController extends Controller
{
    /**
     * Показываем главную страницу справочников
     */
    public function index()
    {
        $materials = TurningMaterial::all()->groupBy('material_group');
        $toolMaterials = ToolMaterial::all()->groupBy('material_type');

        return view('references.index', [
            'title' => 'Справочники',
            'activeTab' => 'overview',
            'materials' => $materials,
            'toolMaterials' => $toolMaterials
        ]);
    }

    /**
     * Показываем справочник материалов для сверления
     */
    public function drillingMaterials()
    {
        $materials = DrillingMaterial::all();

        return view('references.drilling-materials', [
            'title' => 'Справочник материалов для сверления',
            'activeTab' => 'drilling',
            'materials' => $materials
        ]);
    }

    /**
     * Показываем справочник материалов для точения
     */
    public function turningMaterials()
    {
        $materials = TurningMaterial::all()->groupBy('material_group');

        return view('references.turning-materials', [
            'title' => 'Справочник материалов для точения',
            'activeTab' => 'turning',
            'materials' => $materials
        ]);
    }

    /**
     * Показываем справочник материалов для фрезерования
     */
    public function millingMaterials()
    {
        $materials = MillingMaterial::all();

        return view('references.milling-materials', [
            'title' => 'Справочник материалов для фрезерования',
            'activeTab' => 'milling',
            'materials' => $materials
        ]);
    }

    /**
     * Показываем справочник материалов инструмента
     */
    public function toolMaterials()
    {
        $materials = ToolMaterial::all()->groupBy('material_type');

        return view('references.tool-materials', [
            'title' => 'Справочник материалов инструмента',
            'activeTab' => 'tools',
            'materials' => $materials
        ]);
    }

    /**
     * Показываем справочник геометрии инструмента
     */
    public function toolGeometries()
    {
        $geometries = ToolGeometry::all();

        return view('references.tool-geometries', [
            'title' => 'Справочник маркировки инструмента',
            'activeTab' => 'tools',
            'geometries' => $geometries
        ]);
    }

    /**
     * Показываем справочник типов станков
     */
    public function machineTypes()
    {
        $machines = MachineType::all();

        return view('references.machine-types', [
            'title' => 'Справочник типов станков',
            'activeTab' => 'machines',
            'machines' => $machines
        ]);
    }
}
