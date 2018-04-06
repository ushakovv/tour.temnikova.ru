<?php

namespace app\components\vk;

use Yii;
use Yii\base\Component;

class VkComponent extends Component {
    protected $client;

    public function init()
    {
        parent::init();

        $conf = Yii::$app->components['authClientCollection']['clients']['vkontakte'];

        $this->client = new Vk([
            'client_id' => $conf['clientId'], // (required) app id
            'secret_key' => $conf['clientSecret'], // (required) get on https://vk.com/editapp?id=12345&section=options
            'scope' => 'wall,photos,video', // scope access
        ]);

        //d($this->client->get_code_token());
    }

    public function getPhotos($id)
    {
        $albumDta = explode('_', $id);

        if (!empty($albumDta[0]) && isset($albumDta[1])) {
            $album = $this->client->api('photos.get', ['owner_id' => $albumDta[0], 'album_id' => $albumDta[1]]);

            if (isset($album['items'])) {
                return $this->_getPhotosList($album['items']);
            }
        }

        return '';

    }

    public function getVideoPrev($id)
    {
        return $this->client->api('video.get', ['videos' => $id . '_' . $this->client->access_token]);
    }

    public function getVideosPrev($videos)
    {
        $list = '';
        $token = $this->client->access_token;

        foreach ($videos as $index => $video) {
            $video_id = $this->_getVideoId($video['source']);

            $list .= $video_id . '_' . $token;

            if (count($videos) > ($index + 1)) {
                $list .= ',';
            }

        }

        $prev = $this->client->api('video.get', ['videos' => $list]);

        if (isset($prev['error'])) {
            return '';
        }

        foreach ($videos as $index => $video) {
            $video_id = $this->_getVideoId($video['source']);
            foreach ($prev['items'] as $prevImg) {
                if ($video_id === $prevImg['owner_id'] . '_' . $prevImg['id'] && !empty($prevImg['photo_800'])) {
                    $videos[$index]['preview'] = $prevImg['photo_800'];
                }
            }
        }

        return $videos;
    }

    protected function _getVideoId($source)
    {
        $link = explode('?', $source);
        $get = explode('&', $link[1]);

        $oid = explode('=', $get[0]);
        $oid = $oid[1];

        $vid = explode('=', $get[1]);
        $vid = $vid[1];

        return $oid . '_' . $vid;
    }

    protected function _getPhotosList($photos)
    {
        $album = [];

        foreach ($photos as $index => $photo) {
            $album[$index]['prev'] = $photo['photo_130'];
            $album[$index]['normal'] = $photo['photo_807'];
        }

        return $album;
    }
}