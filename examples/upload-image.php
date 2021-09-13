<?php

require_once __DIR__.'/../vendor/autoload.php';

$config = include __DIR__.'/config.php';

$kadamApi = new \kadam\KadamApi($config['appId'], $config['secretKey']);

$url = 'https://example.ru/img/picture.jpg';
// or
//$url = file_get_contents('https://example.ru/img/picture.jpg');
// or
//$url = file_get_contents(__DIR__.'/image.jpg');
$adType = 10;

/** @var \kadam\KadamApi $kadamApi */
$image = $kadamApi->uploadImage($url, $adType);

var_dump($image);