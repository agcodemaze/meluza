if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register('/pwaServiceWorker.js')
    .then(() => console.log('Service Worker registrado!'))
    .catch(err => console.error('Falha no registro', err));
}