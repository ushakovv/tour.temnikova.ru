<?php

namespace core\components;

use core\models\ContentBlock;
use yii\db\Query;

class ContentManager
{
    static private $_cache;

    public static function get($key, $templateVars = array())
    {
        if (!self::$_cache) {
            self::_prepareCache();
        }
        if (isset(self::$_cache[$key])) {
            $content = self::$_cache[$key];
        } else {
            return null;
            /*$contentBlock = ContentBlock::findOne(['key' => $key]);
            $content = $contentBlock ? $contentBlock->content : "";*/
        }
        foreach ($templateVars as $from => $to) {
            $content = str_replace($from, $to, $content);
        }
        return $content;
    }

    private static function _prepareCache()
    {
        $contentBlocks = \Yii::$app->db->createCommand(
            "SELECT `key`, content FROM content_block WHERE precache = 1 OR (object_type = 'global' AND `language` = :lang)",
            ['lang' => \Yii::$app->language]
        )->queryAll();
        foreach ($contentBlocks as $cb) {
            $data[$cb['key']] = $cb['content'];
        }

        self::$_cache = $data;
    }

    public static function preFetchGroup($object_type, $object_id)
    {
        self::_prepareCache();
        $cacheKey = "contentBlocksGroup-$object_type-$object_id";
        $data = \Yii::$app->cache->get($cacheKey);
        if ($data === false) {
            $data = (new Query())
                ->select(['key', 'content'])
                ->from('content_block')
                ->where(['object_type' => $object_type, 'object_id' => $object_id, 'language' => 'ru'])
                ->all();
            \Yii::$app->cache->set($cacheKey, $data, 7200);
        }
       // var_dump($data); exit;
        foreach ($data as $block) {
            self::$_cache[$block['key']] = $block['content'];
        }
    }

    public static function getByGroupId($groupId)
    {
        $data = \Yii::$app->cache->get('contentBlocksData' . $groupId);
        if ($data === false) {
            $blocks = ContentBlock::findAll(['group_id' => $groupId]);
            $data = [];
            foreach ($blocks as $block) {
                $data[$block->key] = $block->content;
            }
            \Yii::$app->cache->set('contentBlocksData' . $groupId, $data, 7200);
        }
        return $data;
    }

    public static function getSupportEmail($contentKey = 'supportEmail')
    {
        $email = self::get($contentKey);
        if (!$email) {
            $email = \Yii::$app->params['supportEmail'];
        }
        return $email;
    }

    public static function getRev()
    {
        return self::get('system.rev');
    }

    public static function getDate($date, $format = "d m YYYY")
    {
        if(!$date) {
            return null;
        }


        $aMonth = ['января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];

        $days = explode(' ', $date);
        $year = explode('-', $days[0])[0];
        $day = explode('-', $days[0])[2];
        $month = $aMonth[intval( explode('-', $days[0])[1]) - 1];
        if(isset($days[1])) {
           $time = explode(':', $days[1])[0] . ':' . explode(':', $days[1])[1];
        } else {
            $time = '';
        }


        return $day . ' ' . $month . ' ' . $year . ' ' . $time;
    }
}