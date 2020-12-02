# KADAM PHP-SDK 

## Overview

* [Installation](#install)
* Campaigns
    * [Create Campaign](#campaign-create)
    * [Update Campaign](#campaign-update)
    * Targeting
        * [Ages](#target-ages)
        * [Browsers](#target-browsers)
        * [Platforms](#target-platforms)
        * [Devices](#target-devices)
        * [Languages](#target-langs)
        * [Countries](#target-countries)
        * [Regions](#target-regions)
        * [Cities](#target-cities)

## <a name="install"></a> Installation

### Step 1

```
composer require kadam/php-sdk
```

### Step 2

```php
require_once __DIR__.'vendor/autoload.php';

$appId = 10;
$secretKey = 'you_secret_key';

$kadamApi = new \kadam\KadamApi($appId, $secretKey);
```

## <a name="campaign-create"></a> Create Campaign

```php
/** @var \kadam\KadamApi $kadamApi */
$id = $kadamApi->createCampaign([
    'app_id' => $appId,
    'client_id' => $appId,
    'ad_format' => 10, // teaser
    'cost_type' => 0, // cpc
    'name' => 'Ads campaign',
    'link_url' => 'https://darkfriend.ru',
    'real_url' => 'https://darkfriend.ru',
    'age' => '1,2,4',
    'sex' => 3,
    'tags' => ['key1', 'key2', 'key3'],
    'geoExclude' => 0,
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
```

## <a name="campaign-update"></a> Update Campaign

```php
$campaignId = 1;
/** @var \kadam\KadamApi $kadamApi */
$id = $kadamApi->updateCampaign($campaignId, [
    'name' => 'Ads campaign update',
    'geoExclude' => 0,
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