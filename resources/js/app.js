// Bootstrap
import 'bootstrap';

// Vanilla JavaScript for interactivity
// document.addEventListener('DOMContentLoaded', function() {
//     console.log('Calc Project loaded');
//
//     // Menu active state
//     const currentPage = window.location.pathname;
//     const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
//
//     navLinks.forEach(link => {
//         if (link.getAttribute('href') === currentPage) {
//             link.classList.add('active');
//         }
//     });
//
//     // Dashboard card hover effects
//     const cards = document.querySelectorAll('.card');
//     cards.forEach(card => {
//         card.addEventListener('mouseenter', function() {
//             this.style.transform = 'translateY(-5px)';
//             this.style.transition = 'transform 0.3s ease';
//         });
//
//         card.addEventListener('mouseleave', function() {
//             this.style.transform = 'translateY(0)';
//         });
//     });
//
//     // Sidebar menu interactions
//     const menuItems = document.querySelectorAll('.list-group-item');
//     menuItems.forEach(item => {
//         item.addEventListener('click', function(e) {
//             if (!this.classList.contains('active')) {
//                 menuItems.forEach(i => i.classList.remove('active'));
//                 this.classList.add('active');
//             }
//         });
//     });
// });
