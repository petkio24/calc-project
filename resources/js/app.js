import 'bootstrap';

// Operation cards hover effects only
document.addEventListener('DOMContentLoaded', function() {
    const operationCards = document.querySelectorAll('.operation-card');

    operationCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.zIndex = '10';
        });

        card.addEventListener('mouseleave', function() {
            this.style.zIndex = '1';
        });
    });
});

// Drilling Calculator
function initDrillingCalculator() {
    const calculator = document.getElementById('calc_drilling');
    if (!calculator) return;

    const inputs = {
        diameter: document.getElementById('calc_diameter'),
        cuttingSpeed: document.getElementById('calc_cutting_speed'),
        rpm: document.getElementById('calc_rpm'),
        feedPerRev: document.getElementById('calc_feed_per_rev'),
        feedRate: document.getElementById('calc_feed_rate'),
        holeDepth: document.getElementById('calc_hole_depth'),
        material: document.getElementById('calc_material'),
        machiningTime: document.getElementById('calc_machining_time'),
        power: document.getElementById('calc_power')
    };

    const buttons = {
        calculate: document.getElementById('btn_calculate'),
        reset: document.getElementById('btn_reset')
    };

    // Enable dependent inputs when diameter is set
    inputs.diameter.addEventListener('input', function() {
        const hasDiameter = this.value && parseFloat(this.value) > 0;

        inputs.cuttingSpeed.disabled = !hasDiameter;
        inputs.rpm.disabled = !hasDiameter;
        inputs.feedPerRev.disabled = !hasDiameter;
        inputs.feedRate.disabled = !hasDiameter;
        inputs.holeDepth.disabled = !hasDiameter;
        inputs.material.disabled = !hasDiameter;
    });

    // Calculate RPM from cutting speed
    inputs.cuttingSpeed.addEventListener('input', function() {
        if (this.value && inputs.diameter.value) {
            const diameter = parseFloat(inputs.diameter.value);
            const speed = parseFloat(this.value);
            const rpm = (speed * 1000) / (Math.PI * diameter);
            inputs.rpm.value = rpm.toFixed(0);
        }
    });

    // Calculate cutting speed from RPM
    inputs.rpm.addEventListener('input', function() {
        if (this.value && inputs.diameter.value) {
            const diameter = parseFloat(inputs.diameter.value);
            const rpm = parseFloat(this.value);
            const speed = (Math.PI * diameter * rpm) / 1000;
            inputs.cuttingSpeed.value = speed.toFixed(1);
        }
    });

    // Calculate feed rate from feed per revolution
    inputs.feedPerRev.addEventListener('input', function() {
        if (this.value && inputs.rpm.value) {
            const feedPerRev = parseFloat(this.value);
            const rpm = parseFloat(inputs.rpm.value);
            const feedRate = feedPerRev * rpm;
            inputs.feedRate.value = feedRate.toFixed(1);
        }
    });

    // Calculate feed per revolution from feed rate
    inputs.feedRate.addEventListener('input', function() {
        if (this.value && inputs.rpm.value) {
            const feedRate = parseFloat(this.value);
            const rpm = parseFloat(inputs.rpm.value);
            const feedPerRev = feedRate / rpm;
            inputs.feedPerRev.value = feedPerRev.toFixed(3);
        }
    });

    // Main calculation function
    buttons.calculate.addEventListener('click', function() {
        calculateResults();
    });

    // Reset function
    buttons.reset.addEventListener('click', function() {
        Object.values(inputs).forEach(input => {
            if (input.type !== 'button') {
                input.value = '';
            }
        });
        inputs.cuttingSpeed.disabled = true;
        inputs.rpm.disabled = true;
        inputs.feedPerRev.disabled = true;
        inputs.feedRate.disabled = true;
        inputs.holeDepth.disabled = true;
        inputs.material.disabled = true;
    });

    function calculateResults() {
        // Calculate machining time
        if (inputs.feedRate.value && inputs.holeDepth.value) {
            const feedRate = parseFloat(inputs.feedRate.value);
            const holeDepth = parseFloat(inputs.holeDepth.value);
            const machiningTime = holeDepth / feedRate;
            inputs.machiningTime.value = machiningTime.toFixed(2);
        }

        // Calculate power (simplified formula)
        if (inputs.diameter.value && inputs.feedPerRev.value &&
            inputs.cuttingSpeed.value && inputs.material.value) {
            const diameter = parseFloat(inputs.diameter.value);
            const feed = parseFloat(inputs.feedPerRev.value);
            const speed = parseFloat(inputs.cuttingSpeed.value);
            const materialFactor = parseFloat(inputs.material.value) / 1000;

            const power = (diameter * feed * speed * materialFactor) / 60;
            inputs.power.value = power.toFixed(2);
        }
    }

    // Управление полем срока службы станка
    const machineAgeSelect = document.getElementById('machine_age');
    const customAgeContainer = document.getElementById('custom_age_container');

    function updateMachineAgeField() {
        if (machineAgeSelect.value === 'custom') {
            customAgeContainer.style.display = 'block';
        } else {
            customAgeContainer.style.display = 'none';
        }
    }

    machineAgeSelect.addEventListener('change', updateMachineAgeField);
    updateMachineAgeField(); // Инициализация при загрузке
}

// Initialize calculator when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initDrillingCalculator();
});

// Функциональность для справочников
document.addEventListener('DOMContentLoaded', function() {
    // Фильтрация таблиц
    initTableFilters();

    // Поиск в реальном времени
    initLiveSearch();

    // Сортировка таблиц
    initTableSorting();

    // Экспорт данных
    initExportButtons();
});

function initTableFilters() {
    const filterInputs = document.querySelectorAll('.table-filter');

    filterInputs.forEach(input => {
        input.addEventListener('input', function() {
            const filterValue = this.value.toLowerCase();
            const table = this.closest('.data-section').querySelector('.data-table');
            const rows = table.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filterValue) ? '' : 'none';
            });
        });
    });
}

function initLiveSearch() {
    const searchInput = document.querySelector('.search-box input');
    if (!searchInput) return;

    let timeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            // Здесь можно добавить AJAX-поиск
            console.log('Search:', this.value);
        }, 300);
    });
}

function initTableSorting() {
    const sortableHeaders = document.querySelectorAll('.data-table th[data-sort]');

    sortableHeaders.forEach(header => {
        header.style.cursor = 'pointer';
        header.addEventListener('click', function() {
            const table = this.closest('.data-table');
            const columnIndex = this.cellIndex;
            const isNumeric = this.dataset.sort === 'numeric';
            const isAscending = !this.classList.contains('asc');

            sortTable(table, columnIndex, isNumeric, isAscending);

            // Обновление классов
            sortableHeaders.forEach(h => h.classList.remove('asc', 'desc'));
            this.classList.toggle('asc', isAscending);
            this.classList.toggle('desc', !isAscending);
        });
    });
}

function sortTable(table, columnIndex, isNumeric, ascending) {
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));

    rows.sort((a, b) => {
        let aValue = a.cells[columnIndex].textContent.trim();
        let bValue = b.cells[columnIndex].textContent.trim();

        if (isNumeric) {
            aValue = parseFloat(aValue) || 0;
            bValue = parseFloat(bValue) || 0;
        }

        if (aValue < bValue) return ascending ? -1 : 1;
        if (aValue > bValue) return ascending ? 1 : -1;
        return 0;
    });

    // Очистка и перезапись строк
    rows.forEach(row => tbody.appendChild(row));
}

function initExportButtons() {
    const exportButtons = document.querySelectorAll('.export-btn');

    exportButtons.forEach(button => {
        button.addEventListener('click', function() {
            const table = this.closest('.data-section').querySelector('.data-table');
            exportTableToCSV(table, 'справочник.csv');
        });
    });
}

function exportTableToCSV(table, filename) {
    const rows = table.querySelectorAll('tr');
    const csv = [];

    rows.forEach(row => {
        const rowData = [];
        row.querySelectorAll('th, td').forEach(cell => {
            rowData.push(`"${cell.textContent.replace(/"/g, '""')}"`);
        });
        csv.push(rowData.join(','));
    });

    const csvString = csv.join('\n');
    const blob = new Blob([csvString], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');

    if (link.download !== undefined) {
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', filename);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
}
