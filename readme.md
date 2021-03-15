# KADAM PHP-SDK 

## Overview

* [Installation](#install)
* Campaigns
    * [Create Campaign](#campaign-create)
    * [Update Campaign](#campaign-update)
    * [Toggle Campaign State](#campaign-update-state)
    * [Stats Campaign](#campaign-stats)
    * [Campaign Placement Stats](#campaign-placement-stats)
    * Targeting
        * [Ages](#target-ages)
        * [Browsers](#target-browsers)
        * [Platforms](#target-platforms)
        * [Devices](#target-devices)
        * [Languages](#target-langs)
        * [Countries](#target-countries)
        * [Regions](#target-regions)
        * [Cities](#target-cities)
* Advertisements
    * [Create Advertisement (ex.Teaser)](#adv-create)
    * [Update Advertisement (ex.Teaser)](#adv-update)
    * [Archive Advertisements Enable/Disable](#adv-archive)
    * [Update Advertisements State](#adv-state)
* Creatives
    * [Creative Stats](#creative-stats)
* Images
    * [Upload Image](#upload-image)
* Materials
    * [Banner Sizes](#banner-sizes)
* [Examples](./examples)
* [Changelog](./CHANGELOG.md)

## <a name="install"></a> Installation

### Step 1

```
composer require kadam/php-sdk
```

### Step 2

```php
require_once __DIR__.'/vendor/autoload.php';

$appId = 10;
$secretKey = 'you_secret_key';

$kadamApi = new \kadam\KadamApi($appId, $secretKey);
```

## <a name="campaign-create"></a> Create Campaign

```php
/** @var \kadam\KadamApi $kadamApi */
$id = $kadamApi->createCampaign([
    'ad_format' => 10, // teaser
    'cost_type' => 0, // cpc
    'name' => 'Ads campaign',
    'linkUrl' => 'https://darkfriend.ru',
    'realUrl' => 'https://darkfriend.ru',
    'age' => '1,2,4',
    'gender' => 3,
    'tags' => ['key1', 'key2', 'key3'],
    'geoExclude' => 0,
    'langs' => [0,1,2,3,4,5,6,7,8,9,10],
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
```

## <a name="campaign-update"></a> Update Campaign

```php
$campaignId = 1;
/** @var \kadam\KadamApi $kadamApi */
$id = $kadamApi->updateCampaign($campaignId, [
    'name' => 'Ads campaign update',
    'geoExclude' => 0,
    'langs' => [0,1,2,3,4,5,6,7,8,9,10],
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
```

## <a name="campaign-stats"></a> Stats Campaign

```php
$campaignId = [1];
/** @var \kadam\KadamApi $kadamApi */
$stats = $kadamApi->getCampaignStats(
    $campaignId,
    ['date','campaign'],
    '2020-01-01',
    '2020-02-01'
);
var_dump($stats);
```

## <a name="campaign-placement-stats"></a> Campaign Placement Stats

```php
$campaignId = [1];
/** @var \kadam\KadamApi $kadamApi */
$stats = $kadamApi->getCampaignPlacementStats(
    $campaignId,
    '2020-01-01',
    '2020-02-01'
);
var_dump($stats);
```


## <a name="target-ages"></a> Ages target

```php
/** @var \kadam\KadamApi $kadamApi */
$ages = $kadamApi->getAges();
var_dump($ages);
```

### result
```
array(2) {
  ["count"]=>int
  ["items"]=>
  array {
    [0]=>
    array(2) {
      ["id"]=> int
      ["title"] => string
    }
    [1]=>
    array(2) {
      ["id"]=>int
      ["title"]=>string
    }
    ....
  }
}
```

## <a name="target-browsers"></a> Browsers target

```php
/** @var \kadam\KadamApi $kadamApi */
$browsers = $kadamApi->getBrowsers();
var_dump($browsers);
```

### result
```
array(2) {
  ["count"]=>int
  ["items"]=>
  array {
    [0]=>
    array(2) {
      ["id"]=> int
      ["title"] => string
    }
    [1]=>
    array(2) {
      ["id"]=>int
      ["title"]=>string
    }
    ....
  }
}
```

## <a name="target-platforms"></a> Platforms target

```php
/** @var \kadam\KadamApi $kadamApi */
$platforms = $kadamApi->getPlatforms();
var_dump($platforms);
```

### result
```
array(2) {
  ["count"]=>int
  ["items"]=>
  array {
    [0]=>
    array(2) {
      ["id"]=> int
      ["title"] => string
    }
    [1]=>
    array(2) {
      ["id"]=>int
      ["title"]=>string
    }
    ....
  }
}
```

## <a name="target-langs"></a> Languages target

```php
/** @var \kadam\KadamApi $kadamApi */
$langs = $kadamApi->getLangs();
var_dump($langs);
```

### result
```
array(2) {
  ["count"]=>int
  ["items"]=>
  array {
    [0]=>
    array(2) {
      ["id"]=> int
      ["title"] => string
    }
    [1]=>
    array(2) {
      ["id"]=>int
      ["title"]=>string
    }
    ....
  }
}
```

## <a name="target-devices"></a> Devices target

```php
/** @var \kadam\KadamApi $kadamApi */
$devices = $kadamApi->getDevices();
var_dump($devices);
```

### result
```
array(2) {
  ["count"]=>int
  ["items"]=>
  array {
    [0]=>
    array(2) {
      ["id"] => int
      ["title"] => string
    }
    [1]=>
    array(2) {
      ["id"]=>int
      ["title"]=>string
    }
    ....
  }
}
```

## <a name="target-countries"></a> Countries target

```php
/** @var \kadam\KadamApi $kadamApi */
$countries = $kadamApi->getCountries();
var_dump($countries);
```

### result
```
array(2) {
  ["count"]=>int
  ["items"]=>
  array {
    [0]=>
    array(2) {
      ["id"] => int
      ["title"] => string
    }
    [1]=>
    array(2) {
      ["id"]=>int
      ["title"]=>string
    }
    ....
  }
}
```

## <a name="target-regions"></a> Regions target

```php
/** @var \kadam\KadamApi $kadamApi */
$regions = $kadamApi->getRegions();
var_dump($regions);
```

### result
```
array(2) {
  ["count"]=>int
  ["items"]=>
  array {
    [0]=>
    array(2) {
      ["id"] => int
      ["title"] => string
      ["countryId"] => int
      ["subdivisionId"] => int
    }
    [1]=>
    array(2) {
      ["id"]=>int
      ["title"]=>string
      ["countryId"] => int
      ["subdivisionId"] => int
    }
    ....
  }
}
```

## <a name="target-cities"></a> Cities target

```php
/** @var \kadam\KadamApi $kadamApi */
$cities = $kadamApi->getCities();
var_dump($cities);
```

### result
```
array(2) {
  ["count"]=>int
  ["items"]=>
  array {
    [0]=>
    array(2) {
      ["id"] => int
      ["title"] => string
      ["countryId"] => int
      ["subdivisionId"] => int
    }
    [1]=>
    array(2) {
      ["id"]=>int
      ["title"]=>string
      ["countryId"] => int
      ["subdivisionId"] => int
    }
    ....
  }
}
```

## <a name="adv-create"></a> Create Teaser

```php
$campaignId = 1;
$link = 'https://darkfriend.ru/img/darkfriend.jpg';

/** @var \kadam\KadamApi $kadamApi */
$id = $kadamApi->createMaterial($campaignId, 10, [
    'title' => 'Test teaser',
    'linkUrl' => 'https://darkfriend.ru', // schema and host as in campaign
    'linkMedia' => \file_get_contents($link),
//    'linkMediaRect' => $linkRect, // rectangle picture
    'bids' => [
        187 => [
            'bid' => 0.2,
        ],
        83 => [
            'bid' => 0.5,
        ],
    ],
//    'categories' => [1,2,3],
]);
var_dump($id);
```

## <a name="adv-update"></a> Update Teaser

```php
$materialId = 1;

/** @var \kadam\KadamApi $kadamApi */
$result = $kadamApi->updateMaterial($materialId, [
    'title' => 'New Title for teaser',
    'bids' => [
        187 => [
            'bid' => 0.3,
        ],
        83 => [
            'bid' => 0.4,
        ],
    ],
//    'categories' => [1,2,3],
]);
var_dump($result);
```

## <a name="adv-archive"></a> Archive Advertisements Enable/Disable

```php
$materialId = [1];
/** @var \kadam\KadamApi $kadamApi */
$result = $kadamApi->archiveAdvertisements($materialId);
var_dump($result);
```

## <a name="adv-state"></a> Update Advertisements State

```php
$materialId = 1;
/** @var \kadam\KadamApi $kadamApi */
$id = $kadamApi->toggleAdvertisementState(
    $materialId,
    10
);
var_dump($id);
```

## <a name="banner-sizes"></a> Banner Sizes

```php
/** @var \kadam\KadamApi $kadamApi */
$sizes = $kadamApi->getBannerSizes();
var_dump($sizes);
```

### result
```
array(2) {
  ["count"]=>int
  ["items"]=>
  array {
    [0]=>
    array(2) {
      ["id"] => int
      ["title"] => string
    }
    [1]=>
    array(2) {
      ["id"]=>int
      ["title"]=>string
    }
    ....
  }
}
```

## <a name="creative-stats"></a> Creative Stats

```php
$campaignIds = [1];
$creativeIds = [1];
/** @var \kadam\KadamApi $kadamApi */
$stats = $kadamApi->getCreativeStats(
    $campaignIds,
    $creativeIds,
    ['date', 'creative'],
    '2020-01-01',
    '2020-02-01'
);
var_dump($stats);
```

## <a name="creative-stats"></a> Upload Image

```php
$url = 'http://site.ru/image.jpg';
// or
// $url = file_get_contents('http://site.ru/image.jpg');
// or 
// $url = file_get_contents(__DIR__.'/image.jpg');
$adType = 10;
/** @var \kadam\KadamApi $kadamApi */
$image = $kadamApi->uploadImage($url,$adType);
var_dump($image);
```
### result
```
https//kadam.net/path-to-file/file.extension
```
