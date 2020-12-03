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
$campaignId = 1;

$id = $kadamApi->updateCampaign($campaignId, [
    'name' => 'Ads campaign update',
    'geoExclude' => 1,
    'langTarget' => [0,1,2,3,4,5,6,7,8,9,10],
    'cityTarget' => [5819],
    'countryTarget' => [
        187 => [
            'bid' => 0.2,
            'leadCost' => 0.8,
        ],
        83 => [
            'bid' => 0.5,
            'leadCost' => 1,
        ],
        20 => [
            'bid' => 0.5,
            'leadCost' => 1,
        ],
    ],
    'regionTarget' => [50360],
]);

var_dump($id);