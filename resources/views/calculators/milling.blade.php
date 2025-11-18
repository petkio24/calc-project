@extends('layouts.app')

@section('title', 'Профессиональный калькулятор фрезерования')

@section('content')
    <div class="calculator-container">
        <!-- Хлебные крошки -->
        <nav class="calc-breadcrumbs">
            <a href="{{ route('home') }}" class="calc-breadcrumbs__item">Главная</a>
            <span class="calc-breadcrumbs__separator">›</span>
            <span class="calc-breadcrumbs__item active">Профессиональный калькулятор фрезерования</span>
        </nav>

        <!-- Заголовок -->
        <div class="calc-header">
            <h1>Профессиональный расчет режимов фрезерования</h1>
            <h2>Точные параметры для оптимальной фрезерной обработки</h2>
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
        <form method="POST" action="{{ route('calculators.milling.calculate') }}">
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

                                <!-- Нержавеющие стали -->
                                <optgroup label="🔶 Нержавеющие стали">
                                    @foreach($materials['stainless_steel'] ?? [] as $material)
                                        <option value="{{ $material->id }}"
                                                {{ old('material_id') == $material->id ? 'selected' : '' }}
                                                data-group="stainless_steel">
                                            {{ $material->name }} ({{ $material->hardness_range }})
                                        </option>
                                    @endforeach
                                </optgroup>

                                <!-- Чугуны -->
                                <optgroup label="🛠️ Чугуны">
                                    @foreach($materials['cast_iron'] ?? [] as $material)
                                        <option value="{{ $material->id }}"
                                                {{ old('material_id') == $material->id ? 'selected' : '' }}
                                                data-group="cast_iron">
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

                    <!-- Тип операции -->
                    <div class="calc-input-group">
                        <label class="calc-input-group__label" for="operation_type">Тип операции</label>
                        <div class="styled-select">
                            <select class="styled-select__input" id="operation_type" name="operation_type" required>
                                <option value="roughing" {{ old('operation_type') == 'roughing' ? 'selected' : '' }}>⚒️ Черновая обработка</option>
                                <option value="semi_finishing" {{ old('operation_type', 'semi_finishing') == 'semi_finishing' ? 'selected' : '' }}>🔧 Получистовая обработка</option>
                                <option value="finishing" {{ old('operation_type') == 'finishing' ? 'selected' : '' }}>✨ Чистовая обработка</option>
                            </select>
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

                    <!-- Тип фрезы -->
                    <div class="calc-input-group">
                        <label class="calc-input-group__label" for="tool_id">Тип фрезы</label>
                        <div class="styled-select">
                            <select class="styled-select__input" id="tool_id" name="tool_id" required>
                                <option value="">Выберите тип фрезы</option>
                                @foreach($tools as $tool)
                                    <option value="{{ $tool->id }}"
                                            {{ old('tool_id') == $tool->id ? 'selected' : '' }}
                                            data-teeth-min="{{ $tool->number_of_teeth_min }}"
                                            data-teeth-max="{{ $tool->number_of_teeth_max }}">
                                        {{ $tool->name }} ({{ $tool->tool_type_name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="tool-info" id="toolInfo" style="display: none;">
                            <div class="tool-info__content">
                                <span id="toolTeethRange"></span> •
                                <span id="toolMaterial"></span> •
                                Макс. скорость: <span id="toolMaxSpeed"></span> м/мин
                            </div>
                        </div>
                    </div>

                    <div class="input-row">
                        <div class="calc-input-group">
                            <label class="calc-input-group__label" for="cutter_diameter">Диаметр фрезы (мм)</label>
                            <input class="calc-input-group__input" id="cutter_diameter"
                                   name="cutter_diameter" type="number" step="0.1" min="0.1" max="200"
                                   placeholder="Ø фрезы" value="{{ old('cutter_diameter', 10) }}" required>
                        </div>

                        <div class="calc-input-group">
                            <label class="calc-input-group__label" for="number_of_teeth">Количество зубьев</label>
                            <input class="calc-input-group__input" id="number_of_teeth"
                                   name="number_of_teeth" type="number" step="1" min="1" max="20"
                                   placeholder="Количество зубьев" value="{{ old('number_of_teeth', 4) }}" required>
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
                    </h3>

                    <div class="input-row">
                        <div class="calc-input-group">
                            <label class="calc-input-group__label" for="width_of_cut">Ширина фрезерования (мм)</label>
                            <input class="calc-input-group__input" id="width_of_cut"
                                   name="width_of_cut" type="number" step="0.1" min="0.1"
                                   placeholder="Ширина" value="{{ old('width_of_cut', 5) }}" required>
                            <div class="input-hint" id="widthHint"></div>
                        </div>

                        <div class="calc-input-group">
                            <label class="calc-input-group__label" for="depth_of_cut">Глубина резания (мм)</label>
                            <input class="calc-input-group__input" id="depth_of_cut"
                                   name="depth_of_cut" type="number" step="0.1" min="0.1"
                                   placeholder="Глубина" value="{{ old('depth_of_cut', 2) }}" required>
                            <div class="input-hint" id="depthHint"></div>
                        </div>
                    </div>

                    <!-- Охлаждение -->
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
                        Рассчитать режимы фрезерования
                    </button>
                </div>

                <div class="calc-info">
                    <p>💡 <strong>Совет:</strong> Для точного расчета обязательно укажите материал заготовки, тип фрезы и параметры обработки. Рекомендуемая ширина фрезерования: 40-70% от диаметра фрезы для черновой обработки.</p>
                </div>
            </div>
        </form>

        <!-- Результаты -->
        @if(isset($result))
            <div class="calc-results">
                <div class="results-header">
                    <h3>Результаты профессионального расчета</h3>
                    <div class="results-subtitle">Оптимальные режимы фрезерной обработки</div>
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
                            <div class="result-label">Тип фрезы</div>
                            <div class="result-value">{{ $result['tool']->name }}</div>
                            <div class="result-info">{{ $result['tool']->tool_type_name }}, {{ $result['tool']->material_type_name }}</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Тип операции</div>
                            <div class="result-value">{{ $result['operation_type_name'] }}</div>
                        </div>

                        <div class="result-card {{ $result['used_default_machine_type'] ? 'info' : '' }}">
                            <div class="result-label">Тип станка</div>
                            <div class="result-value">{{ $result['machine_type']->name }}</div>
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

                <!-- Геометрические параметры -->
                <div class="results-section">
                    <h4 class="section-subtitle">Геометрические параметры</h4>
                    <div class="results-grid">
                        <div class="result-card">
                            <div class="result-label">Диаметр фрезы</div>
                            <div class="result-value">{{ $result['cutter_diameter'] }} мм</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Количество зубьев</div>
                            <div class="result-value">{{ $result['number_of_teeth'] }}</div>
                        </div>

                        <div class="result-card {{ $result['is_width_valid'] ? 'success' : 'danger' }}">
                            <div class="result-label">Ширина фрезерования</div>
                            <div class="result-value">{{ $result['width_of_cut'] }} мм</div>
                            @if(!$result['is_width_valid'])
                                <div class="result-warning">⚠ Превышение рекомендуемой ширины (макс: {{ $result['max_recommended_width'] }} мм)</div>
                            @endif
                        </div>

                        <div class="result-card {{ $result['is_depth_valid'] ? 'success' : 'danger' }}">
                            <div class="result-label">Глубина резания</div>
                            <div class="result-value">{{ $result['depth_of_cut'] }} мм</div>
                            @if(!$result['is_depth_valid'])
                                <div class="result-warning">⚠ Превышение рекомендуемой глубины (макс: {{ $result['max_recommended_depth'] }} мм)</div>
                            @endif
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
                                n = (1000 × V) ÷ (π × D) = (1000 × {{ $result['cutting_speed'] }}) ÷ (3.1416 × {{ $result['cutter_diameter'] }})
                            </div>
                            @if(!$result['is_rpm_valid'])
                                <div class="result-warning">⚠ Превышение максимальных оборотов станка</div>
                            @endif
                        </div>

                        <div class="result-card">
                            <div class="result-label">Скорость резания</div>
                            <div class="result-value">{{ $result['cutting_speed'] }} м/мин</div>
                            <div class="result-formula">
                                V = (π × D × n) ÷ 1000 = (3.1416 × {{ $result['cutter_diameter'] }} × {{ $result['spindle_rpm'] }}) ÷ 1000
                            </div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Подача на зуб</div>
                            <div class="result-value">{{ $result['feed_per_tooth'] }} мм/зуб</div>
                            <div class="result-formula">
                                S<sub>z</sub> = f(материал, операция, геометрия) = {{ $result['feed_per_tooth'] }} мм/зуб
                            </div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Подача на оборот</div>
                            <div class="result-value">{{ $result['feed_per_revolution'] }} мм/об</div>
                            <div class="result-formula">
                                S<sub>o</sub> = S<sub>z</sub> × z = {{ $result['feed_per_tooth'] }} × {{ $result['number_of_teeth'] }}
                            </div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Минутная подача</div>
                            <div class="result-value">{{ $result['feed_rate'] }} мм/мин</div>
                            <div class="result-formula">
                                S<sub>m</sub> = S<sub>z</sub> × z × n = {{ $result['feed_per_tooth'] }} × {{ $result['number_of_teeth'] }} × {{ $result['spindle_rpm'] }}
                            </div>
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
                            <div class="result-formula">
                                P = (a<sub>e</sub> × a<sub>p</sub> × S<sub>m</sub> × K) ÷ 60000
                            </div>
                            @if(!$result['is_power_valid'])
                                <div class="result-warning">⚠ Превышение мощности станка</div>
                            @endif
                        </div>

                        <div class="result-card">
                            <div class="result-label">Эффективная мощность</div>
                            <div class="result-value">{{ $result['effective_power'] }} кВт</div>
                            <div class="result-formula">
                                P<sub>эфф</sub> = P ÷ η = {{ $result['cutting_power'] }} ÷ {{ $result['machine_type']->efficiency ?? 0.85 }}
                            </div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Усилие резания</div>
                            <div class="result-value">{{ $result['cutting_force'] }} Н</div>
                            <div class="result-formula">
                                F = K<sub>s</sub> × a<sub>e</sub> × a<sub>p</sub> × S<sub>z</sub>
                            </div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Крутящий момент</div>
                            <div class="result-value">{{ $result['torque'] }} Н·м</div>
                            <div class="result-formula">
                                M = F × D ÷ 2000 = {{ $result['cutting_force'] }} × {{ $result['cutter_diameter'] }} ÷ 2000
                            </div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Съем материала</div>
                            <div class="result-value">{{ $result['material_removal_rate'] }} см³/мин</div>
                            <div class="result-formula">
                                Q = a<sub>e</sub> × a<sub>p</sub> × S<sub>m</sub> ÷ 1000
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Производительность -->
                <div class="results-section">
                    <h4 class="section-subtitle">Производительность</h4>
                    <div class="results-grid">
                        <div class="result-card">
                            <div class="result-label">Время обработки (100 мм)</div>
                            <div class="result-value">{{ $result['cutting_time'] }} мин</div>
                            <div class="result-formula">
                                T = L ÷ S<sub>m</sub> × 1.2 = {{ $result['cutting_length'] }} ÷ {{ $result['feed_rate'] }} × 1.2
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
                                <p>Некоторые параметры превышают возможности оборудования или рекомендации</p>
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
            // Элементы DOM
            const materialSelect = document.getElementById('material_id');
            const materialPreview = document.getElementById('materialPreview');
            const toolSelect = document.getElementById('tool_id');
            const toolInfo = document.getElementById('toolInfo');
            const diameterInput = document.getElementById('cutter_diameter');
            const teethInput = document.getElementById('number_of_teeth');
            const widthInput = document.getElementById('width_of_cut');
            const depthInput = document.getElementById('depth_of_cut');
            const widthHint = document.getElementById('widthHint');
            const depthHint = document.getElementById('depthHint');
            const operationSelect = document.getElementById('operation_type');

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

            // Данные инструментов
            const toolsData = {
                @foreach($tools as $tool)
                    {{ $tool->id }}: {
                    name: "{{ $tool->name }}",
                    type: "{{ $tool->tool_type_name }}",
                    material: "{{ $tool->material_type_name }}",
                    teethMin: {{ $tool->number_of_teeth_min }},
                    teethMax: {{ $tool->number_of_teeth_max }},
                    maxSpeed: {{ $tool->max_cutting_speed }}
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

            function updateToolInfo() {
                const selectedId = toolSelect.value;
                if (selectedId && toolsData[selectedId]) {
                    const tool = toolsData[selectedId];
                    document.getElementById('toolTeethRange').textContent = `Зубьев: ${tool.teethMin}-${tool.teethMax}`;
                    document.getElementById('toolMaterial').textContent = tool.material;
                    document.getElementById('toolMaxSpeed').textContent = tool.maxSpeed;

                    // Обновляем подсказку для количества зубьев
                    teethInput.setAttribute('min', tool.teethMin);
                    teethInput.setAttribute('max', tool.teethMax);
                    teethInput.value = Math.max(tool.teethMin, Math.min(teethInput.value, tool.teethMax));

                    toolInfo.style.display = 'block';
                } else {
                    toolInfo.style.display = 'none';
                }
            }

            function updateWidthHint() {
                const diameter = parseFloat(diameterInput.value) || 10;
                const operation = operationSelect.value;

                let recommendedMin, recommendedMax;
                switch (operation) {
                    case 'roughing':
                        recommendedMin = diameter * 0.4;
                        recommendedMax = diameter * 0.7;
                        break;
                    case 'semi_finishing':
                        recommendedMin = diameter * 0.2;
                        recommendedMax = diameter * 0.4;
                        break;
                    case 'finishing':
                        recommendedMin = diameter * 0.05;
                        recommendedMax = diameter * 0.2;
                        break;
                    default:
                        recommendedMin = diameter * 0.3;
                        recommendedMax = diameter * 0.6;
                }

                widthHint.textContent = `Рекомендуется: ${recommendedMin.toFixed(1)}-${recommendedMax.toFixed(1)} мм`;
                widthHint.style.color = '#28a745';
            }

            function updateDepthHint() {
                const diameter = parseFloat(diameterInput.value) || 10;
                const operation = operationSelect.value;

                let recommendedMax;
                switch (operation) {
                    case 'roughing':
                        recommendedMax = diameter * 1.0;
                        break;
                    case 'semi_finishing':
                        recommendedMax = diameter * 1.5;
                        break;
                    case 'finishing':
                        recommendedMax = diameter * 0.5;
                        break;
                    default:
                        recommendedMax = diameter * 1.0;
                }

                depthHint.textContent = `Макс. рекомендуется: ${recommendedMax.toFixed(1)} мм`;
                depthHint.style.color = '#28a745';
            }

            // Слушатели событий
            materialSelect.addEventListener('change', updateMaterialPreview);
            toolSelect.addEventListener('change', updateToolInfo);
            diameterInput.addEventListener('input', function() {
                updateWidthHint();
                updateDepthHint();
            });
            operationSelect.addEventListener('change', function() {
                updateWidthHint();
                updateDepthHint();
            });
            widthInput.addEventListener('input', updateWidthHint);
            depthInput.addEventListener('input', updateDepthHint);

            // Инициализация при загрузке
            updateMaterialPreview();
            updateToolInfo();
            updateWidthHint();
            updateDepthHint();

            // Валидация ввода
            diameterInput.addEventListener('change', function() {
                const value = parseFloat(this.value);
                if (value < 0.1) this.value = 0.1;
                if (value > 200) this.value = 200;
                updateWidthHint();
                updateDepthHint();
            });

            teethInput.addEventListener('change', function() {
                const value = parseInt(this.value);
                const toolId = toolSelect.value;
                if (toolId && toolsData[toolId]) {
                    const tool = toolsData[toolId];
                    if (value < tool.teethMin) this.value = tool.teethMin;
                    if (value > tool.teethMax) this.value = tool.teethMax;
                }
            });
        });
    </script>
@endsection
