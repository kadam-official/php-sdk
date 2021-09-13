<?php

require_once __DIR__.'/../vendor/autoload.php';

$config = include __DIR__.'/config.php';

$kadamApi = new \kadam\KadamApi($config['appId'], $config['secretKey']);
$campaignId = [1, 10];

$stats = $kadamApi->getCampaignStats(
    $campaignId,
    ['date','campaign'],
    '2020-01-01',
    '2020-02-01'
);

var_dump($stats);