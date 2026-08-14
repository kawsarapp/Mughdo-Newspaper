/**
 * Mughdo Newspaper Service Worker for PWA Offline Caching
 */

const CACHE_NAME = 'mughdo-news-cache-v1';
const URLS_TO_CACHE = [
  '/',
  '/assets/css/main.css',
  '/assets/js/app.js',
  '/assets/js/spa-router.js'
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(URLS_TO_CACHE);
    })
  );
});

self.addEventListener('fetch', (event) => {
  event.respondWith(
    caches.match(event.request).then((response) => {
      return response || fetch(event.request);
    })
  );
});
