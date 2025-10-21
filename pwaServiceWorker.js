self.addEventListener('install', (event) => {
  console.log('Service Worker instalado');
  self.skipWaiting(); // força ativação imediata
});

self.addEventListener('activate', (event) => {
  console.log('Service Worker ativado');
  event.waitUntil(self.clients.claim()); // assume controle imediato
});

self.addEventListener('fetch', (event) => {
  // Só loga, sem interferir em requisições
  console.log('Requisição:', event.request.url);
});