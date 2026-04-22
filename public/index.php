<?php

declare(strict_types=1);

$base = dirname(__DIR__);

require_once "$base/vendor/autoload.php";

use PhpSPA\App;
use PhpSPA\Component;

$config = require "$base/config.php";



$app = new App(require "$base/app/layout/layout.php")
   ->attach(new Component(fn() => '<h1>Hi</h1>'));

for ($i = 0; $i < count($config['scripts']); $i++) { 
   $app->script(...$config['scripts'][$i]);
}

for ($i = 0; $i < count($config['links']); $i++) { 
   $app->link(...$config['links'][$i]);
}


$app->run();
