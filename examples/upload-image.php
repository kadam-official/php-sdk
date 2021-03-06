<?php
/**
 * Created by PhpStorm.
 * User: darkfriend <hi@darkfriend.ru>
 * Date: 02.12.2020
 * Time: 16:13
 */

require_once __DIR__.'/../vendor/autoload.php';

$config = include __DIR__.'/config.php';

$kadamApi = new \kadam\KadamApi($config['appId'], $config['secretKey']);

$url = 'https://darkfriend.ru/img/darkfriend.jpg';
// or
//$url = file_get_contents('https://darkfriend.ru/img/darkfriend.jpg');
// or
//$url = file_get_contents(__DIR__.'/image.jpg');
$adType = 10;

/** @var \kadam\KadamApi $kadamApi */
$image = $kadamApi->uploadImage($url, $adType);

var_dump($image);