@extends('layouts.app')

@section('title', 'Профессиональный калькулятор точения')

@section('styles')
    <link href="{{ asset('css/calculator_turning.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="calculator-container">
        <!-- Хлебные крошки -->
        <nav class="calc-breadcrumbs">
            <a href="{{ route('home') }}" class="calc-breadcrumbs__item">Главная</a>
            <span class="calc-breadcrumbs__separator">›</span>
            <span class="calc-breadcrumbs__item active">Профессиональный калькулятор точения</span>
        </nav>

        <!-- Заголовок -->
        <div class="calc-header">
            <h1>Профессиональный расчет режимов точения</h1>
            <h2>Точные параметры для оптимальной токарной обработки</h2>
        </div>

        <!-- Форма калькулятора -->
        <form method="POST" action="{{ route('calculators.turning.calculate') }}">
            @csrf

            <div class="calc-grid">
                <!-- Параметры заготовки -->
                <div class="calc-section">
                    <h3 class="section-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        Параметры заготовки
                    </h3>

                    <div class="calc-input-group">
                        <label class="calc-input-group__label" for="material_id">Материал заготовки</label>

                        <!-- Черные металлы -->
                        <div class="material-group">
                            <div class="material-group-title">Черные металлы</div>
                            @foreach($materials['black_metals'] ?? [] as $material)
                                <label class="material-option">
                                    <input type="radio" name="material_id" value="{{ $material->id }}"
                                           {{ old('material_id') == $material->id ? 'checked' : '' }} required>
                                    <span class="material-name">{{ $material->name }}</span>
                                    <span class="material-props">{{ $material->hardness_range }}</span>
                                </label>
                            @endforeach
                        </div>

                        <!-- Цветные металлы -->
                        <div class="material-group">
                            <div class="material-group-title">Цветные металлы</div>
                            @foreach($materials['nonferrous_metals'] ?? [] as $material)
                                <label class="material-option">
                                    <input type="radio" name="material_id" value="{{ $material->id }}"
                                           {{ old('material_id') == $material->id ? 'checked' : '' }} required>
                                    <span class="material-name">{{ $material->name }}</span>
                                    <span class="material-props">{{ $material->hardness_range }}</span>
                                </label>
                            @endforeach
                        </div>

                        <!-- Неметаллы -->
                        <div class="material-group">
                            <div class="material-group-title">Неметаллы</div>
                            @foreach($materials['non_metals'] ?? [] as $material)
                                <label class="material-option">
                                    <input type="radio" name="material_id" value="{{ $material->id }}"
                                           {{ old('material_id') == $material->id ? 'checked' : '' }} required>
                                    <span class="material-name">{{ $material->name }}</span>
                                    <span class="material-props">{{ $material->hardness_range }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="input-row">
                        <div class="calc-input-group">
                            <label class="calc-input-group__label" for="initial_diameter">Диаметр исходный (мм)</label>
                            <input class="calc-input-group__input" id="initial_diameter"
                                   name="initial_diameter" type="number" step="0.01" placeholder="Ø исходный"
                                   value="{{ old('initial_diameter') }}" required>
                        </div>

                        <div class="calc-input-group">
                            <label class="calc-input-group__label" for="final_diameter">Диаметр получаемый (мм)</label>
                            <input class="calc-input-group__input" id="final_diameter"
                                   name="final_diameter" type="number" step="0.01" placeholder="Ø получаемый"
                                   value="{{ old('final_diameter') }}" required>
                        </div>
                    </div>
                </div>

                <!-- Параметры инструмента -->
                <div class="calc-section">
                    <h3 class="section-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                        </svg>
                        Параметры инструмента
                    </h3>

                    <!-- Материал инструмента -->
                    <div class="calc-input-group">
                        <label class="calc-input-group__label">Материал инструмента</label>

                        <!-- Твердые сплавы -->
                        <div class="tool-material-group">
                            <div class="tool-material-title">Твердые сплавы</div>
                            @foreach($toolMaterials['hard_alloy'] ?? [] as $toolMaterial)
                                <label class="tool-material-option">
                                    <input type="radio" name="tool_material_id" value="{{ $toolMaterial->id }}"
                                           {{ old('tool_material_id') == $toolMaterial->id ? 'checked' : '' }} required>
                                    <span class="tool-material-name">{{ $toolMaterial->name }} ({{ $toolMaterial->grade }})</span>
                                    <span class="tool-material-props">до {{ $toolMaterial->max_cutting_speed }} м/мин</span>
                                </label>
                            @endforeach
                        </div>

                        <!-- Быстрорежущие стали -->
                        <div class="tool-material-group">
                            <div class="tool-material-title">Быстрорежущие стали</div>
                            @foreach($toolMaterials['high_speed_steel'] ?? [] as $toolMaterial)
                                <label class="tool-material-option">
                                    <input type="radio" name="tool_material_id" value="{{ $toolMaterial->id }}"
                                           {{ old('tool_material_id') == $toolMaterial->id ? 'checked' : '' }} required>
                                    <span class="tool-material-name">{{ $toolMaterial->name }} ({{ $toolMaterial->grade }})</span>
                                    <span class="tool-material-props">до {{ $toolMaterial->max_cutting_speed }} м/мин</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Маркировка инструмента -->
                    <div class="calc-input-group">
                        <label class="calc-input-group__label" for="tool_geometry_id">Маркировка пластины</label>
                        <select class="calc-input-group__input" id="tool_geometry_id" name="tool_geometry_id" required>
                            <option value="">Выберите маркировку пластины</option>
                            @foreach($toolGeometries as $geometry)
                                <option value="{{ $geometry->id }}"
                                        {{ old('tool_geometry_id') == $geometry->id ? 'selected' : '' }}
                                        data-shape="{{ $geometry->shape }}"
                                        data-clearance="{{ $geometry->clearance_angle }}"
                                        data-tolerance="{{ $geometry->tolerance_class }}"
                                        data-chipbreaker="{{ $geometry->chipbreaker_type }}"
                                        data-length="{{ $geometry->cutting_edge_length }}"
                                        data-thickness="{{ $geometry->insert_thickness }}"
                                        data-radius="{{ $geometry->corner_radius }}">
                                    {{ $geometry->name }} ({{ $geometry->shape_name }}, {{ $geometry->clearance_angle }}°, R{{ $geometry->corner_radius }})
                                </option>
                            @endforeach
                        </select>
                        <div class="geometry-details" id="geometryDetails" style="display: none;">
                            <div class="geometry-detail">
                                <span class="detail-label">Форма:</span>
                                <span class="detail-value" id="detailShape"></span>
                            </div>
                            <div class="geometry-detail">
                                <span class="detail-label">Задний угол:</span>
                                <span class="detail-value" id="detailClearance"></span>
                            </div>
                            <div class="geometry-detail">
                                <span class="detail-label">Класс точности:</span>
                                <span class="detail-value" id="detailTolerance"></span>
                            </div>
                            <div class="geometry-detail">
                                <span class="detail-label">Стружколом:</span>
                                <span class="detail-value" id="detailChipbreaker"></span>
                            </div>
                            <div class="geometry-detail">
                                <span class="detail-label">Длина кромки:</span>
                                <span class="detail-value" id="detailLength"></span>
                            </div>
                            <div class="geometry-detail">
                                <span class="detail-label">Толщина:</span>
                                <span class="detail-value" id="detailThickness"></span>
                            </div>
                            <div class="geometry-detail">
                                <span class="detail-label">Радиус скругления:</span>
                                <span class="detail-value" id="detailRadius"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Параметры обработки -->
                <div class="calc-section">
                    <h3 class="section-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14,2 14,8 20,8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10,9 9,9 8,9"></polyline>
                        </svg>
                        Параметры обработки
                        <span class="optional-badge">необязательно</span>
                    </h3>

                    <div class="calc-input-group">
                        <label class="calc-input-group__label" for="machine_type_id">
                            Тип станка
                            <span class="optional-hint">(если не указан, будет использован стандартный)</span>
                        </label>
                        <select class="calc-input-group__input" id="machine_type_id" name="machine_type_id">
                            <option value="">Выберите тип станка (опционально)</option>
                            @foreach($machineTypes as $machine)
                                <option value="{{ $machine->id }}"
                                    {{ old('machine_type_id') == $machine->id ? 'selected' : '' }}>
                                    {{ $machine->name }} ({{ $machine->power_range }}, до {{ $machine->max_rpm }} об/мин)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="input-row">
                        <div class="calc-input-group">
                            <label class="calc-input-group__label" for="operation_type">Тип операции</label>
                            <select class="calc-input-group__input" id="operation_type" name="operation_type">
                                <option value="roughing" {{ old('operation_type') == 'roughing' ? 'selected' : '' }}>Черновая обработка</option>
                                <option value="finishing" {{ old('operation_type') == 'finishing' ? 'selected' : '' }}>Чистовая обработка</option>
                            </select>
                        </div>

                        <div class="calc-input-group">
                            <label class="calc-input-group__label" for="surface_quality">Качество поверхности</label>
                            <select class="calc-input-group__input" id="surface_quality" name="surface_quality">
                                <option value="normal" {{ old('surface_quality') == 'normal' ? 'selected' : '' }}>Нормальное</option>
                                <option value="good" {{ old('surface_quality') == 'good' ? 'selected' : '' }}>Хорошее</option>
                                <option value="excellent" {{ old('surface_quality') == 'excellent' ? 'selected' : '' }}>Отличное</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Отдельная секция для кнопки расчета -->
            <div class="calc-actions-section">
                <div class="calc-actions">
                    <button type="submit" class="btn-calculate">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            <polyline points="7.5,4.21 12,6.81 16.5,4.21"></polyline>
                            <polyline points="7.5,19.79 7.5,14.6 3,12"></polyline>
                            <polyline points="21,12 16.5,14.6 16.5,19.79"></polyline>
                            <polyline points="3.27,6.96 12,12.01 20.73,6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                        Рассчитать режимы
                    </button>
                </div>

                <div class="calc-info">
                    <p>💡 <strong>Совет:</strong> Для точного расчета обязательно укажите материал заготовки, материал инструмента и маркировку пластины. Тип станка можно указать для проверки ограничений оборудования.</p>
                </div>
            </div>
        </form>

        <!-- Результаты -->
        @if(isset($result))
            <div class="calc-results">
                <div class="results-header">
                    <h3>Результаты профессионального расчета</h3>
                    <div class="results-subtitle">Оптимальные режимы токарной обработки</div>
                </div>

                <!-- Основные параметры -->
                <div class="results-section">
                    <h4 class="section-subtitle">Основные параметры</h4>
                    <div class="results-grid">
                        <div class="result-card">
                            <div class="result-label">Материал заготовки</div>
                            <div class="result-value">{{ $result['material']->name }}</div>
                            <div class="result-info">{{ $result['material']->material_group_name }}, {{ $result['material']->hardness_range }}</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Материал инструмента</div>
                            <div class="result-value">{{ $result['tool_material']->name }}</div>
                            <div class="result-info">{{ $result['tool_material']->material_type_name }}, {{ $result['tool_material']->grade }}</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Маркировка пластины</div>
                            <div class="result-value">{{ $result['tool_geometry']->name }}</div>
                            <div class="result-info">{{ $result['tool_geometry']->shape_name }}, {{ $result['tool_geometry']->clearance_angle }}°</div>
                        </div>

                        <div class="result-card {{ $result['used_default_machine_type'] ? 'info' : '' }}">
                            <div class="result-label">Тип станка</div>
                            <div class="result-value">{{ $result['machine_type']->name }}</div>
                            @if($result['used_default_machine_type'])
                                <div class="result-info">🔄 Использовано значение по умолчанию</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Геометрические параметры -->
                <div class="results-section">
                    <h4 class="section-subtitle">Геометрические параметры</h4>
                    <div class="results-grid">
                        <div class="result-card">
                            <div class="result-label">Диаметр исходный</div>
                            <div class="result-value">{{ $result['initial_diameter'] }} мм</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Диаметр получаемый</div>
                            <div class="result-value">{{ $result['final_diameter'] }} мм</div>
                        </div>

                        <div class="result-card highlight">
                            <div class="result-label">Глубина резания</div>
                            <div class="result-value">{{ $result['depth_of_cut'] }} мм</div>
                        </div>
                    </div>
                </div>

                <!-- Режимы резания -->
                <div class="results-section">
                    <h4 class="section-subtitle">Режимы резания</h4>
                    <div class="results-grid">
                        <div class="result-card {{ $result['is_rpm_valid'] ? 'success' : 'danger' }}">
                            <div class="result-label">Обороты шпинделя</div>
                            <div class="result-value">{{ $result['spindle_rpm'] }} об/мин</div>
                            @if(!$result['is_rpm_valid'])
                                <div class="result-warning">⚠ Превышение максимальных оборотов станка (макс: {{ $result['machine_type']->max_rpm }} об/мин)</div>
                            @endif
                        </div>

                        <div class="result-card">
                            <div class="result-label">Скорость резания</div>
                            <div class="result-value">{{ $result['cutting_speed'] }} м/мин</div>
                            <div class="result-info">Макс. для инструмента: {{ $result['tool_material']->max_cutting_speed }} м/мин</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Подача на оборот</div>
                            <div class="result-value">{{ $result['feed'] }} мм/об</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Минутная подача</div>
                            <div class="result-value">{{ $result['feed_rate'] }} мм/мин</div>
                        </div>
                    </div>
                </div>

                <!-- Мощность и энергетика -->
                <div class="results-section">
                    <h4 class="section-subtitle">Мощность и энергетика</h4>
                    <div class="results-grid">
                        <div class="result-card {{ $result['is_power_valid'] ? 'success' : 'danger' }}">
                            <div class="result-label">Мощность резания</div>
                            <div class="result-value">{{ $result['cutting_power'] }} кВт</div>
                            @if(!$result['is_power_valid'])
                                <div class="result-warning">⚠ Превышение мощности станка (диапазон: {{ $result['machine_type']->power_range }})</div>
                            @endif
                        </div>

                        <div class="result-card">
                            <div class="result-label">Удельная мощность</div>
                            <div class="result-value">{{ $result['specific_power'] }} кВт/см³</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Съем материала</div>
                            <div class="result-value">{{ $result['material_removal_rate'] }} см³/мин</div>
                        </div>
                    </div>
                </div>

                <!-- Параметры инструмента -->
                <div class="results-section">
                    <h4 class="section-subtitle">Параметры инструмента</h4>
                    <div class="results-grid">
                        <div class="result-card">
                            <div class="result-label">Форма пластины</div>
                            <div class="result-value">{{ $result['tool_geometry']->shape_name }}</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Задний угол</div>
                            <div class="result-value">{{ $result['tool_geometry']->clearance_angle }}°</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Класс точности</div>
                            <div class="result-value">{{ $result['tool_geometry']->tolerance_class_name }}</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Длина режущей кромки</div>
                            <div class="result-value">{{ $result['tool_geometry']->cutting_edge_length }} мм</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Толщина пластины</div>
                            <div class="result-value">{{ $result['tool_geometry']->insert_thickness }} мм</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Радиус скругления</div>
                            <div class="result-value">R{{ $result['tool_geometry']->corner_radius }}</div>
                        </div>
                    </div>
                </div>

                <!-- Дополнительная информация -->
                <div class="results-section">
                    <h4 class="section-subtitle">Дополнительная информация</h4>
                    <div class="results-grid">
                        <div class="result-card info">
                            <div class="result-label">Время обработки</div>
                            <div class="result-value">{{ $result['cutting_time'] }} мин</div>
                        </div>

                        <div class="result-card info">
                            <div class="result-label">Тип операции</div>
                            <div class="result-value">
                                {{ $result['operation_type'] == 'roughing' ? 'Черновая' : 'Чистовая' }}
                            </div>
                        </div>

                        <div class="result-card info">
                            <div class="result-label">Качество поверхности</div>
                            <div class="result-value">
                                @if($result['surface_quality'] == 'normal') Нормальное
                                @elseif($result['surface_quality'] == 'good') Хорошее
                                @else Отличное
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Статус расчета -->
                <div class="calculation-status">
                    @if($result['is_rpm_valid'] && $result['is_power_valid'])
                        <div class="status-success">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                            <div>
                                <strong>Расчет выполнен успешно!</strong>
                                <p>Все параметры находятся в допустимых пределах</p>
                            </div>
                        </div>
                    @else
                        <div class="status-warning">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                            <div>
                                <strong>Требуется корректировка параметров!</strong>
                                <p>Некоторые параметры превышают возможности оборудования</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const geometrySelect = document.getElementById('tool_geometry_id');
                const geometryDetails = document.getElementById('geometryDetails');

                // Функция для отображения деталей геометрии
                function updateGeometryDetails() {
                    const selectedOption = geometrySelect.options[geometrySelect.selectedIndex];

                    if (selectedOption.value && selectedOption.dataset.shape) {
                        document.getElementById('detailShape').textContent = getShapeName(selectedOption.dataset.shape);
                        document.getElementById('detailClearance').textContent = selectedOption.dataset.clearance + '°';
                        document.getElementById('detailTolerance').textContent = getToleranceName(selectedOption.dataset.tolerance);
                        document.getElementById('detailChipbreaker').textContent = selectedOption.dataset.chipbreaker || 'Нет';
                        document.getElementById('detailLength').textContent = selectedOption.dataset.length + ' мм';
                        document.getElementById('detailThickness').textContent = selectedOption.dataset.thickness + ' мм';
                        document.getElementById('detailRadius').textContent = 'R' + selectedOption.dataset.radius;

                        geometryDetails.style.display = 'block';
                    } else {
                        geometryDetails.style.display = 'none';
                    }
                }

                function getShapeName(shape) {
                    const shapes = {
                        'diamond': 'Ромб',
                        'square': 'Квадрат',
                        'circle': 'Круг',
                        'triangle': 'Треугольник'
                    };
                    return shapes[shape] || shape;
                }

                function getToleranceName(tolerance) {
                    const tolerances = {
                        'm': 'Средний (±0.05-0.08 мм)',
                        'g': 'Высокий (±0.025-0.05 мм)',
                        'u': 'Очень высокий (±0.013-0.025 мм)'
                    };
                    return tolerances[tolerance] || tolerance;
                }

                geometrySelect.addEventListener('change', updateGeometryDetails);

                // Инициализация при загрузке
                updateGeometryDetails();
            });
        </script>
    @endsection
@endsection
