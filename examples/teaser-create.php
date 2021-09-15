<?php

require_once __DIR__ . '/../vendor/autoload.php';

$config = include __DIR__ . '/config.php';

$kadamApi = new \kadam\KadamApi($config['appId'], $config['secretKey']);

$campaignId = 1;

$id = $kadamApi->createMaterial(
    $campaignId,
    10, // teaser
    [
        'title' => 'Test teaser',
        'linkMedia' => file_get_contents('https://example.ru/img/picture.jpg'),
    ]
);

var_dump($id);