// Global dark mode toggle functionality
window.toggleDarkMode = function() {
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    document.documentElement.setAttribute('data-theme', isDark ? 'light' : 'dark');
    localStorage.setItem('theme', isDark ? 'light' : 'dark');
    updateAllDarkModeIcons();
}

function updateAllDarkModeIcons() {
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const icons = document.querySelectorAll('[id^="darkModeIcon"]');
    console.log('Found dark mode icons:', icons);
    icons.forEach(function(icon) {
        const btn = icon.closest('button');
        if (isDark) {
            icon.textContent = '☀️';
            if (btn) {
                btn.style.background = 'var(--primary)';
                btn.style.color = 'var(--background)';
                btn.style.borderColor = 'var(--primary)';
            }
        } else {
            icon.textContent = '🌙';
            if (btn) {
                btn.style.background = 'var(--background)';
                btn.style.color = 'var(--primary)';
                btn.style.borderColor = 'var(--primary)';
            }
        }
    });
}

// Restore theme on page load
(function() {
    const theme = localStorage.getItem('theme');
    if (theme === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
    }
    document.addEventListener('DOMContentLoaded', function() {
        // Attach click listeners to all dark mode toggle buttons
        document.querySelectorAll('button[id^="darkModeToggle"]').forEach(function(btn) {
            btn.addEventListener('click', window.toggleDarkMode);
        });
        updateAllDarkModeIcons();
    });
    window.addEventListener('storage', updateAllDarkModeIcons);
})(); 