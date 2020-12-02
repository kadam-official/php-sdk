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

$id = $kadamApi->createCampaign([
    'app_id' => $appId,
    'client_id' => $appId,
    'type' => 10, // teaser
    'cpType' => 0, // cpc
//    'cpaMode' => 10,
    'name' => 'Ads campaign',
//    'learningPayMode' => 1,
    'leadCost' => 1,
    'realUrl' => 'https://darkfriend.ru',
    'linkUrl' => 'https://darkfriend.ru',
    'age' => '1,2,4',
    'gender' => 3,
    'tags' => ['key1', 'key2', 'key3'],
    'geoExclude' => 0,
    'langs' => [0,1,2,3,4,5,6,7,8,9,10],
    'categories' => [100,122],
    'cities' => [5819],
    'countries' => [
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
    'regions' => [50360],
]);

var_dump($id);