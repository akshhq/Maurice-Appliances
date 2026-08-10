/* ============================================================
   MAURICE APPLIANCES — service worker
   Conservative offline strategy:
     • Static assets  → stale-while-revalidate
     • HTML/PHP pages → network-first (always fresh prices/specs)
     • Offline pages  → cached shell fallback
   Bump CACHE_VERSION on each deploy to invalidate old caches.
   ============================================================ */

const CACHE_VERSION = 'maurice-v1.0.0';
const STATIC_CACHE  = `${CACHE_VERSION}-static`;
const PAGE_CACHE    = `${CACHE_VERSION}-pages`;

const PRECACHE = [
  '/',
  '/index.php',
  '/assets/css/style.css',
  '/assets/js/app.js',
  '/errors/404.html',
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(STATIC_CACHE)
      .then((cache) => cache.addAll(PRECACHE).catch(() => null))
      .then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys()
      .then((keys) => Promise.all(
        keys.filter((k) => !k.startsWith(CACHE_VERSION)).map((k) => caches.delete(k))
      ))
      .then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', (event) => {
  const { request } = event;
  if (request.method !== 'GET') return;

  const url = new URL(request.url);
  if (url.origin !== self.location.origin) return;      // leave CDNs alone
  if (url.pathname.startsWith('/api/')) return;          // never cache API responses

  const isStatic = /\.(css|js|woff2?|svg|png|jpe?g|webp|avif|ico)$/.test(url.pathname);

  if (isStatic) {
    // Stale-while-revalidate
    event.respondWith(
      caches.open(STATIC_CACHE).then((cache) =>
        cache.match(request).then((cached) => {
          const network = fetch(request)
            .then((res) => { if (res.ok) cache.put(request, res.clone()); return res; })
            .catch(() => cached);
          return cached || network;
        })
      )
    );
    return;
  }

  // Network-first for documents
  event.respondWith(
    fetch(request)
      .then((res) => {
        if (res.ok) {
          const copy = res.clone();
          caches.open(PAGE_CACHE).then((c) => c.put(request, copy));
        }
        return res;
      })
      .catch(() =>
        caches.match(request).then((cached) => cached || caches.match('/errors/404.html'))
      )
  );
});
