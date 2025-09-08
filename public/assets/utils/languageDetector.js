
    (function() {
        // Verifica se já existe "lang" na URL
        const url = new URL(window.location.href);
        if (!url.searchParams.has('lang')) {
            let idioma = navigator.language || navigator.userLanguage || 'en';
            idioma = idioma.substring(0, 2); // pega só "pt", "en", "es"...

            const suportados = ['pt', 'en', 'es'];
            if (!suportados.includes(idioma)) {
                idioma = 'pt'; // fallback
            }

            // adiciona ?lang=xx mantendo os outros parâmetros
            url.searchParams.set('lang', idioma);
            window.location.replace(url.toString());
        }
    })();
