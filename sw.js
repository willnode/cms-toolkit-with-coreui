// use a cacheName for cache versioning
var cacheName = 'v1:static';

self.addEventListener('beforeinstallprompt', function(e) {
	// TO DO
});

// during the install phase you usually want to cache static assets
self.addEventListener('install', function(e) {
    // once the SW is installed, go ahead and fetch the resources to make this work offline
    e.waitUntil(
        caches.open(cacheName).then(async function(cache) {
            await cache.addAll([
                './',
                './login',
                './vendors/bootstrap/bootstrap.min.js',
                './vendors/bootstrap-table/bootstrap-table.min.css',
                './vendors/bootstrap-table/bootstrap-table.min.js',
                './vendors/coreui/css/style.min.css',
                './vendors/coreui/icons/css/coreui-icons.min.css',
                './vendors/coreui/js/coreui.min.js',
                './vendors/font-awesome/css/font-awesome.min.css',
                './vendors/jquery/jquery.min.js',
                './assets/style.css',
                './assets/script.js',
            ]);
            self.skipWaiting();
        })
    );
});

// when the browser fetches a url
self.addEventListener('fetch', function(event) {
    // either respond with the cached object or go ahead and fetch the actual url
    event.respondWith(
        caches.match(event.request).then(function(response) {
            if (response) {
                // retrieve from cache
                return response;
            }
            // fetch as normal
            return fetch(event.request);
        })
    );
});
