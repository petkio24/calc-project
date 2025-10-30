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
                        <select class="calc-input-group__input" id="material_id" name="material_id" required>
                            <option value="">Выберите материал</option>
                            @foreach($materials as $material)
                                <option value="{{ $material->id }}"
                                    {{ old('material_id') == $material->id ? 'selected' : '' }}>
                                    {{ $material->name }} ({{ $material->hardness_range ?? 'стандарт' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="input-row">
                        <div class="calc-input-group">
                            <label class="calc-input-group__label" for="initial_diameter">Диаметр исходный (мм)</label>
                            <input class="calc-input-group__input" id="initial_diameter"
                                   name="initial_diameter" type="number" step="0.1" placeholder="Ø исходный"
                                   value="{{ old('initial_diameter') }}" required>
                        </div>

                        <div class="calc-input-group">
                            <label class="calc-input-group__label" for="final_diameter">Диаметр получаемый (мм)</label>
                            <input class="calc-input-group__input" id="final_diameter"
                                   name="final_diameter" type="number" step="0.1" placeholder="Ø получаемый"
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

                    <div class="calc-input-group">
                        <label class="calc-input-group__label" for="tool_material_id">Материал инструмента</label>
                        <select class="calc-input-group__input" id="tool_material_id" name="tool_material_id" required>
                            <option value="">Выберите материал инструмента</option>
                            @foreach($toolMaterials as $toolMaterial)
                                <option value="{{ $toolMaterial->id }}"
                                    {{ old('tool_material_id') == $toolMaterial->id ? 'selected' : '' }}>
                                    {{ $toolMaterial->name }} (до {{ $toolMaterial->max_cutting_speed }} м/мин)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="calc-input-group">
                        <label class="calc-input-group__label" for="tool_geometry_id">Геометрия инструмента</label>
                        <select class="calc-input-group__input" id="tool_geometry_id" name="tool_geometry_id" required>
                            <option value="">Выберите геометрию</option>
                            @foreach($toolGeometries as $geometry)
                                <option value="{{ $geometry->id }}"
                                    {{ old('tool_geometry_id') == $geometry->id ? 'selected' : '' }}>
                                    {{ $geometry->name }} ({{ $geometry->rake_angle > 0 ? '+' : '' }}{{ $geometry->rake_angle }}°)
                                </option>
                            @endforeach
                        </select>
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

                    <div class="calc-input-group">
                        <label class="calc-input-group__label" for="machine_type_id">Тип станка</label>
                        <select class="calc-input-group__input" id="machine_type_id" name="machine_type_id" required>
                            <option value="">Выберите тип станка</option>
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
                            <select class="calc-input-group__input" id="operation_type" name="operation_type" required>
                                <option value="roughing" {{ old('operation_type') == 'roughing' ? 'selected' : '' }}>Черновая обработка</option>
                                <option value="finishing" {{ old('operation_type') == 'finishing' ? 'selected' : '' }}>Чистовая обработка</option>
                            </select>
                        </div>

                        <div class="calc-input-group">
                            <label class="calc-input-group__label" for="surface_quality">Качество поверхности</label>
                            <select class="calc-input-group__input" id="surface_quality" name="surface_quality" required>
                                <option value="normal" {{ old('surface_quality') == 'normal' ? 'selected' : '' }}>Нормальное</option>
                                <option value="good" {{ old('surface_quality') == 'good' ? 'selected' : '' }}>Хорошее</option>
                                <option value="excellent" {{ old('surface_quality') == 'excellent' ? 'selected' : '' }}>Отличное</option>
                            </select>
                        </div>
                    </div>

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
                            <div class="result-value">{{ $result['material'] }}</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Материал инструмента</div>
                            <div class="result-value">{{ $result['tool_material'] }}</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Геометрия инструмента</div>
                            <div class="result-value">{{ $result['tool_geometry'] }}</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Тип станка</div>
                            <div class="result-value">{{ $result['machine_type'] }}</div>
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
                                <div class="result-warning">⚠ Превышение максимальных оборотов станка</div>
                            @endif
                        </div>

                        <div class="result-card">
                            <div class="result-label">Скорость резания</div>
                            <div class="result-value">{{ $result['cutting_speed'] }} м/мин</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Подача</div>
                            <div class="result-value">{{ $result['feed'] }} мм/об</div>
                        </div>

                        <div class="result-card {{ $result['is_power_valid'] ? 'success' : 'danger' }}">
                            <div class="result-label">Мощность резания</div>
                            <div class="result-value">{{ $result['cutting_power'] }} кВт</div>
                            @if(!$result['is_power_valid'])
                                <div class="result-warning">⚠ Превышение мощности станка</div>
                            @endif
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
@endsection
