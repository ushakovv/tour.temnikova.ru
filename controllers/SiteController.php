<?php

namespace app\controllers;

use Yii;
use app\models\Events;
use app\models\Events2cashbox;
use app\models\Cashbox;
use app\models\FooterGroup;
use app\models\HeaderGroup;
use app\models\HeaderItem;
use app\models\Partner;
use app\models\Video;
use app\models\VkAlbum;
use app\models\SocialGroup;
use app\models\Sites;
use app\models\Tickets;
use app\components\FrontController;
use yii\helpers\Json;
use yii\web\Response;
use yii\helpers\ArrayHelper;

class SiteController extends FrontController
{
    const CACHE_LIVE_TIME = 900;
    
    protected $_months_ru = [
        '',
        'Января',
        'Февраля',
        'Марта',
        'Апреля',
        'Мая',
        'Июня',
        'Июля',
        'Августа',
        'Сентября',
        'Октября',
        'Ноября',
        'Декабря',
    ];

    protected $_months_en = [
        '',
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December',
    ];

    public function actions()
    {
        return [
	        'error' => [
		        'class' => 'app\components\ErrorAction',
	        ],
        ];
    }

    public function behaviors()
    {
        return [];
    }

    public function actionIndex()
    {
        $cache = \Yii::$app->cache;
        $cookies = Yii::$app->request->cookies;

        $albumId = $cache->get('vkAlbum');
        $vkPhotos = $cache->get('vkPhotos');
        $shopData = $cache->get('shopData');

        if ($albumId == false) {
            $albumId = VkAlbum::find()->select(['album_id'])->asArray()->one();
            $cache->set('vkAlbum', $albumId, self::CACHE_LIVE_TIME);
        }

        if ($vkPhotos == false) {
            $vkPhotos = Yii::$app->vkcomponent->getPhotos($albumId['album_id']);
            $cache->set('vkPhotos', $vkPhotos, self::CACHE_LIVE_TIME);
        }

        if ($shopData == false) {
            //$shopData = $this->_getShopApi();
            $shopData = '';
            $cache->set('shopData', $shopData, self::CACHE_LIVE_TIME);
        }

        $footerGoup = SocialGroup::find()->where(['status' => 1])->orderBy(['sort' => SORT_ASC])->all();

        $aEvents = Events::find()
            ->select('events.*, city.name')
            ->rightJoin('city', 'events.city_id = city.id')
            ->orderBy(['tour_dt' => SORT_ASC])
            ->where(['city.status' => '1'])
            ->asArray()
            ->all();

        $aEvents = $this->_addMontName($aEvents);

        $getId = Yii::$app->request->get('events');
        if (isset($getId) && !empty($getId))
        {
            $ogDesc = $this->_getEventsById($getId, $aEvents);
        } else {
            $ogDesc = '';
        }

        Yii::$app->view->registerMetaTag([
            'property' => 'og:title',
            'content' => 'Пойдем на концерт Лены Темниковой вместе со мной: ' . $ogDesc,
        ]);

        $aVideo = Video::find()
            ->where(['enabled' => 1])
            ->orderBy(['ord' => SORT_ASC])
            ->asArray()
            ->all();

        $aVideo = Yii::$app->vkcomponent->getVideosPrev($aVideo);

        $aPartner = Partner::find()->where(['enabled' => 1])->orderBy(['ord' => SORT_ASC])->all();

        $footerSites = Sites::find()->where(['active' => 1])->all();

        $headGroup = HeaderGroup::find()
            ->where(['enabled' => 1])
            ->orderBy(['ord' => SORT_ASC])
            ->all();

        $headPunkt = HeaderItem::find()
            ->where(['enabled' => 1])
            ->orderBy(['ord' => SORT_ASC])
            ->all();

        $footer = FooterGroup::find()->one();

        $filters = $this->_makeFilterList($aEvents);

        $tickets = new Tickets();

        $userData = $cookies->getValue('user');

        return $this->render('index.twig', [
            'events' => $aEvents,
            'videos' => $aVideo,
            'partners' => $aPartner,
            'hGroup' => $headGroup,
            'hPunkt' => $headPunkt,
            'vkPhotos' => $vkPhotos,
            'shopData' => $shopData,
            'filters' => $filters,
            'footerSites' => $footerSites,
            'footerGoup' => $footerGoup,
            'footer' => $footer,
            'tickets' => $tickets,
            'userData' => $userData,
        ]);
    }

    protected function _getEventsById($id, $eventsList)
    {
        foreach ($eventsList as $event) {
            if ($event['id'] == $id) {
                $dt = explode(' - ', date('j F Y - h:i', strtotime($event['tour_dt'])));
                $dt[0] = str_replace($this->_months_en, $this->_months_ru, $dt[0]);

                return $event['name'] . ', ' . $dt[0];
            }
        }
    }

    protected function _getVkVideoPrev($videos)
    {
        Yii::$app->vkcomponent->getVideosPrev($videos);
    }

    public function actionGetConcertPhotos()
    {
        if (Yii::$app->request->isAjax) {
            $cache   = Yii::$app->cache;
            $albumId = Yii::$app->request->get('albumId');

            if (!empty($albumId)) {
                $vkPhotos = $cache->get('vkPhotos_' . $albumId);

                if ($vkPhotos == false) {
                    $vkPhotos = Yii::$app->vkcomponent->getPhotos($albumId);

                    if (!empty($vkPhotos)) {
                        $cache->set('vkPhotos_' . $albumId, $vkPhotos, self::CACHE_LIVE_TIME);
                    }
                }

                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $vkPhotos;
            }
        }
    }

    public function actionOrder()
    {
        if (Yii::$app->request->isAjax) {

            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->statusCode = 200;

            $tickets = new Tickets();
            $post = Yii::$app->request->post();

            if($tickets->load($post) && $tickets->validate()) {
                $tickets->save();

                $cookies = Yii::$app->response->cookies;

                $cookies->add(new \yii\web\Cookie([
                    'name' => 'user',
                    'value' => [
                        'name' => $tickets->name,
                        'mail' => $tickets->email,
                        'phone' => $tickets->phone,
                    ],
                    'expire' => time() + 86400 * 365,
                ]));

                return Yii::$app->response->data = Events::find()->where(['id' => $tickets->e_id])->asArray()->one();
            } else {
                return Yii::$app->response->data = ['status' => 401, 'errors' => $tickets->errors];
            }
        }
    }

    protected function _addMontName($aEvents)
    {
        $newEvents = [];

        foreach ($aEvents as $event) {
            $event['month'] = date('M', strtotime($event['tour_dt']));
            $newEvents[] = $event;
        }

        return $newEvents;
    }

    protected function _makeFilterList($events)
    {
        $filters = [
            'city' => [],
            'month' => []
        ];

        foreach ($events as $index => $event) {
            if (!$event['id']) {
                continue;
            }

            if (!empty($event['name'])) {
                array_push($filters['city'], $event['name']);
            }

            $filters['month'][$index]['name'] = $this->_months_ru[(int)date('m', strtotime($event['tour_dt']))];
            $filters['month'][$index]['val'] = date('M', strtotime($event['tour_dt']));
        }

        $filters['city']  = array_unique($filters['city']);
        $filters['month'] = ArrayHelper::map($filters['month'], 'val', 'name');

        array_multisort($filters['city']);

        return $filters;
    }

    protected function _getShopApi()
    {
        $host_api = "http://temnikova.shop";
        $reauest_api = '/index.php?route=api/futures';

        $username = "u0334700_t";
        $password = "N1n6T0r1";

        $curl = curl_init();

        $cookies = Yii::getAlias('@runtime') . '/cookie.txt';

        $options = [
            CURLOPT_URL => $host_api . $reauest_api,
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD => $username . ":" . $password,
            CURLOPT_COOKIESESSION => true,
            CURLOPT_COOKIEJAR => $cookies,
            CURLOPT_COOKIEFILE => $cookies,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HEADER => 0
        ];

        curl_setopt_array($curl,$options);

        try {
            $result = curl_exec($curl);
            curl_close($curl);
        } catch (Exception $e) {
            // pass
            $result = '';
        }

        return ArrayHelper::toArray(json_decode($result));
    }

    public function actionGetPopupDetail() {
        if( Yii::$app->request->isAjax) {
            $result = [];
            $result['success'] = 'ok';
            return Json::encode($result);
        } else {
            $this->redirect('/');
        }
    }

    public function actionGetPointMap()
    {
        if( Yii::$app->request->isAjax) {
            $cache   = Yii::$app->cache;

            $eventsData = $cache->get('eventsData');

            if ($eventsData == false) {
                $cnt = 0;
                $result = array();

                $result['items'] = array();

                $aEvents = Events::find()
                    ->select('events.*, city.name, city.coords_x, city.coords_y')
                    ->rightJoin('city', 'events.city_id = city.id')
                    ->where(['city.status' => '1'])
                    ->orderBy(['tour_dt' => SORT_ASC])
                    ->asArray()
                    ->all();

                if($aEvents) {
                    foreach ($aEvents as $index => $event) {
                        $aCashBoxes = Events2cashbox::find()->select('c_id')->where(['e_id' => $event['id']])->asArray()->all();

                        $aCashes = array();
                        $aCashBoxesIds = array();

                        foreach ($aCashBoxes as $cashbox) {
                            $aCashBoxesIds[] = $cashbox['c_id'];
                        }

                        $aCashes = Cashbox::find()
                            ->where(['in', 'id', $aCashBoxesIds])
                            ->orderBy(['order' => SORT_ASC])
                            ->asArray()
                            ->all();

                        foreach ($aCashes as $index => $cash) {
                            if (strpos($cash['link'], 'ponominalu')) {
                                $aCashes[$index]['ponominalu'] = 'true';
                            } else {
                                $aCashes[$index]['normal'] = 'true';
                            }
                        }

                        $event['address'] = $event['place'] . ', ' . $event['address'];

                        $result['items'][$cnt]['point'] = $event;
                        $result['items'][$cnt]['cashes'] = $aCashes;

                        ++$cnt;
                    }
                    $result['success'] = 'ok';
                } else {
                    $result['success'] = 'zero';
                }

                $eventsData = Json::encode($result);

                if (!empty($eventsData)) {
                    $cache->set('eventsData', $eventsData, self::CACHE_LIVE_TIME);
                }

                return $eventsData;
            } else {
                return $eventsData;
            }
        } else {
           $this->redirect('/');
        }
    }
}
