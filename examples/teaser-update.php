<?php

require_once __DIR__ . '/../vendor/autoload.php';

$config = include __DIR__ . '/config.php';

$kadamApi = new \kadam\KadamApi($config['appId'], $config['secretKey']);

$materialId = 1;

$id = $kadamApi->updateMaterial(
    $materialId,
    [
        'title' => 'Update title teaser',
        'linkUrl' => 'https://example.ru',
    ]
);

var_dump($id);