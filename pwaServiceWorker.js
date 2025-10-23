self.addEventListener('install', (event) => {
  self.skipWaiting(); // força ativação imediata
});

self.addEventListener('activate', (event) => {
  event.waitUntil(self.clients.claim()); // assume controle imediato
});

self.addEventListener('fetch', (event) => {
});