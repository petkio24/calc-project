import 'bootstrap';


// Operation cards interaction
document.addEventListener('DOMContentLoaded', function() {
    const operationCards = document.querySelectorAll('.operation-card');

    operationCards.forEach(card => {
        card.addEventListener('click', function() {
            const operation = this.getAttribute('data-operation');

            // Анимация клика
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'translateY(-8px)';
            }, 150);

            // Здесь будет переход к соответствующему калькулятору
            console.log('Выбрана операция:', operation);

            // Временное сообщение
            alert(`Выбрана операция: ${this.querySelector('.operation-title').textContent}`);
        });

        // Дополнительные hover эффекты
        card.addEventListener('mouseenter', function() {
            this.style.zIndex = '10';
        });

        card.addEventListener('mouseleave', function() {
            this.style.zIndex = '1';
        });
    });
});
