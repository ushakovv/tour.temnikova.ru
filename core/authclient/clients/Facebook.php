<?php

namespace core\authclient\clients;

use yii\authclient\clients\Facebook as BaseFacebook;
use yii\helpers\ArrayHelper;

class Facebook extends BaseFacebook implements ClientInterface
{
    public $attributeNames = [
        'birthday',
        'cover',
        'first_name',
        'gender',
        'hometown',
        'last_name',
        'name',
        'email',
    ];

    public function getEmail()
    {
        return ArrayHelper::getValue($this->getUserAttributes(), "email");
    }

    public function getUsername()
    {
        return ArrayHelper::getValue($this->getUserAttributes(), "name");
    }

    public function getFirstName()
    {
        return ArrayHelper::getValue($this->getUserAttributes(), "first_name");
    }

    public function getLastName()
    {
        return ArrayHelper::getValue($this->getUserAttributes(), "last_name");
    }

    public function getPhoto()
    {
        $picture = $this->api(
            '/v2.7/me/picture?redirect=false',
            'GET', ['redirect' => 'false', 'type' => 'square', 'width' => 200, 'height' => 200]
        );
        return ArrayHelper::getValue($picture, "data.url");
    }
}
