<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    TurningMaterial, ToolMaterial, ToolGeometry, MachineType,
    DrillingMaterial, MillingMaterial, DrillingTool, MillingTool
};

class ReferenceController extends Controller
{
    private $activeTab;

    public function __construct()
    {
        $this->activeTab = 'overview';
    }

    public function index()
    {
        $this->activeTab = 'overview';

        return view('references.index', [
            'activeTab' => $this->activeTab,
            'materials' => [
                'black_metals' => TurningMaterial::where('material_group', 'black_metals')->get(),
                'nonferrous_metals' => TurningMaterial::where('material_group', 'nonferrous_metals')->get(),
                'non_metals' => TurningMaterial::where('material_group', 'non_metals')->get(),
            ],
            'toolMaterials' => [
                'hard_alloy' => ToolMaterial::where('material_type', 'hard_alloy')->get(),
                'high_speed_steel' => ToolMaterial::where('material_type', 'high_speed_steel')->get(),
            ]
        ]);
    }

    // СПРАВОЧНИКИ МАТЕРИАЛОВ
    public function turningMaterials()
    {
        $materials = TurningMaterial::orderBy('material_group')->orderBy('name')->get();
        $groupedMaterials = $materials->groupBy('material_group');

        return view('references.turning-materials', [
            'activeTab' => 'turning',
            'materials' => $groupedMaterials
        ]);
    }

    public function drillingMaterials()
    {
        $materials = DrillingMaterial::orderBy('material_group')->orderBy('name')->get();

        return view('references.drilling-materials', [
            'activeTab' => 'drilling',
            'materials' => $materials
        ]);
    }

    public function millingMaterials()
    {
        $materials = MillingMaterial::orderBy('material_group')->orderBy('name')->get();

        return view('references.milling-materials', [
            'activeTab' => 'milling',
            'materials' => $materials
        ]);
    }

    // СПРАВОЧНИКИ ИНСТРУМЕНТОВ
    public function toolMaterials()
    {
        $materials = ToolMaterial::orderBy('material_type')->orderBy('name')->get();
        $groupedMaterials = $materials->groupBy('material_type');

        return view('references.tool-materials', [
            'activeTab' => 'tools',
            'materials' => $groupedMaterials
        ]);
    }

    public function toolGeometries()
    {
        $geometries = ToolGeometry::orderBy('shape')->orderBy('name')->get();

        return view('references.tool-geometries', [
            'activeTab' => 'tools',
            'geometries' => $geometries
        ]);
    }

    public function drillingTools()
    {
        $tools = DrillingTool::orderBy('tool_type')->orderBy('name')->get();

        return view('references.drilling-tools', [
            'activeTab' => 'drilling',
            'tools' => $tools
        ]);
    }

    public function millingTools()
    {
        $tools = MillingTool::orderBy('tool_type')->orderBy('name')->get();

        return view('references.milling-tools', [
            'activeTab' => 'milling',
            'tools' => $tools
        ]);
    }

    // СПРАВОЧНИК СТАНКОВ
    public function machineTypes()
    {
        $machines = MachineType::orderBy('machine_category')->orderBy('name')->get();

        return view('references.machine-types', [
            'activeTab' => 'machines',
            'machines' => $machines
        ]);
    }

    // ПОИСК И ФИЛЬТРАЦИЯ
    public function search(Request $request)
    {
        $query = $request->get('q');
        $type = $request->get('type', 'all');

        $results = [];

        if ($type === 'all' || $type === 'materials') {
            $results['turning_materials'] = TurningMaterial::where('name', 'ILIKE', "%{$query}%")
                ->orWhere('material_group_name', 'ILIKE', "%{$query}%")
                ->get();

            $results['drilling_materials'] = DrillingMaterial::where('name', 'ILIKE', "%{$query}%")
                ->orWhere('material_group_name', 'ILIKE', "%{$query}%")
                ->get();

            $results['milling_materials'] = MillingMaterial::where('name', 'ILIKE', "%{$query}%")
                ->orWhere('material_group_name', 'ILIKE', "%{$query}%")
                ->get();
        }

        if ($type === 'all' || $type === 'tools') {
            $results['tool_materials'] = ToolMaterial::where('name', 'ILIKE', "%{$query}%")
                ->orWhere('grade', 'ILIKE', "%{$query}%")
                ->get();

            $results['tool_geometries'] = ToolGeometry::where('name', 'ILIKE', "%{$query}%")
                ->orWhere('shape_name', 'ILIKE', "%{$query}%")
                ->get();
        }

        if ($type === 'all' || $type === 'machines') {
            $results['machine_types'] = MachineType::where('name', 'ILIKE', "%{$query}%")
                ->orWhere('machine_category', 'ILIKE', "%{$query}%")
                ->get();
        }

        return view('references.search', [
            'activeTab' => 'search',
            'query' => $query,
            'type' => $type,
            'results' => $results
        ]);
    }
}
