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
$materialId = 1;

$id = $kadamApi->updateAdvertisement(
    $materialId,
    10,
    'Тестовый тизер',
    'Текст для тестового тизера',
    'https://darkfriend.ru',
    __DIR__.'/darkfriend.jpg'
);

var_dump($id);