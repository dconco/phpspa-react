<?php

declare(strict_types=1);

$base = dirname(__DIR__);

use PhpSPA\App;

require_once "$base/vendor/autoload.php";

$config = require "$base/config.php";


$app = new App(require "$base/app/layout.php")
   ->attach(require "$base/app/profile.php")
   ->attach(require "$base/app/default.php")
   ->useModule()
   ->defaultTargetID('app');

for ($i = 0; $i < count($config['scripts']); $i++) { 
   $app->script(...$config['scripts'][$i]);
}

for ($i = 0; $i < count($config['links']); $i++) { 
   $app->link(...$config['links'][$i]);
}

$app->run();
