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

$materialId = 1;

$id = $kadamApi->updateMaterial(
    $materialId,
    [
        'title' => 'Update title teaser',
        'linkUrl' => 'https://darkfriend.ru',
    ]
);

var_dump($id);