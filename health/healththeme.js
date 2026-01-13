// Get the root element
var r = document.querySelector(':root');
let theme = 0; // 0 = light, 1 = dark

function changeTheme() {
    if (theme === 0) {
        theme = 1; // Dark mode
        r.style.setProperty('--primary-bg', '#1e1e1e');        // dark background
        r.style.setProperty('--primary-text', '#ffffff');      // light text
        r.style.setProperty('--heading-text', '#ffffff');      // light headings
        r.style.setProperty('--dash-heading-text', '#ffffff'); // light headings
        r.style.setProperty('--navbar-bg', '#1D3B58');         // blue background
        r.style.setProperty('--navbar-text', '#000000');       // dark text
        r.style.setProperty('--banner-bg', '#001024');         // dark background
        r.style.setProperty('--button-bg', '#000000');         // dark background
        r.style.setProperty('--button-text', '#ffffff');       // light text
        r.style.setProperty('--sign-up-button-bg', '#5C6C7D'); // light grey background
        r.style.setProperty('--card-bg', '#C4CBD6');           // grey background
        r.style.setProperty('--form-bg', '#5C6C7D');           // dark grey background
        r.style.setProperty('--footer-bg', '#1D3B58');         // blue background
        r.style.setProperty('--footer-text', '#000000');       // dark text
    } else {
        theme = 0; // Light Mode
        r.style.setProperty('--primary-bg', '#ffffff');        // light background
        r.style.setProperty('--primary-text', '#000000');      // dark text
        r.style.setProperty('--heading-text', '#000000');      // dark headings
        r.style.setProperty('--dash-heading-text', '#ffffff'); // light headings
        r.style.setProperty('--navbar-bg', '#1D3B58');         // blue background
        r.style.setProperty('--navbar-text', '#ffffff');       // light text
        r.style.setProperty('--banner-bg', '#001024');         // dark background
        r.style.setProperty('--button-bg', '#000000');         // dark background
        r.style.setProperty('--button-text', '#ffffff');       // light text
        r.style.setProperty('--sign-up-button-bg', '#5C6C7D'); // light grey background
        r.style.setProperty('--card-bg', '#C4CBD6');           // grey background
        r.style.setProperty('--form-bg', '#5C6C7D');           // dark grey background
        r.style.setProperty('--footer-bg', '#1D3B58');         // blue background
        r.style.setProperty('--footer-text', '#ffffff');       // light text
    }
}








