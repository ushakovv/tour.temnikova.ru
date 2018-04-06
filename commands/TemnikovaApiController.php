<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use app\models\Events;
use app\models\City;
use yii\helpers\ArrayHelper;

class TemnikovaApiController extends Controller
{
    public function actionIndex()
    {
        $url = 'http://www.temnikova.ru/api/concerts';

        $apiData = ArrayHelper::toArray(json_decode(file_get_contents($url, true)));

        $cities = ArrayHelper::map(City::find()->select(['id', 'name'])->asArray()->all(), 'name', 'id');

        foreach ($apiData as $index => $event) {
            // continue if ponominalu data is empty
            if (!empty($event['tour_id']) && (int)$event['tour_id'] === 1) {
                $events = Events::findOne(['api_id' => $event['id']]);

                if (empty($events)) {
                    $events = new Events();

                    $events->tour_id    = $event['tour_id'];
                    $events->tour_desc  = $event['text'];
                    $events->city_id    = array_key_exists(trim($event['city']), $cities) ? (int)$cities[trim($event['city'])] : 0;
                    $events->api_id     = $event['id'];
                }

                $events->p_alias    = $event['ponominalu_alias'];
                $events->p_dt       = $event['ponominalu_date'];
                $events->p_time     = $event['ponominalu_time'];
                $events->p_auth     = $event['ponominalu_referral_auth'];

                $events->save();
            }
        }
    }
}
