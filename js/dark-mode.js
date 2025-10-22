// Dark Mode
const toggle = $('#darkModeToggle');
const body = $('body');
const storageKey = 'themePreference';
const darkTheme = 'dark';
const lightTheme = 'light';

function applyTheme(theme) {
    if (theme === darkTheme) {
        body.attr('data-bs-theme', darkTheme);
        toggle.prop('checked', true);
    } else {
        body.attr('data-bs-theme', lightTheme);
        toggle.prop('checked', false);
    }
}

let currentTheme = localStorage.getItem(storageKey) || lightTheme;
applyTheme(currentTheme);

toggle.on('change', function() {
    if (toggle.is(':checked')) {
        applyTheme(darkTheme);
        localStorage.setItem(storageKey, darkTheme);
    } else {
        applyTheme(lightTheme);
        localStorage.setItem(storageKey, lightTheme);
    }
});
