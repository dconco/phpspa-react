<?php

use function PhpSPA\Http\Redirect;
use PhpSPA\Core\Http\HttpRequest;

// ────────────── CONFIGURATION ──────────────

$config = [
   'mode' => 'development', // 'development' or 'production'

   // --- Use built frontend files from the PHP public directory (PRODUCTION ONLY). ---
   'assets_url' => '/dist/',
   'dist_dir' => __DIR__ . '/public/dist',
   'manifest_file' => __DIR__ . '/public/dist/manifest.json',
   'manifest_entry' => 'src/main.tsx',

   // --- Vite dev server URL (DEVELOPMENT ONLY). ---
   'dev_server_url' => 'http://localhost:5173',
   'dev_url_base' => '/@dev-server'
];




// ────────────── PRODUCTION (ATTACH BUILT ASSETS) ──────────────

if ($config['mode'] === 'production') {
   $manifest = json_decode(file_get_contents($config['manifest_file']), true);

   $entry = $manifest[$config['manifest_entry']];
   $mainJS = $config['assets_url'] . $entry['file'];

   $cssFiles = array_map(fn($css) => [
      'content' => $config['assets_url'] . $css
   ], $entry['css'] ?? []);

   return [
      'scripts' => [[
         'type' => 'module',
         'content' => $mainJS,
      ]],
      'links' => $cssFiles
   ];
}





// ────────────── DEVELOPMENT (HANDLE SERVING ASSETS) ──────────────

// Handle Vite asset requests in development
$devServerUrl = $config['dev_server_url'];
$urlBase = trim($config['dev_url_base'], '/');

$requestPath = ltrim(new HttpRequest()->getUri(), '/');

if (strpos($requestPath, $urlBase) === 0) {
   Redirect("$devServerUrl/$requestPath");
}




// In development, we load the frontend directly from the Vite dev server.


return [
   'scripts' => [
      [
         'type' => 'module',
         'content' => "$devServerUrl/$urlBase/@vite/preamble",
      ],
      [
         'type' => 'module',
         'content' => "$devServerUrl/$urlBase/@vite/client",
      ],
      [
         'type' => 'module',
         'content' => "$devServerUrl/$urlBase/src/main.tsx",
      ]
   ],
   'links' => [],
];
