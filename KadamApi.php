<?php

namespace kadam;


use darkfriend\helpers\ArrayHelper;

/**
 * Class KadamApi
 *
 * @version 1.4.2
 * min php7.0
 */
class KadamApi
{
    /**
     * application id
     * @var int
     */
    private $appID;

    /**
     * api client id
     * @var
     */
    private $clientID;

    /**
     * secret key
     * @var string string
     */
    private $secret;

    /**
     * access token
     * @var string
     */
    private $token;

    /**
     * map fields for campaign
     * @var string[]
     */
    protected $mapFieldsCampaign = [
        'type' => 'ad_format',
        'cpType' => 'cost_type',
        'cpaMode' => 'cpa_mode',
        'learningPayMode' => 'learning_pay_mode',
        'leadCost' => 'cost',
        'devices' => 'deviceTarget',
        'gender' => 'sex',
        'countries' => 'countryTarget',
        'regions' => 'regionTarget',
        'cities' => 'cityTarget',
        'platform' => 'platforms',
        'browsers' => 'browsers',
        'black' => 'black_list',
        'white' => 'white_list',
        'categories' => 'categories',
        'sectionId' => 'section_id',
    ];

    /**
     * api scheme
     * @var string
     */
//    const API_URL = 'http://api.kadam-docker-old.sdev.pw/%action%.%method%?%params%';
    const API_URL = 'http://api.kadam.net/%action%.%method%?%params%';

    /**
     * CApi constructor.
     * @param int $appID
     * @param string $secret
     * @throws \Exception
     */
    public function __construct(int $appID, string $secret)
    {
        $this->appID = $this->clientID = $appID;
        $this->secret = $secret;
        $this->auth();
    }

    /**
     * set api client id
     * @param int $clientID
     */
    public function setClientId(int $clientID)
    {
        $this->clientID = $clientID;
    }

    /**
     * process and prepare request url
     * @param $action_and_method
     * @param array $params
     * @param bool $signature
     * @param bool $toString
     * @return mixed
     */
    private function _prepare_url($action_and_method, array $params, $signature = true, $toString=true)
    {
        $params_string = $this->_process_params($params, $signature, $toString);
        $pattern = [
            '/%action%\.%method%/',
            '/%params%/'
        ];
        $replacement = [
            $action_and_method,
            $params_string
        ];

        return \preg_replace($pattern, $replacement, static::API_URL);
    }

    /**
     * sort params
     * @param array $params
     * @param bool $signature
     * @param bool $toString
     * @return string|array
     */
    private function _process_params(array $params, $signature = true, $toString=true)
    {
        // В каждом запросе обязательно должен быть идентификатор прложения
        $params = \array_merge(
            [
                'app_id' => $this->appID,
                'client_id' => $this->clientID,
            ], $params
        );

        // Сортируем параметры
        \ksort($params);

        $url_params = [];
        foreach ($params as $param => $value) {
            // Формируем параметры со значениями
            $url_params[] = $param . '=' . \urlencode($value);
        }

        // Параметры в виде строки
        $params_string = \implode('&', $url_params);
        // Если требуется сигнатура - дополняем строку параметров сигнатурой от строки параметров

        if($toString) {
            $params_string .= $this->_prepare_signature($params_string, $signature);
            return $params_string;
        } else {
            $url_params['signature'] = $this->_prepare_signature($params_string, $signature);
            return $url_params;
        }
    }

    /**
     * add signature param
     * @param string $params_string
     * @param bool $signature
     * @return string
     */
    private function _prepare_signature($params_string = '', $signature = true)
    {
        return $signature ? '&signature=' . \md5($params_string . $this->token) : '';
    }

    /**
     * execute request
     * @param string $url
     * @param bool $post
     * @return array|mixed
     * @throws \Exception
     */
    private function _execute_request($url, $post = false)
    {
        $curl = \curl_init();
        \curl_setopt($curl, \CURLOPT_URL, $url);
        \curl_setopt($curl, \CURLOPT_HEADER, 0);
        \curl_setopt($curl, \CURLOPT_FOLLOWLOCATION, true);
        \curl_setopt($curl, \CURLOPT_RETURNTRANSFER, 1);
        if ($post) {
            \curl_setopt($curl, \CURLOPT_POST, 1);
            \curl_setopt($curl, \CURLOPT_POSTFIELDS, $post);
        }

        $response = \curl_exec($curl);

        if (!$response) {
            throw new \Exception('response == false');
        }

        $json_response = \json_decode($response, true);

        // invalid response
        if (!\is_array($json_response)) {
            throw new \Exception("Response error (not JSON):\n" . \print_r($response, true));
        }

        // error in method
        if (isset($json_response[0]) and \is_array($json_response[0]) and isset($json_response[0]['error'])) {
            throw new \Exception($json_response[0]['error']['msg'] . " (" . $url . ")");
        }

        return $json_response;
    }

    /**
     * get auth token
     * @return $this
     * @throws \Exception
     */
    public function auth()
    {
        $auth_url = $this->_prepare_url('auth.token', ['secret_key' => $this->secret], false);
        $auth = $this->_execute_request($auth_url);
        if (!isset($auth['access_token'])) {
            throw new \Exception('Access denied');
        }
        $this->token = $auth['access_token'];
        return $this;
    }

    /**
     * get campaign
     * @param int $id
     * @param array $params
     * @return array
     * @throws \Exception
     */
    public function getCampaign(int $id, array $params=[]): array
    {
        return $this->getCampaigns(
            $id,
            $params['archive'] ?? false
        )[0] ?? [];
    }

    /**
     * get campaigns list
     * @param string|string[]|null $campaignIds
     * @param bool $archive
     * @param int $limit
     * @param int $offset
     * @param null $type
     * @return mixed
     * @throws \Exception
     */
    public function getCampaigns(
        $campaignIds = null,
        $archive = false,
        $limit = 100,
        $offset = 0,
        $type = null
    ): array
    {
        if (\is_array($campaignIds)) {
            $campaignIds = \implode(',', $campaignIds);
        }

        $urlFilter = [
            'client_id' => $this->appID,
            'include_archive' => \intval($archive),
            'campaign_ids' => $campaignIds,
            'limit' => $limit,
            'offset' => $offset,
            'with_tags' => 1
        ];

        if (!\is_null($type)) {
            $urlFilter['ad_type'] = $type;
        }

        $url = $this->_prepare_url('ads.campaigns.get', $urlFilter);
        $response = $this->_execute_request($url);

        // get response items
        return $response['response']['items'] ?? [];
    }

    /**
     * get campaign material list
     *
     * @param string|string[] $campaignIds - '1,2,4'
     * @param string|string[] $materialIds - '1,2,4'
     * @param bool $archive
     * @param int $limit
     * @param int $offset
     * @param null $type
     * @return array
     * @throws \Exception
     */
    public function getAdvertisements(
        $campaignIds = null,
        $materialIds = null,
        bool $archive = false,
        int $limit = 100,
        int $offset = 0,
        int $type = null
    ): array
    {
        if (\is_array($campaignIds)) {
            $campaignIds = \implode(',', $campaignIds);
        }
        if (\is_array($materialIds)) {
            $materialIds = \implode(',', $materialIds);
        }
        $urlFilter = [
            'client_id' => $this->appID,
            'archive' => (int) $archive,
            'campaign_ids' => $campaignIds,
            'material_ids' => $materialIds,
            'limit' => $limit,
            'offset' => $offset
        ];

        if ($type !== null) {
            $urlFilter['ad_format'] = $type;
        }

        $url = $this->_prepare_url('ads.materials.get', $urlFilter);

        $response = $this->_execute_request($url);

        // get response items
        return $response['response']['items'] ?? [];
    }


    /**
     * delete material list
     *
     * @param $mIds
     * @return mixed
     */
    public function deleteAdvertisements($mIds)
    {
        $urlFilter = [
            'client_id' => $this->appID,
            'ids' => $mIds,
        ];
        $url = $this->_prepare_url('ads.materials.delete', $urlFilter);
        $response = $this->_execute_request($url);
        // get response items
        return $response['response']['items'] ?? [];
    }


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
    public function createCampaign($fields)
    {
        $errorsField = [];
        $requireFields = [
            'type',
            'cpType',
            'leadCost',
            'name',
            'countries',
            'tags',
            'categories',
            'gender',
            'age',
        ];
        foreach ($requireFields as $requireField) {
            if(empty($fields[$requireField]) && $fields[$requireField]!==0) {
                $errorsField[] = $requireField;
            }
        }
        if($errorsField) {
            throw new \Exception('Error: fields required '. \implode(',', $errorsField));
        }

        $campaignData = [
            'client_id' => $this->appID,
            'ad_format' => (int) $fields['type'], //Баннерная
            'cost_type' => (int) $fields['cpType'], // СРA
            'cpa_mode' => $fields['cpaMode'] ?? 10, //postback
            'learning_pay_mode' => $fields['learningPayMode'] ?? 10, //CPC
            'cost' => $fields['leadCost'], // conversion cost
            'name' => $fields['name'],
            'geoExclude' => isset($fields['geoExclude']) ? $fields['geoExclude'] : 0,
        ];

        if($campaignData['ad_format'] === 30 && empty($fields['pushType'])) {
            throw new \Exception("Error: pushType mandatory when type=30");
        }

        if(empty($fields['url'])) {
            if(empty($fields['realUrl']) || empty($fields['linkUrl'])) {
                throw new \Exception("Error: if empty 'url' then mandatory 'realUrl' and 'linkUrl'");
            }
            $campaignData['link_url'] = $fields['linkUrl'];
            $campaignData['real_url'] = $fields['realUrl'];
            unset($fields['linkUrl'], $fields['realUrl']);
        } else {
            $campaignData['link_url'] = $fields['url'];
            $campaignData['real_url'] = $fields['url'];
            unset($fields['url']);
        }

        $excludeFieldsFormatted = [
            'tags',
            'categories',
        ];

        foreach ($fields as $field => $value) {
            if(isset($this->mapFieldsCampaign[$field])) {
                $field = $this->mapFieldsCampaign[$field];
            }
            if(\in_array($field,$excludeFieldsFormatted)) {
                $campaignData[$field] = $value;
            } elseif(!isset($campaignData[$field])) {
                $campaignData[$field] = $this->getValueFormatted($value);
            }
        }

        $url = $this->_prepare_url('ads.campaigns.put', [
            'data' => \json_encode($campaignData)
        ]);

        $urlContents = \explode("?", $url);
        $result = $this->_execute_request($urlContents[0], $urlContents[1]);

        if (empty($result[0]) || !\intval($result[0])) {
            throw new \Exception("Error creating campaign: " . \json_encode($result));
        }

        return $result[0];
    }

    /**
     * get formatted value
     * @param mixed $value
     * @param array $props
     * @return mixed
     */
    protected function getValueFormatted($value, array $props=[])
    {
        if(!$value && $value!==0) {
            $value = '';
        } else {
            if(\is_array($value)) {
                if(!\is_array(\current($value))) {
                    $value = \implode(',', $value);
                } else {
                    $props['format'] = 'array';
                }
            }
        }
        if(empty($props['format'])) {
            $props['format'] = 'string';
        }
        switch ($props['format']) {
            case 'string': return (string) $value;
            case 'integer': return (int) $value;
            case 'float': return (float) $value;
            default: return $value;
        }
    }

    /**
     * enable/disable campaign state
     * @param $campaignID
     * @param $state
     * @return array|mixed
     * @throws \Exception
     * @internal param $id
     */
    public function toggleCampaignState($campaignID, $state)
    {
        $campaign_data = [
            'campaign_id' => $campaignID,
            'status' => \intval($state)
        ];

        $url = $this->_prepare_url(
            'ads.campaigns.update', [
            'data' => \json_encode($campaign_data)
        ]
        );

        $result = $this->_execute_request($url);
        if (empty($result[0]) || !\intval($result[0])) {
            throw new \Exception("Error in campaign $campaignID changing state");
        }
        if (\intval($result[0]) != \intval($campaignID)) {
            throw new \Exception("Error in campaign $campaignID changing state - Mutual campaign $campaignID != {$result[0]}");
        }
        return $result[0];
    }


    /**
     * enable/disable material state
     * @param $materialID
     * @param int $state
     * @return array|mixed
     * @throws \Exception
     */
    public function toggleAdvertisementState($materialID, int $state)
    {
        $material_data = [
            'material_id' => $materialID,
            'status' => \intval($state)
        ];

        $url = $this->_prepare_url(
            'ads.materials.update', [
            'data' => \json_encode($material_data)
        ]
        );

        $result = $this->_execute_request($url);

        if (empty($result[0]['material_id']) || !\intval($result[0]['material_id'])) {
            throw new \Exception("Error in campaign $materialID changing state");
        }
        if (\intval($result[0]['material_id']) != \intval($materialID)) {
            throw new \Exception("Error in campaign $materialID changing state - Mutual campaign $materialID != {$result[0]['material_id']}");
        }
        return $result[0];
    }


    /**
     * enable/disable material state
     * @param array $ids
     * @return array|mixed
     * @throws \Exception
     */
    public function archiveAdvertisements(array $ids = [])
    {
        if (empty($ids)) return [];

        $url = $this->_prepare_url(
            'ads.materials.delete', [
                'ids' => \implode(',', $ids)
            ]
        );

        $result = $this->_execute_request($url);

        // проверяем ответ на наличие
        if (empty($result[0])) {
            throw new \Exception("Error materials (" . \implode(',', $ids) . ") remove result");
        }

        // проверяем ответ на качество
        $errorMsg = '';
        foreach ($result as $materialID => $info) {
            if (!empty($info['error'])) $errorMsg .= $info['error']['msg'] . ' (' . $materialID . ')' . "\n";
        }
        if (!empty($errorMsg)) {
            throw new \Exception($errorMsg);
        }

        return $result[0];
    }


    /**
     * update campaign
     * @param $campaignID
     * @param $fields
     * @return array|mixed
     * @throws \Exception
     */
    public function updateCampaign($campaignID, $fields)
    {
        $campaign_data = [
            'app_id' => $this->appID,
            'client_id' => $this->appID,
            'campaign_id' => $campaignID,
            'status' => 1
        ];

        $campaign_data = \array_merge($campaign_data, $fields);

        $url = $this->_prepare_url(
            'ads.campaigns.update', [
            'data' => \json_encode($campaign_data)
        ]
        );

        $urlContents = \explode("?", $url);
        $result = $this->_execute_request($urlContents[0], $urlContents[1]);

        if (empty($result[0]) || !\intval($result[0])) {
            throw new \Exception("Error updating campaign");
        }
        if (\intval($result[0]) != \intval($campaignID)) {
            throw new \Exception("Error updating campaign - Mutual campaign $campaignID != {$result[0]}");
        }

        return $result[0];
    }

    /**
     * upload image to storage server
     * @param string $link url to file or file content
     * @param int $type 10 - teaser, 20 - banner, 30 - push, 40 - clickunder, 70 - video
     * @return string
     * @throws \Exception
     */
    public function uploadImage(string $link, int $type): string
    {
        // create post data
        $post = [
            "file" => $link,
            'ad_format' => $type,
        ];

        $url = $this->_prepare_url('data.upload.media', $post);

        $urlContents = \explode("?", $url);
        $result = $this->_execute_request($urlContents[0], $urlContents[1]);

        // error upload image
        if (!empty($result['error'])) {
            throw new \Exception($result['error']['msg']);
        }
        if (empty($result['image'])) {
            throw new \Exception('Error upload image');
        }

        return $result['image'];
    }

    /**
     * create material (teaser)
     * @param int $campaignID
     * @param int $type type from campaign (10 - teaser, 20 - banner, 30 - push, 40 - clickunder, 70 - video)
     * @param string $title
     * @param string $text
     * @param string $linkUrl
     * @param string $linkMedia
     * @param int $pauseAfterModerate set pause after moderation 1|0
     * @param int $size
     * @param int $status
     * @param string $linkUrlRect
     * @param int $size_avail
     * @param array $bids
     * @return array|mixed
     * @throws \Exception
     * @deprecated
     * @see createMaterial
     */
    public function createAdvertisement(int $campaignID, int $type, string $title, string $text, string $linkUrl, string $linkMedia='', int $pauseAfterModerate = 0, int $size = null, int $status = 0, string $linkUrlRect = '', int $size_avail = 1, array $bids = []): int
    {
        return $this->createMaterial($campaignID, $type, [
            'title' => $title,
            'text' => $text,
            'status' => $status ?? 0,
            'linkUrl' => $linkUrl,
            'pauseAfterModerate' => $pauseAfterModerate,
            'sizeAvail' => $size_avail,
            'linkMedia' => $linkMedia,
            'linkMediaRect' => $linkUrlRect,
            'size' => $size,
            'bids' => $bids,
        ]);
    }

    /**
     * Create material (teaser|banner|push|clickunder|video)
     * @param int $campaignID
     * @param int $type type from campaign (10 - teaser, 20 - banner, 30 - push, 40 - clickunder, 70 - video)
     * @param array $fields
     * @return int
     * @throws \Exception
     * @since 1.4.2
     */
    public function createMaterial(int $campaignID, int $type, array $fields): int
    {
        if (!(int)$campaignID) {
            throw new \Exception('The campaign can not be empty');
        }

        MaterialValidate::onCreate($type, $fields);

        $data = [
            'client_id' => $this->appID,
            'campaign_id' => $campaignID,
            'type' => $type,
            'title' => $fields['title'],
            'text' => $fields['text'],
            'status' => $fields['status'] ?? 0,
            'link_url' => $fields['linkUrl'],
            'pause_after_moderate' => $fields['pauseAfterModerate'] ?? 0,
            'size_avail' => $fields['sizeAvail'] ?? 1,
        ];

        if (!empty($fields['linkMedia'])) {
            // upload media
            $data['link_media'] = $link = $this->uploadImage($fields['linkMedia'], $type);

            // upload rectangle media
            if (!empty($fields['linkMediaRect'])) {
                $data['link_media_rect'] = $this->uploadImage($fields['linkMediaRect'], $type);
                $data['size_avail'] = 3;
            }
        }

        if ($type == 60) {
            $data['target_domain'] = $data['link_url'];
        }

        if ($type == 70) {
            $data['length_video'] = $fields['size'];
        } elseif (!empty($fields['size'])) {
            $data['size'] = $fields['size'];
        }

        if(!empty($fields['bids'])) {
            $data['bids'] = $fields['bids'];
        }

        $fields = ArrayHelper::removeByKey($fields, [
            'client_id',
            'campaign_id',
            'status',
            'type',
            'title',
            'text',
            'linkUrl',
            'linkMedia',
            'pauseAfterModerate',
            'size',
            'linkMediaRect',
            'sizeAvail',
            'bids',
        ]);

        if($fields) {
            foreach ($fields as $kField => $vField) {
                $data[$kField] = $vField;
            }
        }

        $url = $this->_prepare_url(
            'ads.materials.put', [
                'data' => \json_encode($data)
            ]
        );

        $urlContents = \explode('?', $url);
        $result = $this->_execute_request($urlContents[0], $urlContents[1]);

        if (empty($result[0]['material_id']) || !(int)$result[0]['material_id']) {
            throw new \Exception('Error create advertisement');
        }

        return (int) $result[0]['material_id'];
    }

    /**
     * update material (teaser|banner|push|clickunder|video)
     * @param int $materialID
     * @param int $type
     * @param string $title
     * @param string $text
     * @param string $linkUrl
     * @param string $linkMedia
     * @param int $pauseAfterModerate
     * @param int $size
     * @param null $status
     * @param null $linkUrlRect
     * @param null $size_avail
     * @param array $bids
     * @return array|mixed
     * @throws \Exception
     * @deprecated
     * @see updateMaterial
     */
    public function updateAdvertisement($materialID, $status = null, $type = null, $title = null, $text = null, $linkUrl = null, $linkMedia = null, $pauseAfterModerate = 0, $size = null, $linkUrlRect = null, $size_avail = null, array $bids = [])
    {
        return $this->updateMaterial((int) $materialID, [
            'status' => $status,
            'type' => $type,
            'title' => $title,
            'text' => $text,
            'linkUrl' => $linkUrl,
            'linkMedia' => $linkMedia,
            'pauseAfterModerate' => $pauseAfterModerate,
            'size' => $size,
            'linkUrlRect' => $linkUrlRect,
            'sizeAvail' => $size_avail,
            'bids' => $bids,
        ]);
    }

    /**
     * Update material (teaser|banner|push|clickunder|video)
     * @param int $materialID
     * @param array $fields
     * @return mixed
     * @throws \Exception
     * @since 1.4.2
     */
    public function updateMaterial(int $materialID, array $fields = [])
    {
        if (!$materialID) {
            throw new \Exception("The materialID can not be empty");
        }

        $data = [
            'material_id' => \intval($materialID),
        ];

        if ($fields['pauseAfterModerate']){
            $data['pause_after_moderate'] = $fields['pauseAfterModerate'];
        }

        if (!empty($fields['title'])) $data['title'] = $fields['title'];
        if (!empty($fields['text'])) $data['text'] = $fields['text'];
        if (!empty($fields['sizeAvail'])) $data['size_avail'] = $fields['sizeAvail'];

        // для видео
        if (!empty($fields['type']) && $fields['type'] == 70 && empty($fields['text'])) {
            $data['text'] = $fields['title'];
        }

        if (!empty($fields['linkUrl'])) $data['link_url'] = $fields['linkUrl'];
        if (!empty($fields['linkMedia'])) {
            // upload media
            $data['link_media'] = $this->uploadImage($fields['linkMedia'], $fields['type']);

            // upload rectangle media
            if (!empty($fields['linkUrlRect'])) {
                $data['link_media_rect'] = $this->uploadImage($fields['linkUrlRect'], $fields['type']);
                $data['size_avail'] = 3;
            }
        }

        if(!empty($fields['type'])) {
            if ($fields['type'] == 60) {
                $data['target_domain'] = $data['link_url'];
            }

            if ($fields['type'] == 70) {
                $data['length_video'] = (int) $fields['size'] ?? 0;
            } elseif (isset($fields['size'])) {
                $data['size'] = (int) $fields['size'];
            }
        }

        if (!\is_null($fields['status'])) {
            $data['status'] = $fields['status'];
        }

        $fields = ArrayHelper::removeByKey($fields, [
            'status',
            'type',
            'title',
            'text',
            'linkUrl',
            'linkMedia',
            'pauseAfterModerate',
            'size',
            'linkUrlRect',
            'sizeAvail',
        ]);

        if($fields) {
            foreach ($fields as $kField => $vField) {
                $data[$kField] = $vField;
            }
        }

        $url = $this->_prepare_url(
            'ads.materials.update', [
                'data' => \json_encode($data)
            ]
        );

        $urlContents = \explode("?", $url);
        $result = $this->_execute_request($urlContents[0], $urlContents[1]);

        if (empty($result[0]['material_id']) || !\intval($result[0]['material_id'])) {
            throw new \Exception("Error update advertisement");
        }

        return $result[0]['material_id'];
    }

    /**
     * select stats for campaign creatives
     *
     * @param array $campaignIds
     * @param array $creativeIds - [1,34,90]
     * @param array $group - 'date', 'creative'
     * @param string $dateFrom - '2020-01-01'
     * @param string $dateTo - '2020-01-01'
     * @param array $sort
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws \Exception
     */
    public function getCreativeStats(array $campaignIds, array $creativeIds, array $group, string $dateFrom, string $dateTo, $sort = [], $limit = 200, $offset = 0): array
    {
        $urlFilter = [
            'campaigns' => \implode(',', $campaignIds),
            'creatives' => \implode(',', $creativeIds),
            'group' => \implode(',', $group),
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'limit' => $limit,
            'offset' => $offset,
        ];
        if ($sort) $urlFilter['sort'] = \implode(',', $sort);

        $url = $this->_prepare_url('ads.stats.creative.get', $urlFilter);

        // get response items
        return $this->_execute_request($url);
    }

    /**
     * select stats for campaigns
     *
     * @param array $campaignIds
     * @param array $group - 'date', 'campaign'
     * @param string $dateFrom - '2020-01-01'
     * @param string $dateTo - '2020-01-01'
     * @param array $sort
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws \Exception
     */
    public function getCampaignStats(array $campaignIds, array $group, string $dateFrom, string $dateTo, $sort = [], int $limit = 200, $offset = 0): array
    {
        $urlFilter = [
            'campaigns' => \implode(',', $campaignIds),
            'group' => \implode(',', $group),
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'limit' => $limit,
            'offset' => $offset,
        ];
        if ($sort) $urlFilter['sort'] = \implode(',', $sort);

        $url = $this->_prepare_url('ads.stats.campaign.get', $urlFilter);

        // get response items
        return $this->_execute_request($url);
    }

    /**
     * select stats for campaign placements
     * @param array $campaignIds
     * @param null $dateFrom
     * @param null $dateTo
     * @param array $sort
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws \Exception
     */
    public function getCampaignPlacementStats(array $campaignIds, $dateFrom = null, $dateTo = null, $sort = [], int $limit = 10, $offset = 0): array
    {
        if (!$dateFrom) $dateFrom = \date('Y-m-d');
        if (!$dateTo) $dateTo = \date('Y-m-d');

        $urlFilter = [
            'campaigns' => \implode(',', $campaignIds),
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'limit' => $limit,
            'offset' => $offset
        ];
        if ($sort) $urlFilter['sort'] = \implode(',', $sort);

        $url = $this->_prepare_url('ads.stats.campaign.placement.get', $urlFilter);

        // get response items
        return $this->_execute_request($url);
    }

    /**
     * set placement multiplier
     * @param $campaignId
     * @param $placementId
     * @param $multiplier
     * @return array|mixed
     * @throws \Exception
     */
    public function setCampaignPlacementMultiplier($campaignId, $placementId, $multiplier)
    {
        $data = [
            'app_id' => $this->appID,
            'client_id' => $this->appID,
            'campaign_id' => $campaignId,
            'placement' => $placementId,
            'multiplier' => $multiplier,
        ];

        $url = $this->_prepare_url('ads.stats.campaign.placement.put', $data);

        return $this->_execute_request($url);
    }

    /**
     * get sizes id for banner advertise
     * @return array
     * @throws \Exception
     * @since 1.4.0
     */
    public function getBannerSizes(): array
    {
        $url = $this->_prepare_url('ads.materials.banner.sizes.get', []);

        return $this->_execute_request($url)['response'] ?? [];
    }

    /**
     * get ages target
     * @return array
     * @throws \Exception
     * @since 1.4.0
     */
    public function getAges(): array
    {
        $url = $this->_prepare_url('ads.targeting.ages.get', []);

        return $this->_execute_request($url)['response'] ?? [];
    }

    /**
     * get browsers target
     * @return array
     * @throws \Exception
     * @since 1.4.0
     */
    public function getBrowsers(): array
    {
        $url = $this->_prepare_url('ads.targeting.browsers.get', []);

        return $this->_execute_request($url)['response'] ?? [];
    }

    /**
     * get platforms target
     * @return array
     * @throws \Exception
     * @since 1.4.0
     */
    public function getPlatforms(): array
    {
        $url = $this->_prepare_url('ads.targeting.platforms.get', []);

        return $this->_execute_request($url)['response'] ?? [];
    }

    /**
     * Get languages target
     * @param array $params
     * @return array
     * @throws \Exception
     * @since 1.4.0
     */
    public function getLangs($params = []): array
    {
        $data = [
            'limit' => $params['limit'] ?? 500,
            'offset' => $params['offset'] ?? 0
        ];

        $url = $this->_prepare_url('ads.targeting.langs.get', $data);

        return $this->_execute_request($url)['response'] ?? [];
    }

    /**
     * Get devices target
     * @param array $params
     * @return array
     * @throws \Exception
     * @since 1.4.0
     */
    public function getDevices($params = []): array
    {
        $data = [
            'limit' => $params['limit'] ?? 500,
            'offset' => $params['offset'] ?? 0
        ];

        $url = $this->_prepare_url('ads.targeting.devices.get', $data);

        return $this->_execute_request($url)['response'] ?? [];
    }

    /**
     * Get countries target
     * @param array $params
     * @return array
     * @throws \Exception
     * @since 1.4.0
     */
    public function getCountries($params = []): array
    {
        $data = [
            'limit' => $params['limit'] ?? 500,
            'offset' => $params['offset'] ?? 0
        ];

        $url = $this->_prepare_url('ads.targeting.countries.get', $data);

        return $this->_execute_request($url)['response'] ?? [];
    }

    /**
     * Get regions target
     * @param array $params
     * @return array
     * @throws \Exception
     * @since 1.4.0
     */
    public function getRegions($params = []): array
    {
        $data = [
            'limit' => $params['limit'] ?? 500,
            'offset' => $params['offset'] ?? 0
        ];

        $url = $this->_prepare_url('ads.targeting.regions.get', $data);

        return $this->_execute_request($url)['response'] ?? [];
    }

    /**
     * Get cities target
     * @param array $params
     * @return array
     * @throws \Exception
     * @since 1.4.0
     */
    public function getCities($params = []): array
    {
        $data = [
            'limit' => $params['limit'] ?? 500,
            'offset' => $params['offset'] ?? 0
        ];

        $url = $this->_prepare_url('ads.targeting.cities.get', $data);

        return $this->_execute_request($url)['response'] ?? [];
    }
}