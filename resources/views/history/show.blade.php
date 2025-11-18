@extends('layouts.app')

@section('title', $title)

@section('content')
    <div class="history-container">
        <div class="history-header">
            <div>
                <h1>Детали расчета</h1>
                <div class="calculation-path">
                    <a href="{{ route('history.index') }}">История расчетов</a>
                    <span class="path-separator">/</span>
                    <span>{{ $calculation->title }}</span>
                </div>
            </div>
            <div class="history-actions">
                <button class="btn btn-outline favorite-btn"
                        data-id="{{ $calculation->id }}"
                        data-favorite="{{ $calculation->is_favorite ? '1' : '0' }}">
                    @if($calculation->is_favorite)
                        ★ Удалить из избранного
                    @else
                        ☆ Добавить в избранное
                    @endif
                </button>
                <a href="{{ route('history.index') }}" class="btn btn-outline">
                    ← Назад к истории
                </a>
            </div>
        </div>

        <!-- Основная информация -->
        <div class="calculation-detail">
            <div class="detail-header">
                <h2>{{ $calculation->title }}</h2>
                <div class="calculation-meta">
                    <span class="operation-badge {{ $calculation->operation_type }}">
                        @if($calculation->operation_type == 'turning')
                            🔄 Точение
                        @elseif($calculation->operation_type == 'milling')
                            ⚙️ Фрезерование
                        @else
                            🔩 Сверление
                        @endif
                    </span>
                    <span class="calculation-date">
                        Создан: {{ $calculation->created_at->format('d.m.Y в H:i') }}
                    </span>
                    @if($calculation->updated_at->ne($calculation->created_at))
                        <span class="calculation-date">
                            Обновлен: {{ $calculation->updated_at->format('d.m.Y в H:i') }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Входные параметры -->
            <div class="detail-section">
                <h3>📋 Входные параметры</h3>
                <div class="parameters-grid">
                    @php $inputParams = $calculation->input_parameters; @endphp

                    @if($calculation->operation_type == 'turning')
                        <div class="parameter-card">
                            <label>Материал заготовки:</label>
                            <span>{{ $calculation->material_info }}</span>
                        </div>
                        <div class="parameter-card">
                            <label>Диаметр исходный:</label>
                            <span>{{ $inputParams['initial_diameter'] ?? '—' }} мм</span>
                        </div>
                        <div class="parameter-card">
                            <label>Диаметр получаемый:</label>
                            <span>{{ $inputParams['final_diameter'] ?? '—' }} мм</span>
                        </div>
                        <div class="parameter-card">
                            <label>Длина обработки:</label>
                            <span>{{ $inputParams['cutting_length'] ?? '—' }} мм</span>
                        </div>
                        <div class="parameter-card">
                            <label>Тип операции:</label>
                            <span>
                                @if(($inputParams['operation_type'] ?? '') == 'roughing')
                                    Черновая
                                @else
                                    Чистовая
                                @endif
                            </span>
                        </div>

                    @elseif($calculation->operation_type == 'milling')
                        <div class="parameter-card">
                            <label>Материал заготовки:</label>
                            <span>{{ $calculation->material_info }}</span>
                        </div>
                        <div class="parameter-card">
                            <label>Диаметр фрезы:</label>
                            <span>{{ $inputParams['cutter_diameter'] ?? '—' }} мм</span>
                        </div>
                        <div class="parameter-card">
                            <label>Количество зубьев:</label>
                            <span>{{ $inputParams['number_of_teeth'] ?? '—' }}</span>
                        </div>
                        <div class="parameter-card">
                            <label>Ширина фрезерования:</label>
                            <span>{{ $inputParams['width_of_cut'] ?? '—' }} мм</span>
                        </div>
                        <div class="parameter-card">
                            <label>Глубина резания:</label>
                            <span>{{ $inputParams['depth_of_cut'] ?? '—' }} мм</span>
                        </div>

                    @elseif($calculation->operation_type == 'drilling')
                        <div class="parameter-card">
                            <label>Материал заготовки:</label>
                            <span>{{ $calculation->material_info }}</span>
                        </div>
                        <div class="parameter-card">
                            <label>Диаметр сверла:</label>
                            <span>{{ $inputParams['diameter'] ?? '—' }} мм</span>
                        </div>
                        <div class="parameter-card">
                            <label>Глубина отверстия:</label>
                            <span>{{ $inputParams['hole_depth'] ?? '—' }} мм</span>
                        </div>
                        <div class="parameter-card">
                            <label>Тип операции:</label>
                            <span>
                                @if(($inputParams['operation_type'] ?? '') == 'roughing')
                                    Черновая
                                @else
                                    Чистовая
                                @endif
                            </span>
                        </div>
                    @endif

                    @if(isset($inputParams['machine_type_id']) && $inputParams['machine_type_id'])
                        <div class="parameter-card">
                            <label>Тип станка:</label>
                            <span>Указан (ID: {{ $inputParams['machine_type_id'] }})</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Результаты расчета -->
            <div class="detail-section">
                <h3>📊 Результаты расчета</h3>
                <div class="results-grid">
                    @php $results = $calculation->calculation_results; @endphp

                    <div class="result-card highlight">
                        <div class="result-label">Скорость резания</div>
                        <div class="result-value">{{ $results['cutting_speed'] ?? '—' }} м/мин</div>
                    </div>

                    <div class="result-card highlight">
                        <div class="result-label">Обороты шпинделя</div>
                        <div class="result-value">{{ $results['spindle_rpm'] ?? '—' }} об/мин</div>
                    </div>

                    <div class="result-card">
                        <div class="result-label">Подача</div>
                        <div class="result-value">
                            @if($calculation->operation_type == 'milling')
                                {{ $results['feed_per_tooth'] ?? '—' }} мм/зуб
                            @else
                                {{ $results['feed_per_revolution'] ?? '—' }} мм/об
                            @endif
                        </div>
                    </div>

                    <div class="result-card">
                        <div class="result-label">Минутная подача</div>
                        <div class="result-value">{{ $results['feed_rate'] ?? '—' }} мм/мин</div>
                    </div>

                    <div class="result-card">
                        <div class="result-label">Мощность резания</div>
                        <div class="result-value">{{ $results['cutting_power'] ?? '—' }} кВт</div>
                    </div>

                    <div class="result-card">
                        <div class="result-label">Эффективная мощность</div>
                        <div class="result-value">{{ $results['effective_power'] ?? '—' }} кВт</div>
                    </div>

                    @if(isset($results['thrust_force']))
                        <div class="result-card">
                            <div class="result-label">Осевое усилие</div>
                            <div class="result-value">{{ $results['thrust_force'] }} Н</div>
                        </div>
                    @endif

                    @if(isset($results['cutting_force']))
                        <div class="result-card">
                            <div class="result-label">Усилие резания</div>
                            <div class="result-value">{{ $results['cutting_force'] }} Н</div>
                        </div>
                    @endif

                    @if(isset($results['torque']))
                        <div class="result-card">
                            <div class="result-label">Крутящий момент</div>
                            <div class="result-value">{{ $results['torque'] }} Н·м</div>
                        </div>
                    @endif

                    @if(isset($results['material_removal_rate']))
                        <div class="result-card">
                            <div class="result-label">Съем материала</div>
                            <div class="result-value">{{ $results['material_removal_rate'] }} см³/мин</div>
                        </div>
                    @endif

                    @if(isset($results['cutting_time_per_hole']))
                        <div class="result-card">
                            <div class="result-label">Время на отверстие</div>
                            <div class="result-value">{{ $results['cutting_time_per_hole'] }} мин</div>
                        </div>
                    @endif

                    @if(isset($results['total_cutting_time']))
                        <div class="result-card">
                            <div class="result-label">Общее время</div>
                            <div class="result-value">{{ $results['total_cutting_time'] }} мин</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Заметки -->
            @if($calculation->notes)
                <div class="detail-section">
                    <h3>📝 Заметки</h3>
                    <div class="notes-content">
                        {{ $calculation->notes }}
                    </div>
                </div>
            @endif

            <!-- Действия -->
            <div class="detail-actions">
                <button class="btn btn-danger delete-btn" data-id="{{ $calculation->id }}">
                    🗑️ Удалить расчет
                </button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Добавление/удаление из избранного
            const favoriteBtn = document.querySelector('.favorite-btn');
            if (favoriteBtn) {
                favoriteBtn.addEventListener('click', function() {
                    const calculationId = this.dataset.id;
                    const isFavorite = this.dataset.favorite === '1';

                    fetch(`/history/${calculationId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            is_favorite: !isFavorite
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                this.dataset.favorite = isFavorite ? '0' : '1';
                                if (isFavorite) {
                                    this.innerHTML = '☆ Добавить в избранное';
                                } else {
                                    this.innerHTML = '★ Удалить из избранного';
                                }
                                showNotification('Настройки сохранены', 'success');
                            }
                        });
                });
            }

            // Удаление расчета
            const deleteBtn = document.querySelector('.delete-btn');
            if (deleteBtn) {
                deleteBtn.addEventListener('click', function() {
                    const calculationId = this.dataset.id;

                    if (confirm('Удалить этот расчет из истории?')) {
                        fetch(`/history/${calculationId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    window.location.href = '/history';
                                }
                            });
                    }
                });
            }

            function showNotification(message, type) {
                // Реализация уведомлений (можно использовать вашу существующую)
                alert(message);
            }
        });
    </script>
@endsection
