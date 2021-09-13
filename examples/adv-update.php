<?php

require_once __DIR__.'/../vendor/autoload.php';

$config = include __DIR__.'/config.php';

$kadamApi = new \kadam\KadamApi($config['appId'], $config['secretKey']);
$materialId = 10;

$id = $kadamApi->updateMaterial(
    $materialId,
    [
        'title' => 'title ads',
        'text' => 'text ads',
        'linkUrl' => 'http://example.ru',
    ]
);
var_dump($id);