<?php

namespace core\authclient\clients;

use Yii;
use yii\authclient\clients\VKontakte as BaseVKontakte;
use yii\helpers\ArrayHelper;

class VKontakte extends BaseVKontakte implements ClientInterface
{
    public $scope = 'email';

    public $attributeNames = [
        'uid',
        'first_name',
        'last_name',
        'nickname',
        'screen_name',
        'sex',
        'bdate',
        'city',
        'country',
        'timezone',
        'photo',
        'photo_max',
        'photo_max_orig'
    ];

    public function getEmail()
    {
        return $this->getAccessToken()->getParam('email');
    }

    public function getUsername()
    {
        return isset($this->getUserAttributes()['screen_name'])
            ? $this->getUserAttributes()['screen_name']
            : null;
    }

    public function getFirstName()
    {
        return ArrayHelper::getValue($this->getUserAttributes(), 'first_name');
    }

    public function getLastName()
    {
        return ArrayHelper::getValue($this->getUserAttributes(), 'last_name');
    }

    public function getPhoto()
    {
        return ArrayHelper::getValue($this->getUserAttributes(), 'photo_max');
    }

}
