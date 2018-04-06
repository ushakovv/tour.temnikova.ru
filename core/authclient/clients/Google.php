<?php

namespace core\authclient\clients;

use yii\authclient\clients\GoogleOAuth as BaseGoogle;

class Google extends BaseGoogle implements ClientInterface
{
    public function getEmail()
    {
        return isset($this->getUserAttributes()['emails'][0]['value'])
            ? $this->getUserAttributes()['emails'][0]['value']
            : null;
    }

    public function getUsername()
    {
        return;
    }

    public function getFirstName()
    {
        return "";
    }

    public function getLastName()
    {
        return "";
    }

    public function getPhoto()
    {
        return "";
    }

}
