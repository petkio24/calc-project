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
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
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

                    <!-- Материал заготовки -->
                    <div class="calc-input-group">
                        <label class="calc-input-group__label" for="material_id">Материал заготовки</label>
                        <div class="styled-select">
                            <select class="styled-select__input" id="material_id" name="material_id" required>
                                <option value="">Выберите материал заготовки</option>

                                <!-- Черные металлы -->
                                <optgroup label="🛠️ Черные металлы">
                                    @foreach($materials['black_metals'] ?? [] as $material)
                                        <option value="{{ $material->id }}"
                                                {{ old('material_id') == $material->id ? 'selected' : '' }}
                                                data-group="black_metals">
                                            {{ $material->name }} ({{ $material->hardness_range }})
                                        </option>
                                    @endforeach
                                </optgroup>

                                <!-- Цветные металлы -->
                                <optgroup label="🔶 Цветные металлы">
                                    @foreach($materials['nonferrous_metals'] ?? [] as $material)
                                        <option value="{{ $material->id }}"
                                                {{ old('material_id') == $material->id ? 'selected' : '' }}
                                                data-group="nonferrous_metals">
                                            {{ $material->name }} ({{ $material->hardness_range }})
                                        </option>
                                    @endforeach
                                </optgroup>

                                <!-- Неметаллы -->
                                <optgroup label="🧪 Неметаллы">
                                    @foreach($materials['non_metals'] ?? [] as $material)
                                        <option value="{{ $material->id }}"
                                                {{ old('material_id') == $material->id ? 'selected' : '' }}
                                                data-group="non_metals">
                                            {{ $material->name }} ({{ $material->hardness_range }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            </select>
                            <div class="styled-select__arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </div>
                        </div>
                        <div class="material-preview" id="materialPreview" style="display: none;">
                            <div class="material-preview__content">
                                <strong id="previewMaterialName"></strong>
                                <div class="material-preview__details">
                                    <span id="previewMaterialGroup"></span> •
                                    <span id="previewMaterialHardness"></span>
                                </div>
                                <div class="material-preview__speed">
                                    Скорость резания: <span id="previewMaterialSpeed"></span> м/мин
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="input-row">
                        <div class="calc-input-group">
                            <label class="calc-input-group__label" for="initial_diameter">Диаметр исходный (мм)</label>
                            <input class="calc-input-group__input" id="initial_diameter"
                                   name="initial_diameter" type="number" step="0.01" placeholder="Ø исходный"
                                   value="{{ old('initial_diameter', 50) }}" required>
                        </div>

                        <div class="calc-input-group">
                            <label class="calc-input-group__label" for="final_diameter">Диаметр получаемый (мм)</label>
                            <input class="calc-input-group__input" id="final_diameter"
                                   name="final_diameter" type="number" step="0.01" placeholder="Ø получаемый"
                                   value="{{ old('final_diameter', 45) }}" required>
                        </div>
                    </div>

                    <div class="input-row">
                        <div class="calc-input-group">
                            <label class="calc-input-group__label" for="cutting_length">Длина обработки (мм)</label>
                            <input class="calc-input-group__input" id="cutting_length"
                                   name="cutting_length" type="number" step="0.1" placeholder="Длина обработки"
                                   value="{{ old('cutting_length', 100) }}" required>
                        </div>

                        <div class="calc-input-group">
                            <label class="calc-input-group__label" for="allowance">Припуск на обработку (мм)</label>
                            <input class="calc-input-group__input" id="allowance"
                                   name="allowance" type="number" step="0.1" placeholder="Припуск"
                                   value="{{ old('allowance', 0) }}">
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
                        <label class="calc-input-group__label" for="tool_material_id">Материал инструмента</label>
                        <div class="styled-select">
                            <select class="styled-select__input" id="tool_material_id" name="tool_material_id" required>
                                <option value="">Выберите материал инструмента</option>

                                <!-- Твердые сплавы -->
                                <optgroup label="💎 Твердые сплавы">
                                    @foreach($toolMaterials['hard_alloy'] ?? [] as $toolMaterial)
                                        <option value="{{ $toolMaterial->id }}"
                                                {{ old('tool_material_id') == $toolMaterial->id ? 'selected' : '' }}
                                                data-type="hard_alloy">
                                            {{ $toolMaterial->name }} ({{ $toolMaterial->grade }})
                                        </option>
                                    @endforeach
                                </optgroup>

                                <!-- Быстрорежущие стали -->
                                <optgroup label="⚔️ Быстрорежущие стали">
                                    @foreach($toolMaterials['high_speed_steel'] ?? [] as $toolMaterial)
                                        <option value="{{ $toolMaterial->id }}"
                                                {{ old('tool_material_id') == $toolMaterial->id ? 'selected' : '' }}
                                                data-type="high_speed_steel">
                                            {{ $toolMaterial->name }} ({{ $toolMaterial->grade }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            </select>
                            <div class="styled-select__arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </div>
                        </div>
                        <div class="tool-material-preview" id="toolMaterialPreview" style="display: none;">
                            <div class="tool-material-preview__content">
                                <strong id="previewToolMaterialName"></strong>
                                <div class="tool-material-preview__details">
                                    <span id="previewToolMaterialType"></span> •
                                    Марка: <span id="previewToolMaterialGrade"></span>
                                </div>
                                <div class="tool-material-preview__speed">
                                    Макс. скорость: <span id="previewToolMaterialSpeed"></span> м/мин
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Маркировка инструмента -->
                    <div class="calc-input-group">
                        <label class="calc-input-group__label" for="tool_geometry_id">Маркировка пластины</label>
                        <div class="styled-select">
                            <select class="styled-select__input" id="tool_geometry_id" name="tool_geometry_id" required>
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
                                            data-radius="{{ $geometry->corner_radius }}"
                                            data-shape-name="{{ $geometry->shape_name }}"
                                            data-max-depth="{{ $geometry->max_depth_of_cut }}">
                                        {{ $geometry->name }} ({{ $geometry->shape_name }}, {{ $geometry->clearance_angle }}°, R{{ $geometry->corner_radius }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="styled-select__arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </div>
                        </div>
                        <div class="geometry-details" id="geometryDetails" style="display: none;">
                            <div class="geometry-details__header">
                                <h4>Характеристики пластины</h4>
                            </div>
                            <div class="geometry-details__grid">
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
                                <div class="geometry-detail">
                                    <span class="detail-label">Макс. глубина резания:</span>
                                    <span class="detail-value" id="detailMaxDepth"></span>
                                </div>
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
                        <div class="styled-select">
                            <select class="styled-select__input" id="machine_type_id" name="machine_type_id">
                                <option value="">Выберите тип станка (опционально)</option>
                                @foreach($machineTypes as $machine)
                                    <option value="{{ $machine->id }}"
                                        {{ old('machine_type_id') == $machine->id ? 'selected' : '' }}>
                                        {{ $machine->name }} ({{ $machine->power_range }}, до {{ $machine->max_rpm }} об/мин)
                                    </option>
                                @endforeach
                            </select>
                            <div class="styled-select__arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="calc-input-group">
                        <label class="calc-input-group__label" for="machine_age">
                            Срок службы станка (лет)
                            <span class="optional-hint">(для корректировки режимов)</span>
                        </label>
                        <div class="styled-select">
                            <select class="styled-select__input" id="machine_age" name="machine_age">
                                <option value="new" {{ old('machine_age') == 'new' ? 'selected' : '' }}>🟢 Новый (0-2 года)</option>
                                <option value="normal" {{ old('machine_age') == 'normal' ? 'selected' : '' }}>🟡 Нормальный (3-7 лет)</option>
                                <option value="worn" {{ old('machine_age') == 'worn' ? 'selected' : '' }}>🔴 Изношенный (8+ лет)</option>
                            </select>
                            <div class="styled-select__arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="input-row">
                        <div class="calc-input-group">
                            <label class="calc-input-group__label" for="operation_type">Тип операции</label>
                            <div class="styled-select">
                                <select class="styled-select__input" id="operation_type" name="operation_type">
                                    <option value="roughing" {{ old('operation_type') == 'roughing' ? 'selected' : '' }}>⚒️ Черновая обработка</option>
                                    <option value="finishing" {{ old('operation_type') == 'finishing' ? 'selected' : '' }}>✨ Чистовая обработка</option>
                                </select>
                                <div class="styled-select__arrow">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="calc-input-group">
                            <label class="calc-input-group__label" for="operation_subtype">Вид обработки</label>
                            <div class="styled-select">
                                <select class="styled-select__input" id="operation_subtype" name="operation_subtype">
                                    <option value="external_turning" {{ old('operation_subtype') == 'external_turning' ? 'selected' : '' }}>🔄 Наружное точение</option>
                                    <option value="internal_turning" {{ old('operation_subtype') == 'internal_turning' ? 'selected' : '' }}>🕳️ Растачивание (внутреннее)</option>
                                </select>
                                <div class="styled-select__arrow">▶</div>
                            </div>
                        </div>
                        <div class="calc-input-group">
                            <label class="calc-input-group__label" for="surface_quality">Качество поверхности</label>
                            <div class="styled-select">
                                <select class="styled-select__input" id="surface_quality" name="surface_quality">
                                    <option value="normal" {{ old('surface_quality') == 'normal' ? 'selected' : '' }}>🟡 Нормальное</option>
                                    <option value="good" {{ old('surface_quality') == 'good' ? 'selected' : '' }}>🟢 Хорошее</option>
                                    <option value="excellent" {{ old('surface_quality') == 'excellent' ? 'selected' : '' }}>🔴 Отличное</option>
                                </select>
                                <div class="styled-select__arrow">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </div>
                            </div>
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

                        <div class="result-card">
                            <div class="result-label">Средний диаметр</div>
                            <div class="result-value">{{ $result['average_diameter'] }} мм</div>
                        </div>

                        <div class="result-card highlight">
                            <div class="result-label">Глубина резания</div>
                            <div class="result-value">{{ $result['depth_of_cut'] }} мм</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Глубина на проход</div>
                            <div class="result-value">{{ $result['depth_per_pass'] }} мм</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Количество проходов</div>
                            <div class="result-value">{{ $result['number_of_passes'] }}</div>
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
                            <div class="result-value">{{ $result['feed_per_revolution'] }} мм/об</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Минутная подача</div>
                            <div class="result-value">{{ $result['feed_rate'] }} мм/мин</div>
                        </div>
                    </div>
                </div>

                <!-- Мощность и силовые параметры -->
                <div class="results-section">
                    <h4 class="section-subtitle">Мощность и силовые параметры</h4>
                    <div class="results-grid">
                        <div class="result-card {{ $result['is_power_valid'] ? 'success' : 'danger' }}">
                            <div class="result-label">Мощность резания</div>
                            <div class="result-value">{{ $result['cutting_power'] }} кВт</div>
                            @if(!$result['is_power_valid'])
                                <div class="result-warning">⚠ Превышение мощности станка (макс: {{ $result['machine_type']->max_power_kw }} кВт)</div>
                            @endif
                        </div>

                        <div class="result-card">
                            <div class="result-label">Эффективная мощность</div>
                            <div class="result-value">{{ $result['effective_power'] }} кВт</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Усилие резания</div>
                            <div class="result-value">{{ $result['cutting_force'] }} Н</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Крутящий момент</div>
                            <div class="result-value">{{ $result['torque'] }} Н·м</div>
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

                        <div class="result-card {{ $result['is_depth_valid'] ? 'success' : 'danger' }}">
                            <div class="result-label">Макс. глубина резания</div>
                            <div class="result-value">{{ $result['tool_geometry']->max_depth_of_cut }} мм</div>
                            @if(!$result['is_depth_valid'])
                                <div class="result-warning">⚠ Превышение максимальной глубины резания для инструмента</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Время обработки -->
                <div class="results-section">
                    <h4 class="section-subtitle">Время обработки</h4>
                    <div class="results-grid">
                        <div class="result-card">
                            <div class="result-label">Длина обработки</div>
                            <div class="result-value">{{ $result['cutting_length'] }} мм</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Время на проход</div>
                            <div class="result-value">{{ $result['cutting_time_per_pass'] }} мин</div>
                        </div>

                        <div class="result-card highlight">
                            <div class="result-label">Общее время обработки</div>
                            <div class="result-value">{{ $result['total_cutting_time'] }} мин</div>
                        </div>
                    </div>
                </div>

                <!-- Дополнительная информация -->
                <div class="results-section">
                    <h4 class="section-subtitle">Дополнительная информация</h4>
                    <div class="results-grid">
                        <div class="result-card info">
                            <div class="result-label">Тип операции</div>
                            <div class="result-value">
                                {{ $result['operation_type'] == 'roughing' ? '⚒️ Черновая' : '✨ Чистовая' }}
                            </div>
                        </div>

                        <div class="result-card info">
                            <div class="result-label">Качество поверхности</div>
                            <div class="result-value">
                                @if($result['surface_quality'] == 'normal') 🟡 Нормальное
                                @elseif($result['surface_quality'] == 'good') 🟢 Хорошее
                                @else 🔴 Отличное
                                @endif
                            </div>
                        </div>
                        <div class="result-card info">
                            <div class="result-label">Вид обработки</div>
                            <div class="result-value">{{ $result['operation_subtype_name'] }}</div>
                        </div>
                        <div class="result-card {{ $result['machine_condition'] == 'worn' ? 'danger' : 'info' }}">
                            <div class="result-label">Состояние станка</div>
                            <div class="result-value">
                                @if($result['machine_condition'] == 'new') 🟢 Новый
                                @elseif($result['machine_condition'] == 'normal') 🟡 Нормальное
                                @else 🔴 Изношенный
                                @endif
                            </div>
                            <div class="result-info">Срок службы: {{ $result['years_in_service'] }} лет</div>
                        </div>
                        <div class="result-card {{ $result['is_feed_radius_compatible'] ? 'success' : 'danger' }}">
                            <div class="result-label">Соответствие радиуса и подачи</div>
                            <div class="result-value">
                                {{ $result['is_feed_radius_compatible'] ? '✅ Совместимо' : '⚠️ Несовместимо' }}
                            </div>
                            @if(!$result['is_feed_radius_compatible'])
                                <div class="result-warning">
                                    Рекомендуемый радиус: R{{ $result['recommended_radius'] }} (текущий: R{{ $result['tool_geometry']->corner_radius }})
                                </div>
                            @endif
                        </div>
                        <div class="result-card info">
                            <div class="result-label">Корректировка режимов</div>
                            <div class="result-value">-{{ $result['condition_reduction_percent'] }}%</div>
                            <div class="result-info">Учтено снижение производительности</div>
                        </div>
                    </div>
                </div>

                <!-- Статус расчета -->
                <div class="calculation-status">
                    @if($result['is_rpm_valid'] && $result['is_power_valid'] && $result['is_depth_valid'])
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
                                <p>Некоторые параметры превышают возможности оборудования или инструмента</p>
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
                // Элементы DOM
                const materialSelect = document.getElementById('material_id');
                const materialPreview = document.getElementById('materialPreview');
                const toolMaterialSelect = document.getElementById('tool_material_id');
                const toolMaterialPreview = document.getElementById('toolMaterialPreview');
                const geometrySelect = document.getElementById('tool_geometry_id');
                const geometryDetails = document.getElementById('geometryDetails');

                // Данные материалов (в реальном приложении можно загрузить через AJAX)
                const materialsData = {
                    @foreach($materials->flatten() as $material)
                        {{ $material->id }}: {
                        name: "{{ $material->name }}",
                        group: "{{ $material->material_group_name }}",
                        hardness: "{{ $material->hardness_range }}",
                        speed: "{{ $material->cutting_speed_min }}-{{ $material->cutting_speed_max }}"
                    },
                    @endforeach
                };

                // Данные инструментальных материалов
                const toolMaterialsData = {
                    @foreach($toolMaterials->flatten() as $toolMaterial)
                        {{ $toolMaterial->id }}: {
                        name: "{{ $toolMaterial->name }}",
                        type: "{{ $toolMaterial->material_type_name }}",
                        grade: "{{ $toolMaterial->grade }}",
                        speed: "{{ $toolMaterial->max_cutting_speed }}"
                    },
                    @endforeach
                };

                // Функции для обновления превью
                function updateMaterialPreview() {
                    const selectedId = materialSelect.value;
                    if (selectedId && materialsData[selectedId]) {
                        const material = materialsData[selectedId];
                        document.getElementById('previewMaterialName').textContent = material.name;
                        document.getElementById('previewMaterialGroup').textContent = material.group;
                        document.getElementById('previewMaterialHardness').textContent = material.hardness;
                        document.getElementById('previewMaterialSpeed').textContent = material.speed;
                        materialPreview.style.display = 'block';
                    } else {
                        materialPreview.style.display = 'none';
                    }
                }

                function updateToolMaterialPreview() {
                    const selectedId = toolMaterialSelect.value;
                    if (selectedId && toolMaterialsData[selectedId]) {
                        const toolMaterial = toolMaterialsData[selectedId];
                        document.getElementById('previewToolMaterialName').textContent = toolMaterial.name;
                        document.getElementById('previewToolMaterialType').textContent = toolMaterial.type;
                        document.getElementById('previewToolMaterialGrade').textContent = toolMaterial.grade;
                        document.getElementById('previewToolMaterialSpeed').textContent = toolMaterial.speed;
                        toolMaterialPreview.style.display = 'block';
                    } else {
                        toolMaterialPreview.style.display = 'none';
                    }
                }

                function updateGeometryDetails() {
                    const selectedOption = geometrySelect.options[geometrySelect.selectedIndex];

                    if (selectedOption.value && selectedOption.dataset.shape) {
                        document.getElementById('detailShape').textContent = selectedOption.dataset.shapeName;
                        document.getElementById('detailClearance').textContent = selectedOption.dataset.clearance + '°';
                        document.getElementById('detailTolerance').textContent = getToleranceName(selectedOption.dataset.tolerance);
                        document.getElementById('detailChipbreaker').textContent = selectedOption.dataset.chipbreaker || 'Нет';
                        document.getElementById('detailLength').textContent = selectedOption.dataset.length + ' мм';
                        document.getElementById('detailThickness').textContent = selectedOption.dataset.thickness + ' мм';
                        document.getElementById('detailRadius').textContent = 'R' + selectedOption.dataset.radius;
                        document.getElementById('detailMaxDepth').textContent = selectedOption.dataset.maxDepth + ' мм';

                        geometryDetails.style.display = 'block';
                    } else {
                        geometryDetails.style.display = 'none';
                    }
                }

                function getToleranceName(tolerance) {
                    const tolerances = {
                        'm': 'Средний (±0.05-0.08 мм)',
                        'g': 'Высокий (±0.025-0.05 мм)',
                        'u': 'Очень высокий (±0.013-0.025 мм)'
                    };
                    return tolerances[tolerance] || tolerance;
                }

                // Слушатели событий
                materialSelect.addEventListener('change', updateMaterialPreview);
                toolMaterialSelect.addEventListener('change', updateToolMaterialPreview);
                geometrySelect.addEventListener('change', updateGeometryDetails);

                // Инициализация при загрузке
                updateMaterialPreview();
                updateToolMaterialPreview();
                updateGeometryDetails();

                // Добавляем классы для стилизации optgroup
                const style = document.createElement('style');
                style.textContent = `
                    .styled-select__input optgroup {
                        font-weight: bold;
                        color: #333;
                    }
                    .styled-select__input option {
                        padding: 8px 12px;
                    }
                `;
                document.head.appendChild(style);
            });
        </script>
    @endsection
@endsection
