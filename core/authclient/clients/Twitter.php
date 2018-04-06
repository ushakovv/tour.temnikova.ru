<?php

namespace core\authclient\clients;

use yii\authclient\clients\Twitter as BaseTwitter;
use yii\helpers\ArrayHelper;

class Twitter extends BaseTwitter implements ClientInterface
{
    /**
     * @return string
     */
    public function getUsername()
    {
        return ArrayHelper::getValue($this->getUserAttributes(), 'name');
    }

    /**
     * @return null Twitter does not provide user's email address
     */
    public function getEmail()
    {
        return null;
    }

    public function getFirstName()
    {
        $name = ArrayHelper::getValue($this->getUserAttributes(), 'name');
        $name_parts = explode(" ", $name);
        return isset($name_parts[0]) ? $name_parts[0] : "";
    }

    public function getLastName()
    {
        $name = ArrayHelper::getValue($this->getUserAttributes(), 'name');
        $name_parts = explode(" ", $name);
        return isset($name_parts[1]) ? $name_parts[1] : "";
    }

    public function getPhoto()
    {
        $url = ArrayHelper::getValue($this->getUserAttributes(), "profile_image_url");
        return str_replace("_normal", "_400x400", $url);
    }
}
