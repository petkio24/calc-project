@extends('layouts.app')

@section('title', 'Профессиональный калькулятор сверления')

@section('styles')
    <link href="{{ asset('css/calculator_drilling.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="calculator-container">
        <!-- Хлебные крошки -->
        <nav class="calc-breadcrumbs">
            <a href="{{ route('home') }}" class="calc-breadcrumbs__item">Главная</a>
            <span class="calc-breadcrumbs__separator">›</span>
            <span class="calc-breadcrumbs__item active">Профессиональный калькулятор сверления</span>
        </nav>

        <!-- Заголовок -->
        <div class="calc-header">
            <h1>Профессиональный расчет режимов сверления</h1>
            <h2>Точные параметры для оптимальной сверлильной обработки</h2>
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
        <form method="POST" action="{{ route('calculators.drilling.calculate') }}">
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

                                <!-- Конструкционные стали -->
                                <optgroup label="⚙️ Конструкционные стали">
                                    @foreach($materials['carbon_steel'] ?? [] as $material)
                                        <option value="{{ $material->id }}"
                                                {{ old('material_id') == $material->id ? 'selected' : '' }}
                                                data-group="carbon_steel">
                                            {{ $material->name }} ({{ $material->hardness_range }})
                                        </option>
                                    @endforeach
                                </optgroup>

                                <!-- Легированные стали -->
                                <optgroup label="🔩 Легированные стали">
                                    @foreach($materials['alloy_steel'] ?? [] as $material)
                                        <option value="{{ $material->id }}"
                                                {{ old('material_id') == $material->id ? 'selected' : '' }}
                                                data-group="alloy_steel">
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

                                <!-- Алюминиевые сплавы -->
                                <optgroup label="📦 Алюминиевые сплавы">
                                    @foreach($materials['aluminum'] ?? [] as $material)
                                        <option value="{{ $material->id }}"
                                                {{ old('material_id') == $material->id ? 'selected' : '' }}
                                                data-group="aluminum">
                                            {{ $material->name }} ({{ $material->hardness_range }})
                                        </option>
                                    @endforeach
                                </optgroup>

                                <!-- Медные сплавы -->
                                <optgroup label="🔰 Медные сплавы">
                                    @foreach($materials['copper_alloy'] ?? [] as $material)
                                        <option value="{{ $material->id }}"
                                                {{ old('material_id') == $material->id ? 'selected' : '' }}
                                                data-group="copper_alloy">
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

                                <!-- Пластмассы -->
                                <optgroup label="🧩 Пластмассы">
                                    @foreach($materials['plastics'] ?? [] as $material)
                                        <option value="{{ $material->id }}"
                                                {{ old('material_id') == $material->id ? 'selected' : '' }}
                                                data-group="plastics">
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
                            <label class="calc-input-group__label" for="diameter">Диаметр сверла (мм)</label>
                            <input class="calc-input-group__input" id="diameter"
                                   name="diameter" type="number" step="0.01" min="0.1" max="100"
                                   placeholder="Ø сверла" value="{{ old('diameter', 10) }}" required>
                        </div>

                        <div class="calc-input-group">
                            <label class="calc-input-group__label" for="hole_depth">Глубина отверстия (мм)</label>
                            <input class="calc-input-group__input" id="hole_depth"
                                   name="hole_depth" type="number" step="0.1" min="1"
                                   placeholder="Глубина" value="{{ old('hole_depth', 20) }}" required>
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

                    <!-- Тип сверла -->
                    <div class="calc-input-group">
                        <label class="calc-input-group__label" for="tool_id">Тип сверла</label>
                        <div class="styled-select">
                            <select class="styled-select__input" id="tool_id" name="tool_id" required>
                                <option value="">Выберите тип сверла</option>
                                @foreach($tools as $tool)
                                    <option value="{{ $tool->id }}"
                                        {{ old('tool_id') == $tool->id ? 'selected' : '' }}>
                                        {{ $tool->name }} ({{ $tool->tool_type_name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Дополнительные параметры -->
                    <div class="input-row">
                        <div class="calc-input-group">
                            <label class="calc-input-group__label" for="operation_type">Тип операции</label>
                            <div class="styled-select">
                                <select class="styled-select__input" id="operation_type" name="operation_type">
                                    <option value="roughing" {{ old('operation_type') == 'roughing' ? 'selected' : '' }}>⚒️ Черновая обработка</option>
                                    <option value="finishing" {{ old('operation_type') == 'finishing' ? 'selected' : '' }}>✨ Чистовая обработка</option>
                                </select>
                            </div>
                        </div>

                        <div class="calc-input-group">
                            <label class="calc-input-group__label" for="coolant_used">Охлаждение</label>
                            <div class="styled-select">
                                <select class="styled-select__input" id="coolant_used" name="coolant_used">
                                    <option value="1" {{ old('coolant_used', true) ? 'selected' : '' }}>💧 С охлаждением</option>
                                    <option value="0" {{ !old('coolant_used', true) ? 'selected' : '' }}>🌵 Без охлаждения</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Параметры станка -->
                <div class="calc-section">
                    <h3 class="section-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                            <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                            <line x1="6" y1="6" x2="6" y2="18"></line>
                        </svg>
                        Параметры станка
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
                        </div>
                    </div>

                    <!-- Состояние станка -->
                    <div class="calc-input-group">
                        <label class="calc-input-group__label" for="machine_age">
                            Состояние станка
                            <span class="optional-hint">(для корректировки режимов)</span>
                        </label>
                        <div class="styled-select">
                            <select class="styled-select__input" id="machine_age" name="machine_age">
                                <option value="new" {{ old('machine_age') == 'new' ? 'selected' : '' }}>🟢 Новый (0-2 года)</option>
                                <option value="normal" {{ old('machine_age', 'normal') == 'normal' ? 'selected' : '' }}>🟡 Нормальный (3-7 лет)</option>
                                <option value="worn" {{ old('machine_age') == 'worn' ? 'selected' : '' }}>🔴 Изношенный (8+ лет)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Кнопка расчета -->
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
                        Рассчитать режимы сверления
                    </button>
                </div>
            </div>
        </form>

        <!-- Результаты -->
        @if(isset($result))
            <div class="calc-results">
                <div class="results-header">
                    <h3>Результаты профессионального расчета</h3>
                    <div class="results-subtitle">Оптимальные режимы сверлильной обработки</div>
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
                            <div class="result-label">Тип сверла</div>
                            <div class="result-value">{{ $result['tool']->name }}</div>
                            <div class="result-info">{{ $result['tool']->tool_type_name }}, {{ $result['tool']->material_type_name }}</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Диаметр сверла</div>
                            <div class="result-value">{{ $result['diameter'] }} мм</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Глубина отверстия</div>
                            <div class="result-value">{{ $result['hole_depth'] }} мм</div>
                        </div>

                        <div class="result-card {{ $result['used_default_machine_type'] ? 'info' : '' }}">
                            <div class="result-label">Тип станка</div>
                            <div class="result-value">{{ $result['machine_type'] }}</div>
                            @if($result['used_default_machine_type'])
                                <div class="result-info">🔄 Использовано значение по умолчанию</div>
                            @endif
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
                    </div>
                </div>

                <!-- Режимы резания -->
                <div class="results-section">
                    <h4 class="section-subtitle">Режимы резания</h4>
                    <div class="results-grid">
                        <div class="result-card {{ $result['is_rpm_valid'] ? 'success' : 'danger' }}">
                            <div class="result-label">Обороты шпинделя</div>
                            <div class="result-value">{{ $result['spindle_rpm'] }} об/мин</div>
                            <div class="result-formula">
                                n = (1000 × V) ÷ (π × D) = (1000 × {{ $result['cutting_speed'] }}) ÷ (3.1416 × {{ $result['diameter'] }})
                            </div>
                            @if(!$result['is_rpm_valid'])
                                <div class="result-warning">⚠ Превышение максимальных оборотов станка</div>
                            @endif
                        </div>

                        <div class="result-card">
                            <div class="result-label">Скорость резания</div>
                            <div class="result-value">{{ $result['cutting_speed'] }} м/мин</div>
                            <div class="result-formula">
                                V = (π × D × n) ÷ 1000 = (3.1416 × {{ $result['diameter'] }} × {{ $result['spindle_rpm'] }}) ÷ 1000
                            </div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Подача на оборот</div>
                            <div class="result-value">{{ $result['feed_per_revolution'] }} мм/об</div>
                            <div class="result-formula">
                                S = f(материал, диаметр, операция) = {{ $result['feed_per_revolution'] }} мм/об
                            </div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Минутная подача</div>
                            <div class="result-value">{{ $result['feed_rate'] }} мм/мин</div>
                            <div class="result-formula">
                                F = S × n = {{ $result['feed_per_revolution'] }} × {{ $result['spindle_rpm'] }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Силовые параметры -->
                <div class="results-section">
                    <h4 class="section-subtitle">Силовые параметры</h4>
                    <div class="results-grid">
                        <div class="result-card">
                            <div class="result-label">Осевое усилие</div>
                            <div class="result-value">{{ $result['thrust_force'] }} Н</div>
                            <div class="result-formula">
                                P = K × D × S<sup>0.8</sup> = {{ $result['material']->specific_pressure }} × {{ $result['diameter'] }} × {{ $result['feed_per_revolution'] }}<sup>0.8</sup>
                            </div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Крутящий момент</div>
                            <div class="result-value">{{ $result['torque'] }} Н·м</div>
                            <div class="result-formula">
                                M = C × D² × S<sup>0.8</sup> = ({{ $result['material']->specific_pressure }} ÷ 200) × {{ $result['diameter'] }}² × {{ $result['feed_per_revolution'] }}<sup>0.8</sup>
                            </div>
                        </div>

                        <div class="result-card {{ $result['is_power_valid'] ? 'success' : 'danger' }}">
                            <div class="result-label">Мощность резания</div>
                            <div class="result-value">{{ $result['cutting_power'] }} кВт</div>
                            <div class="result-formula">
                                P = (M × n) ÷ 9550 = ({{ $result['torque'] }} × {{ $result['spindle_rpm'] }}) ÷ 9550
                            </div>
                            @if(!$result['is_power_valid'])
                                <div class="result-warning">⚠ Превышение мощности станка</div>
                            @endif
                        </div>

                        <div class="result-card">
                            <div class="result-label">Эффективная мощность</div>
                            <div class="result-value">{{ $result['effective_power'] }} кВт</div>
                            <div class="result-formula">
                                P<sub>эфф</sub> = P ÷ η = {{ $result['cutting_power'] }} ÷ {{ $result['machine_type_obj']->efficiency ?? 0.85 }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Производительность -->
                <div class="results-section">
                    <h4 class="section-subtitle">Производительность</h4>
                    <div class="results-grid">
                        <div class="result-card">
                            <div class="result-label">Время обработки отверстия</div>
                            <div class="result-value">{{ $result['cutting_time_per_hole'] }} мин</div>
                            <div class="result-formula">
                                T = L ÷ F × 1.1 = {{ $result['hole_depth'] }} ÷ {{ $result['feed_rate'] }} × 1.1
                            </div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Съем материала</div>
                            <div class="result-value">{{ $result['material_removal_rate'] }} см³/мин</div>
                            <div class="result-formula">
                                Q = (π × D² × F) ÷ 4000 = (3.1416 × {{ $result['diameter'] }}² × {{ $result['feed_rate'] }}) ÷ 4000
                            </div>
                        </div>

                        <div class="result-card info">
                            <div class="result-label">Корректировка режимов</div>
                            <div class="result-value">-{{ $result['condition_reduction_percent'] }}%</div>
                            <div class="result-formula">
                                Коэффициент износа: {{ $result['condition_factor'] }} ({{ $result['years_in_service'] }} лет)
                            </div>
                            <div class="result-info">Учтено снижение производительности</div>
                        </div>
                    </div>
                </div>

                <!-- Статус расчета -->
                <div class="calculation-status">
                    @if($result['is_calculations_valid'])
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
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Валидация диаметра
            const diameterInput = document.getElementById('diameter');
            if (diameterInput) {
                diameterInput.addEventListener('change', function() {
                    const value = parseFloat(this.value);
                    if (value < 0.1) {
                        this.value = 0.1;
                    } else if (value > 100) {
                        this.value = 100;
                    }
                });
            }

            // Валидация глубины
            const depthInput = document.getElementById('hole_depth');
            if (depthInput) {
                depthInput.addEventListener('change', function() {
                    const value = parseFloat(this.value);
                    if (value < 1) {
                        this.value = 1;
                    }
                });
            }

            // Превью материала (как в точении)
            const materialSelect = document.getElementById('material_id');
            const materialPreview = document.getElementById('materialPreview');

            // Данные материалов
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

            materialSelect.addEventListener('change', updateMaterialPreview);
            updateMaterialPreview(); // Инициализация

            // Стилизация optgroup
            const style = document.createElement('style');
            style.textContent = `
                .styled-select__input optgroup {
                    font-weight: bold;
                    color: #333;
                    background-color: #f8f9fa;
                }
                .styled-select__input option {
                    padding: 8px 12px;
                }
                .material-preview {
                    margin-top: 8px;
                    padding: 12px;
                    background: #f8f9fa;
                    border-radius: 6px;
                    border-left: 4px solid #007bff;
                }
                .material-preview__content strong {
                    color: #333;
                }
                .material-preview__details {
                    font-size: 0.9em;
                    color: #666;
                    margin: 4px 0;
                }
                .material-preview__speed {
                    font-size: 0.9em;
                    color: #007bff;
                    font-weight: 500;
                }
            `;
            document.head.appendChild(style);
        });
    </script>
@endsection
