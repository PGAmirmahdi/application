'use strict';
const MANIFEST = 'flutter-app-manifest';
const TEMP = 'flutter-temp-cache';
const CACHE_NAME = 'flutter-app-cache';

const RESOURCES = {".git/COMMIT_EDITMSG": "1b833a33495f28ef4822551efad5048b",
".git/config": "e1cdde207c104c90acd8522ecbc06943",
".git/description": "a0a7c3fff21f2aea3cfa1d0316dd816c",
".git/HEAD": "4cf2d64e44205fe628ddd534e1151b58",
".git/hooks/applypatch-msg.sample": "ce562e08d8098926a3862fc6e7905199",
".git/hooks/commit-msg.sample": "579a3c1e12a1e74a98169175fb913012",
".git/hooks/fsmonitor-watchman.sample": "a0b2633a2c8e97501610bd3f73da66fc",
".git/hooks/post-update.sample": "2b7ea5cee3c49ff53d41e00785eb974c",
".git/hooks/pre-applypatch.sample": "054f9ffb8bfe04a599751cc757226dda",
".git/hooks/pre-commit.sample": "305eadbbcd6f6d2567e033ad12aabbc4",
".git/hooks/pre-merge-commit.sample": "39cb268e2a85d436b9eb6f47614c3cbc",
".git/hooks/pre-push.sample": "2c642152299a94e05ea26eae11993b13",
".git/hooks/pre-rebase.sample": "56e45f2bcbc8226d2b4200f7c46371bf",
".git/hooks/pre-receive.sample": "2ad18ec82c20af7b5926ed9cea6aeedd",
".git/hooks/prepare-commit-msg.sample": "2b5c047bdb474555e1787db32b2d2fc5",
".git/hooks/push-to-checkout.sample": "c7ab00c7784efeadad3ae9b228d4b4db",
".git/hooks/sendemail-validate.sample": "4d67df3a8d5c98cb8565c07e42be0b04",
".git/hooks/update.sample": "647ae13c682f7827c22f5fc08a03674e",
".git/index": "86198c840a3bf8f7f5c0a4b7bc827a4b",
".git/info/exclude": "036208b4a1ab4a235d75c181e685e5a3",
".git/logs/HEAD": "87001706ff7da274eae3c487c698bbb4",
".git/logs/refs/heads/master": "87001706ff7da274eae3c487c698bbb4",
".git/objects/0b/9fcf3d6c6058acc662279d9d22099086a0c78a": "0f20d8b31472ed851f3506e98bb44282",
".git/objects/0b/ea395d98ca722f1a41220e5294f12a8fae011b": "33aab6ab3ee4635856565b5cac641231",
".git/objects/0e/3e1bcc147533cf8a5391c3bc093fe773a93bfe": "d78d34d6c6680eeecdb58031ce2ab5e0",
".git/objects/0f/c344c7e8b9e32ea1ad91f30ded22556352d7bf": "a8a30f28869f7378465338066f34d80d",
".git/objects/11/1693fadb5297bf6a4160392cda74392d3f9658": "6eb1cbcd0d0c3b08947f884ac83109d5",
".git/objects/18/eb401097242a0ec205d5f8abd29a4c5e09c5a3": "4e08af90d04a082aab5eee741258a1dc",
".git/objects/20/1afe538261bd7f9a38bed0524669398070d046": "82a4d6c731c1d8cdc48bce3ab3c11172",
".git/objects/20/cb2f80169bf29d673844d2bb6a73bc04f3bfb8": "b807949265987310dc442dc3f9f492a2",
".git/objects/22/6787626aa90cfb89cb6b3d2b2a8673da0a8da3": "7d6d3b98a72deddf2fe23ef1ba3096e9",
".git/objects/24/b84a36b93e1316b4cea33a33ce1b1330dc7e30": "ae28b8ca25fd07fd40e8c66a13e134d1",
".git/objects/33/b87b1025138f1bee8aacfe3c1837e08ad40100": "b89bc56d9f49b7df04ed9f6958a313e3",
".git/objects/49/adebdb511c8c293b28db3f6792e5bac28cdc32": "ba6a3971e7f06834fd6ec3844372ce17",
".git/objects/4c/07db2ac28d5874634636e4177e5a6ef93cdcdc": "bb92dc765ac1e5adc92e8341772a36ff",
".git/objects/4c/1c9bc0def6dfeffce4d8adaaa44286796d2dad": "30609ab711c750070a33536aad445f77",
".git/objects/4c/fb32e578cb2ad5ea02330d0aa8fe986bc6d5f4": "2487cdc11049028d0e6d27727a1acd0e",
".git/objects/4e/4826193da5372070906d34995c838be96dda19": "7112fe859f3d946399d95381960d9f2a",
".git/objects/4e/c3ca4dcabc59d9ee0b24dabb91fdb97c5b5b6e": "5f6ec64362cc54b3b2446831b49a23d6",
".git/objects/53/91d48f5f848d9514ad090463607243a661d928": "a5e4381afb52fb985a411d4249334268",
".git/objects/53/b9ca4d4bd54884861048329a950833d450224e": "0d355cbcb2a2baa3be10c1e0d0f8ef2e",
".git/objects/55/a68d9d09bd6b39b9db8712296f8d7fc62b0ebb": "40415baa9e194a881d715452c09a8271",
".git/objects/58/356635d1dc89f2ed71c73cf27d5eaf97d956cd": "f61f92e39b9805320d2895056208c1b7",
".git/objects/58/b007afeab6938f7283db26299ce2de9475d842": "6c6cbea527763bb3cdff2cecfee91721",
".git/objects/62/c89ee094658c7a9465824fdb42793a64ea557b": "133cd5da638f245b079d9e9cdc29ae38",
".git/objects/65/718681dfa4ff1edd35537c6d582caea74c7c54": "2283480d35f93e8daa61f0c1b54cce27",
".git/objects/68/45e089ce1c79fe0808975d47a4120e19d8760f": "eb60c3a8d0c70794c7e4e222936812dc",
".git/objects/68/ea773eb0ed50399978fd58fc23b36e3db02a51": "f413462d645b10ead341893d9166d913",
".git/objects/71/3f932c591e8f661aa4a8e54c32c196262fd574": "66c6c54fbdf71902cb7321617d5fa33c",
".git/objects/71/c708bd8e587802c29c7bba51b9e938854841c6": "8b80f5f832b51da4a2d54d616facb91e",
".git/objects/76/159f8f8e46cb2ef2a32045f299fcf9803467cc": "323958da39c8d96a66407cde4df8b613",
".git/objects/77/f4b43fcfee25b91b35e9199964bedaff06090f": "7751d5d0db672dfe774012235679520a",
".git/objects/7a/6a39b571698fcaabaf0c8f3108d2d7bd8f038e": "0cb28e40275e81bf328ceb77e473709a",
".git/objects/7e/2751ee3c8ebfde513b5a401c4cc9ac3bf996a1": "aa9fd9bd8e73e1009a08ab28d90024f4",
".git/objects/7e/76d16128c09f4eb9b3987c5a4b382d9201c0a8": "63c2d3dd9e8cc0bcd6a3c1488b5adebf",
".git/objects/7f/935b9dd23e3a6714b829541c4bff50acffe499": "86e368f47e3a80a5666d18a43bfd0eb4",
".git/objects/80/edaf80746e69a3f6419cb3cad90c57ce88dd3d": "2efa57f36bc8cfeb8114ba2779bb3d06",
".git/objects/86/03d0a3d2a91580f77171968c7d13e73fd1482a": "dc750bd17c929d834d260dd7dc0293e7",
".git/objects/86/f5b80124219a9918c556f9c9665fcd03f7ab99": "f71b51344d0297d9615cee0b4ca4e77d",
".git/objects/88/75b4040365ba6428fe9e5b621a2cd8aae6c142": "9bd7aa5127c70a5ea1725de01e58f83f",
".git/objects/8b/f9c66bb145aa3d739fbc06951e1ab3c2e981bd": "7cec059126b3d067a34477c6f26d0280",
".git/objects/8c/dc566aba395850386644192c3ca79ec887ef9a": "0644f02e2c968280f10350dfef2d4d56",
".git/objects/8e/e974025bc5b6eea513c8456e2a0ddeed6ba471": "eb5456efc3a6305b8b19a6670e2c7fc2",
".git/objects/8f/3c083c44e65f17405165993fef0b00fe30968a": "1ab64cbbef2fefb1395fb8f2d6126980",
".git/objects/90/daf22e0b81722ca95cebc4b917dd91fabb7acf": "31509dd8394fae86f47c764b3e126eb6",
".git/objects/94/7f97daf2b8876a8f43b748706f0dc95694fb68": "c0fad8856d4db994cfcad0ec2bad0099",
".git/objects/94/f7d06e926d627b554eb130e3c3522a941d670a": "77a772baf4c39f0a3a9e45f3e4b285bb",
".git/objects/a6/59e00463e0ed62a497a207126495512bf62fee": "1b9c3a5403c57bd6db85d23b5ef8741a",
".git/objects/a9/91f51138ffe059d588003dc7936aff059a0428": "b73a35563fa129bd884d8b5c53ee9231",
".git/objects/a9/de1d9934ff75ba4ad3ce1356801563796bd328": "39b6de6f0fb859e25d3519be9687fa8b",
".git/objects/ab/ccc020df7efb084ce7ea802b83c0413416162c": "898d573e0439288da61f33549c959cd8",
".git/objects/ad/2f90b78480aeb6608226d9db4ecbd3ec06aaeb": "4add97a99941aa0e9eed0f2173416ade",
".git/objects/ad/a9f37e36d8d85997491e62d363d5e66327d069": "fab1d7d38a76b9424eff0e2beeb2a14d",
".git/objects/ae/d263098569b99f7fe76fa80b5d2a8cd1584e0a": "be4acb6edcbfd9b0f6b91647ba58fc67",
".git/objects/b1/cc5c554a036b58387db6b5c33522d81f2d9729": "5aaa7cfb09469db11a44067b02367ead",
".git/objects/b3/ebbd38f666d4ffa1a394c5de15582f9d7ca6c0": "23010709b2d5951ca2b3be3dd49f09df",
".git/objects/c3/67e576beb14b6232a0e8cd6404ee36abca5cc1": "25b4f5eea51dd6818f61852f4310ca31",
".git/objects/c6/897d6802711e55599efa0f1502418071a1ebaa": "7a7fde1bddccff2490c5aa1829091acb",
".git/objects/c6/ec8abbab23cd7578b7878f4b48447cadbd3eda": "a153a87c4e96fee1202eca678235069e",
".git/objects/c7/fe069fc662a61ec25e7ae127c296265c038aa1": "9e7875a060322cdd49f6b74d834a6e0e",
".git/objects/c9/bf8af1b92c723b589cc9afadff1013fa0a0213": "632f11e7fee6909d99ecfd9eeab30973",
".git/objects/cc/fab74c1f56c330985060e2247607eaedb3c7d7": "ad5b6117df489509af208438785f208b",
".git/objects/d1/0491d09dd5838bb3c031c14dda18031a894c4d": "59e508255ff2f54d095da0e5bc3d23fb",
".git/objects/d1/098e7588881061719e47766c43f49be0c3e38e": "f17e6af17b09b0874aa518914cfe9d8c",
".git/objects/d4/3532a2348cc9c26053ddb5802f0e5d4b8abc05": "3dad9b209346b1723bb2cc68e7e42a44",
".git/objects/d5/b52f5ea9a2686c79a9b0801223981e2b8381d5": "7997b2bf1bd941ba1a41873f66b8ea6e",
".git/objects/d9/350a65f0df730f6b08d852e568ed49e3ea2be2": "795474b9f0de71ede8d312f557a25a87",
".git/objects/da/acd2c3c35e266e676a1d9cc9d0120e56e7f571": "cea80782c6928b077efe00236786d20e",
".git/objects/e0/7797437d096064bd90c373800dcb0f335c14b0": "16f9b9defb16491f8c733b09b022688c",
".git/objects/e1/4ac864aafee1ace1f28c8d7e97bcc8fb8cace2": "f70c57f7841984ac8c91256012e339a8",
".git/objects/f2/04823a42f2d890f945f70d88b8e2d921c6ae26": "6b47f314ffc35cf6a1ced3208ecc857d",
".git/objects/f4/b71eb5fbed9617cfaac9d8fa007dab85c70659": "12f2f1f15ed3b5222e451c2e90a17dfa",
".git/objects/f9/0b51576bf38c00fd6b6eae3b559c0f999f56b5": "3249dad609419ec0b27f8b9054e72912",
".git/objects/fa/602c488e88caee2a19002cd15448298700a927": "2a1c580061872aa7f627f90b2197ae54",
".git/objects/fe/b923193313be2c33af92df86fc627fc96aa89c": "48d3914d107178d5a36ac94eb3d8b760",
".git/objects/ff/4e863a4d66458da62ea62cf063d678ae9f8244": "77179c5337a46c0bf784853f58bfcfc0",
".git/refs/heads/master": "ead543879754daa8a2fb273314211d33",
"assets/AssetManifest.bin": "046d17afa872dcc1e40be01fefb7c9e5",
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
"flutter_bootstrap.js": "15cba380d133189529ae3cd53ff8032c",
"icons/Icon-192.png": "1aceb88b0882b0202a919808830d8e57",
"icons/Icon-512.png": "1ea0b5a1f1cb6470727d9e1592568fa8",
"icons/Icon-maskable-192.png": "1aceb88b0882b0202a919808830d8e57",
"icons/Icon-maskable-512.png": "1ea0b5a1f1cb6470727d9e1592568fa8",
"index.html": "c49f43c679380bddef21134c0585d5ed",
"/": "c49f43c679380bddef21134c0585d5ed",
"main.dart.js": "0b069a109801f55fce817f85f1e34ae2",
"manifest.json": "5cd484ff3cfea8901e791d0247f7602a",
"version.json": "af42797e750b82b8a831d3ee88c59ee2"};
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
