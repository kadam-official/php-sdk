<?php
/**
 * Created by PhpStorm.
 * User: darkfriend <hi@darkfriend.ru>
 * Date: 19.01.2021
 * Time: 17:47
 */

require_once __DIR__ . '/../vendor/autoload.php';

$config = include __DIR__ . '/config.php';

$kadamApi = new \kadam\KadamApi($config['appId'], $config['secretKey']);

$campaignId = 1;

$id = $kadamApi->createMaterial(
    $campaignId,
    10, // teaser
    [
        'title' => 'Тестовый тизер',
        'linkMedia' => file_get_contents(__DIR__.'/darkfriend.jpg'),
    ]
);

var_dump($id);