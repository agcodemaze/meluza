// Ao sair da página, salva a posição do scroll
window.addEventListener('beforeunload', function() {
    localStorage.setItem('scrollPos', window.scrollY);
});

// Ao carregar a página, retorna à posição salva
window.addEventListener('load', function() {
    const scrollPos = localStorage.getItem('scrollPos');
    if(scrollPos) {
        window.scrollTo(0, parseInt(scrollPos));
        localStorage.removeItem('scrollPos'); 
    }
});