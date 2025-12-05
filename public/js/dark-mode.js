// Dark Mode Management - IslaControl
// Este script gestiona el modo oscuro en todas las páginas de la aplicación

(function() {
    'use strict';

    // Initialize theme on page load (before DOM ready to prevent flash)
    function initTheme() {
        const savedTheme = localStorage.getItem('theme') || 'light';
        if (savedTheme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }

    // Call immediately
    initTheme();

    // Wait for DOM to be ready for toggle functionality
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupThemeToggle);
    } else {
        setupThemeToggle();
    }

    function setupThemeToggle() {
        updateThemeIcons();
    }

    // Toggle theme function (available globally)
    window.toggleTheme = function() {
        const isDark = document.documentElement.classList.toggle('dark');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        updateThemeIcons();
    };

    // Update theme icons
    function updateThemeIcons() {
        const isDark = document.documentElement.classList.contains('dark');
        const darkSun = document.querySelector('.dark-sun');
        const lightMoon = document.querySelector('.light-moon');

        if (darkSun && lightMoon) {
            darkSun.style.display = isDark ? 'block' : 'none';
            lightMoon.style.display = isDark ? 'none' : 'block';
        }
    }

    // Export for module usage
    if (typeof module !== 'undefined' && module.exports) {
        module.exports = { toggleTheme: window.toggleTheme };
    }
})();
