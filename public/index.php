<?php

declare(strict_types=1);

use PhpSPA\App;

$config = require __DIR__ . '/../config.php';



$app = new App(require __DIR__ . '/../app/layout/layout.php');

for ($i = 0; $i < count($config['scripts']); $i++) { 
   $app->script(...$config['scripts'][$i]);
}

for ($i = 0; $i < count($config['links']); $i++) { 
   $app->link(...$config['links'][$i]);
}


$app->run();
