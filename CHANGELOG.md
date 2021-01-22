# Version 1.4.2

* deprecate categories stakes for methods `ads.materials.put `and `ads.materials.post`
* added support parameter `bids` for stakes in `createMaterial `and `updateMaterial` (also in `createAdvertisement` and `updateAdvertisement`)
* added deprecate flag for method `createAdvertisement`, use `createMaterial`
* added deprecate flag for method `updateAdvertisement` use `updateMaterial`
* added new method `createMaterial`
* added new method `updateMaterial`

# Version 1.4.0

* [now available as a package `kadam/php-sdk` and new initialize](#initial)
* added readme
* [added new methods](#new-methods)
* [improved current methods](#improved)
* improved support php7
* improved upload image

## <a name="initial"></a> initial

### before
```php
$kadamApi = new KadamApi($appId, $secretKey);
```
### after
#### install
```
composer require kadam/php-sdk
```
#### update
```
composer update kadam/php-sdk 
```
#### use
```php
$kadamApi = new \kadam\KadamApi($appId, $secretKey);
```

## <a name="improved"></a> improved current methods

### before
```php
public function createCampaign($type, $cpType, $learningPayMode, $cpaMode, $leadCost, $name, $geo, $tags, $categories, $url, $gender, $age, $platform, $browser, $sectionID, $learning = true, $folders = [], $black = [], $white = []) {}
```
### after
```php
/**
     * create campaign
     *
     * @param array{
     *     type:integer, // mandatory, advertise format (10 - teaser, 20 - banner, 30 - push, 40 - clickunder, 70 - video)
     *     cpType:integer, // integer mandatory, cost type (0 - cpc, 2 - cpm)
     *     cpaMode:integer, // mandatory (10 - Postback URL, 20 - Javascript)
     *     payMode:integer, // mandatory when costType=0
     *     pushType:integer|integer[], // mandatory when type=30 (1 - )
     *     learningPayMode:integer, // (10 - cpm, 20 - cpc)
     *     leadCost:integer, // mandatory
     *     name:string, // mandatory
     *     countries:array, // mandatory
     *     regions:string|integer[],
     *     cities:string|integer[],
     *     geoExclude:integer, // 1 or 0 (default 0)
     *     tags:string|string[], //  mandatory
     *     categories:string|integer[], //  mandatory
     *     url:string, // mandatory or use realUrl with linkUrl
     *     realUrl:string, // mandatory if empty url
     *     linkUrl:string, // mandatory if empty url
     *     gender:string|integer[], // mandatory
     *     age:string|integer[], // mandatory
     *     platform:string|integer[],
     *     browser:string|integer[],
     *     categories:string|integer[], // mandatory
     *     learning:bool,
     *     folders:string|integer[],
     *     black:string|integer[],
     *     white:string|integer[],
     *     devices:string|integer[],
     *     langs:string|integer[],
     *     sectionId:integer,
     * } $fields
     *
     * @return array|mixed
     * @throws \Exception
     */
    public function createCampaign($fields) {}
```
### Attention! _(for create campaign and update)_
* geo is deprecated, use countries (with stakes) and now mandatory parameter
* `$sectionID` rename `sectionId`
* regions now only regions
* added support devices target
* added support langs target
* added support geoExclude parameter
* added support countries target
* added support cities target
* categories no longer support stakes
* added support `array pushType` when type=30 (push)
* also added support any parameter when support api

## <a name="new-methods"></a> new methods

* getCampaign - one capmpaign by id
* getBannerSizes - list support banner size
* getAges - list support ages for target
* getBrowsers - list support browsers for target
* getPlatforms - list support platforms for target
* getLangs - list support languages for target
* getDevices - list support devices for target
* getCountries - list support countries for target
* getRegions - list support regions for target
* getCities - list support cities for target