const CACHE_NAME = "itso-system-v1";

const urlsToCache = [
  "login.php",
  "assets/css/style.css",

];

self.addEventListener('install', e => {
  e.waitUntil(
    Promise.all(
      urlsToCache.map(u =>
        fetch(u).then(r => { if (!r.ok) throw new Error(u+' → '+r.status); return r; })
      )
    )
    .then(responses => caches.open(CACHE_NAME).then(c => c.addAll(urlsToCache)))
    .catch(err => console.error('cache install failed:', err))
  );
});

self.addEventListener("fetch", event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => response || fetch(event.request))
  );
});