'use strict';
const MANIFEST = 'flutter-app-manifest';
const TEMP = 'flutter-temp-cache';
const CACHE_NAME = 'flutter-app-cache';

const RESOURCES = {"assets/AssetManifest.bin": "046d17afa872dcc1e40be01fefb7c9e5",
"assets/AssetManifest.bin.json": "32aa0656b3c8eec704be5c82d9ca27c7",
"assets/AssetManifest.json": "cd0a8242f2a478a6fd86d1cadf89c563",
"assets/assets/fonts/IRANSansXFaNum-Bold.ttf": "aed42c09da7fe0468268b4ee78001b7f",
"assets/assets/fonts/IRANSansXFaNum-ExtraBold.ttf": "6c555e5cb29e70e67b60346a9cbf4bbf",
"assets/assets/fonts/IRANSansXFaNum-Medium.ttf": "ff2da440d62aee697efabb9c6164c445",
"assets/assets/fonts/IRANSansXFaNum-Regular.ttf": "7c8c2f00c8d96ec4ab1da628790a1a67",
"assets/assets/images/about_us.webp": "2a837bb7046ba225923d3c4074b82480",
"assets/assets/images/card.png": "ca16de5f9498dfb63bb3fbc070ce93e1",
"assets/assets/images/customer-service.svg": "9d5c54c46b67fd8a2368d3f12293aea3",
"assets/assets/images/empty1.png": "5c13d4f55b721c54b3a28b0201aa3901",
"assets/assets/images/guarantee.svg": "7b8a96541311ca6d88ff20aadb82c33a",
"assets/assets/images/header_logo.png": "ae9c1b5d269cb39b701d32e5d927a081",
"assets/assets/images/ime.svg": "ab040d96774e5c18a9aa4d9bd1269e76",
"assets/assets/images/logo.png": "d110724cd3b03da37cb99e1960c13e2d",
"assets/assets/images/logo2.png": "ea9ca59e1cfacc65f161795ef46c2ed1",
"assets/assets/images/logo_adaptive_back.png": "44e2de688fa2e2854f21b3a7f8bc4a5d",
"assets/assets/images/logo_adaptive_fore.png": "b35e286e3f7a9e7ae1bbaac06363dd90",
"assets/assets/images/phone-vector.png": "ac14976ef59db7870a4a0ea831a69f06",
"assets/assets/images/return.svg": "b0b88da29199d7522c6a77f1f5444e3f",
"assets/assets/images/wallet.png": "3439fe44ba53f6bfd2b5b26d893380c6",
"assets/FontManifest.json": "e7ad0d71ff3800a3f7bae0dbece04464",
"assets/fonts/MaterialIcons-Regular.otf": "88c772e3394c8aa8006b73d08ac5dbf9",
"assets/NOTICES": "300e547765722cb39017479d046c5e6a",
"assets/packages/cupertino_icons/assets/CupertinoIcons.ttf": "391ff5f9f24097f4f6e4406690a06243",
"assets/packages/flutter_map/lib/assets/flutter_map_logo.png": "208d63cc917af9713fc9572bd5c09362",
"assets/packages/font_awesome_flutter/lib/fonts/fa-brands-400.ttf": "17ee8e30dde24e349e70ffcdc0073fb0",
"assets/packages/font_awesome_flutter/lib/fonts/fa-regular-400.ttf": "f3307f62ddff94d2cd8b103daf8d1b0f",
"assets/packages/font_awesome_flutter/lib/fonts/fa-solid-900.ttf": "04f83c01dded195a11d21c2edf643455",
"assets/packages/wakelock_plus/assets/no_sleep.js": "7748a45cd593f33280669b29c2c8919a",
"assets/shaders/ink_sparkle.frag": "ecc85a2e95f5e9f53123dcaf8cb9b6ce",
"canvaskit/canvaskit.js": "738255d00768497e86aa4ca510cce1e1",
"canvaskit/canvaskit.js.symbols": "74a84c23f5ada42fe063514c587968c6",
"canvaskit/canvaskit.wasm": "9251bb81ae8464c4df3b072f84aa969b",
"canvaskit/chromium/canvaskit.js": "901bb9e28fac643b7da75ecfd3339f3f",
"canvaskit/chromium/canvaskit.js.symbols": "ee7e331f7f5bbf5ec937737542112372",
"canvaskit/chromium/canvaskit.wasm": "399e2344480862e2dfa26f12fa5891d7",
"canvaskit/skwasm.js": "5d4f9263ec93efeb022bb14a3881d240",
"canvaskit/skwasm.js.symbols": "c3c05bd50bdf59da8626bbe446ce65a3",
"canvaskit/skwasm.wasm": "4051bfc27ba29bf420d17aa0c3a98bce",
"canvaskit/skwasm.worker.js": "bfb704a6c714a75da9ef320991e88b03",
"favicon.png": "df2da5d63a3e45f6b507edaad4f29556",
"flutter.js": "383e55f7f3cce5be08fcf1f3881f585c",
"flutter_bootstrap.js": "f89fd55279b731beb7269459f352cf67",
"icons/Icon-192.png": "1aceb88b0882b0202a919808830d8e57",
"icons/Icon-512.png": "1ea0b5a1f1cb6470727d9e1592568fa8",
"icons/Icon-maskable-192.png": "1aceb88b0882b0202a919808830d8e57",
"icons/Icon-maskable-512.png": "1ea0b5a1f1cb6470727d9e1592568fa8",
"index.html": "964bb175f29c9d9282117d1d0d46fe9d",
"/": "964bb175f29c9d9282117d1d0d46fe9d",
"main.dart.js": "cec74ee97dc067cbb98a277ade0e01fc",
"manifest.json": "5cd484ff3cfea8901e791d0247f7602a",
"version.json": "2af6b586f7a5caba7183c0e0045f31a3"};
// The application shell files that are downloaded before a service worker can
// start.
const CORE = ["main.dart.js",
"index.html",
"flutter_bootstrap.js",
"assets/AssetManifest.bin.json",
"assets/FontManifest.json"];

// During install, the TEMP cache is populated with the application shell files.
self.addEventListener("install", (event) => {
  self.skipWaiting();
  return event.waitUntil(
    caches.open(TEMP).then((cache) => {
      return cache.addAll(
        CORE.map((value) => new Request(value, {'cache': 'reload'})));
    })
  );
});
// During activate, the cache is populated with the temp files downloaded in
// install. If this service worker is upgrading from one with a saved
// MANIFEST, then use this to retain unchanged resource files.
self.addEventListener("activate", function(event) {
  return event.waitUntil(async function() {
    try {
      var contentCache = await caches.open(CACHE_NAME);
      var tempCache = await caches.open(TEMP);
      var manifestCache = await caches.open(MANIFEST);
      var manifest = await manifestCache.match('manifest');
      // When there is no prior manifest, clear the entire cache.
      if (!manifest) {
        await caches.delete(CACHE_NAME);
        contentCache = await caches.open(CACHE_NAME);
        for (var request of await tempCache.keys()) {
          var response = await tempCache.match(request);
          await contentCache.put(request, response);
        }
        await caches.delete(TEMP);
        // Save the manifest to make future upgrades efficient.
        await manifestCache.put('manifest', new Response(JSON.stringify(RESOURCES)));
        // Claim client to enable caching on first launch
        self.clients.claim();
        return;
      }
      var oldManifest = await manifest.json();
      var origin = self.location.origin;
      for (var request of await contentCache.keys()) {
        var key = request.url.substring(origin.length + 1);
        if (key == "") {
          key = "/";
        }
        // If a resource from the old manifest is not in the new cache, or if
        // the MD5 sum has changed, delete it. Otherwise the resource is left
        // in the cache and can be reused by the new service worker.
        if (!RESOURCES[key] || RESOURCES[key] != oldManifest[key]) {
          await contentCache.delete(request);
        }
      }
      // Populate the cache with the app shell TEMP files, potentially overwriting
      // cache files preserved above.
      for (var request of await tempCache.keys()) {
        var response = await tempCache.match(request);
        await contentCache.put(request, response);
      }
      await caches.delete(TEMP);
      // Save the manifest to make future upgrades efficient.
      await manifestCache.put('manifest', new Response(JSON.stringify(RESOURCES)));
      // Claim client to enable caching on first launch
      self.clients.claim();
      return;
    } catch (err) {
      // On an unhandled exception the state of the cache cannot be guaranteed.
      console.error('Failed to upgrade service worker: ' + err);
      await caches.delete(CACHE_NAME);
      await caches.delete(TEMP);
      await caches.delete(MANIFEST);
    }
  }());
});
// The fetch handler redirects requests for RESOURCE files to the service
// worker cache.
self.addEventListener("fetch", (event) => {
  if (event.request.method !== 'GET') {
    return;
  }
  var origin = self.location.origin;
  var key = event.request.url.substring(origin.length + 1);
  // Redirect URLs to the index.html
  if (key.indexOf('?v=') != -1) {
    key = key.split('?v=')[0];
  }
  if (event.request.url == origin || event.request.url.startsWith(origin + '/#') || key == '') {
    key = '/';
  }
  // If the URL is not the RESOURCE list then return to signal that the
  // browser should take over.
  if (!RESOURCES[key]) {
    return;
  }
  // If the URL is the index.html, perform an online-first request.
  if (key == '/') {
    return onlineFirst(event);
  }
  event.respondWith(caches.open(CACHE_NAME)
    .then((cache) =>  {
      return cache.match(event.request).then((response) => {
        // Either respond with the cached resource, or perform a fetch and
        // lazily populate the cache only if the resource was successfully fetched.
        return response || fetch(event.request).then((response) => {
          if (response && Boolean(response.ok)) {
            cache.put(event.request, response.clone());
          }
          return response;
        });
      })
    })
  );
});
self.addEventListener('message', (event) => {
  // SkipWaiting can be used to immediately activate a waiting service worker.
  // This will also require a page refresh triggered by the main worker.
  if (event.data === 'skipWaiting') {
    self.skipWaiting();
    return;
  }
  if (event.data === 'downloadOffline') {
    downloadOffline();
    return;
  }
});
// Download offline will check the RESOURCES for all files not in the cache
// and populate them.
async function downloadOffline() {
  var resources = [];
  var contentCache = await caches.open(CACHE_NAME);
  var currentContent = {};
  for (var request of await contentCache.keys()) {
    var key = request.url.substring(origin.length + 1);
    if (key == "") {
      key = "/";
    }
    currentContent[key] = true;
  }
  for (var resourceKey of Object.keys(RESOURCES)) {
    if (!currentContent[resourceKey]) {
      resources.push(resourceKey);
    }
  }
  return contentCache.addAll(resources);
}
// Attempt to download the resource online before falling back to
// the offline cache.
function onlineFirst(event) {
  return event.respondWith(
    fetch(event.request).then((response) => {
      return caches.open(CACHE_NAME).then((cache) => {
        cache.put(event.request, response.clone());
        return response;
      });
    }).catch((error) => {
      return caches.open(CACHE_NAME).then((cache) => {
        return cache.match(event.request).then((response) => {
          if (response != null) {
            return response;
          }
          throw error;
        });
      });
    })
  );
}
