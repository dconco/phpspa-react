<?php

// ────────────── CONFIGURATION ──────────────

$config = [
   'mode' => 'production', // 'development' or 'production'

   // --- Use built frontend files from the PHP public directory (PRODUCTION ONLY). ---
   'assets_url' => '/dist/',
   'dist_dir' => __DIR__ . '/public/dist',
   'manifest_file' => __DIR__ . '/public/dist/.vite/manifest.json',
   'manifest_entry' => 'src/main.tsx',

   // --- Vite dev server URL (DEVELOPMENT ONLY). ---
   'dev_server_url' => 'http://localhost:5173',
];


// ────────────── IMPLEMENTATION ──────────────

if ($config['mode'] === 'production') {
   $manifest = json_decode(file_get_contents($config['manifest_file']), true);

   $entry = $manifest[$config['manifest_entry']];
   $mainJS = $config['assets_url'] . $entry['file'];

   $cssFiles = array_map(fn($css) => [
      'content' => $config['assets_url'] . $css,
      'name' => basename($css),
   ], $entry['css'] ?? []);

   return [
      'scripts' => [[
         'type' => 'module',
         'name' => 'main_prod.tsx',
         'content' => $mainJS,
      ]],
      'links' => $cssFiles
   ];
}

// In development, we load the frontend directly from the Vite dev server.
return [
   'scripts' => [
      [
         'type' => 'module',
         'name' => 'react-refresh',
         'content' => fn() => <<<JS
               import RefreshRuntime from "{$config['dev_server_url']}/@react-refresh"
               RefreshRuntime.injectIntoGlobalHook(window)
               window.\$RefreshReg\$ = () => {}
               window.\$RefreshSig\$ = () => (type) => type
               window.__vite_plugin_react_preamble_installed__ = true
         JS,
      ],
      [
         'type' => 'module',
         'name' => 'vite-client',
         'content' => "{$config['dev_server_url']}/@vite/client",
      ],
      [
         'type' => 'module',
         'name' => 'main_dev.tsx',
         'content' => "{$config['dev_server_url']}/src/main.tsx",
      ]
   ],
   'styles' => [],
];
