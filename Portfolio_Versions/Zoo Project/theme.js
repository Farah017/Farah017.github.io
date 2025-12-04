
    // Get the root element
    var r = document.querySelector(':root');
    let theme = 0; // 0 = light, 1 = dark

    function changeTheme() {
        if (theme === 0) {
            theme = 1; // Dark mode
            r.style.setProperty('--primary-bg-colour', '#1e1e1e');   // dark background
            r.style.setProperty('--primary-colour', '#f0f0f0');      // light text
            r.style.setProperty('--heading-colour', '#ffffff');      // white headings
            r.style.setProperty('--navbar-bg', '#111111');           // dark navbar
            r.style.setProperty('--banner-bg', '#222222');           // dark banner
            r.style.setProperty('--button-bg', '#444444');           // dark buttons
            r.style.setProperty('--button-text', '#ffffff');         // button text
            r.style.setProperty('--card-bg', '#2a2a2a');             // dark cards
            r.style.setProperty('--form-bg', '#333333');             // dark forms
            r.style.setProperty('--footer-bg', '#111111');           // dark footer
        } else {
            theme = 0; // Light mode
            r.style.setProperty('--primary-bg-colour', '#f5fff5');   // light background
            r.style.setProperty('--primary-colour', 'black');      // dark text for contrast on light background
            r.style.setProperty('--heading-colour', '#3D513D');      // green headings
            r.style.setProperty('--navbar-bg', '#3D513D');           // green navbar
            r.style.setProperty('--banner-bg', '#3D513D');           // green banner
            r.style.setProperty('--button-bg', 'black');             // black buttons
            r.style.setProperty('--button-text', 'white');           // button text
            r.style.setProperty('--card-bg', '#ffffff');             // white cards
            r.style.setProperty('--form-bg', '#ffffff');             // white forms
            r.style.setProperty('--footer-bg', '#2e4d2c');           // green footer
        }
    }

