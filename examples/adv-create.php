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
$campaignId = 10;

$id = $kadamApi->createMaterial(
    $campaignId,
    30, // push
    [
        'title' => 'title ads',
        'text' => 'text ads',
        'linkUrl' => 'http://darkfriend.ru',
    ]
);
var_dump($id);