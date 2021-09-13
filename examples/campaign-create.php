<?php

require_once __DIR__.'/../vendor/autoload.php';

$config = include __DIR__.'/config.php';

$kadamApi = new \kadam\KadamApi($config['appId'], $config['secretKey']);

$id = $kadamApi->createCampaign([
    'type' => 10, // teaser
    'cpType' => 0, // cpc
//    'cpaMode' => 10,
    'name' => 'Ads campaign',
//    'learningPayMode' => 1,
    'leadCost' => 1,
    'realUrl' => 'https://example.ru',
    'linkUrl' => 'https://example.ru',
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
//    'black_list' => ['123', '456'], // if need black list
//    'white_list' => ['321', '654'], // if need  white list
]);

var_dump($id);