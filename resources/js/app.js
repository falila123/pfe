import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import './livres';

// 🔔 Auto-disparition des messages flash (succès / erreur) après 5 s
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.alert-success, .alert-danger').forEach((el) => {
        // On garde les encarts d'information à l'intérieur des modales
        if (el.closest('.modal')) return;

        setTimeout(() => {
            el.style.transition = 'opacity .4s ease';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 400);
        }, 5000);
    });
});