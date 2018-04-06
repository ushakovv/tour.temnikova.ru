<?php

namespace core\authclient\clients;

use yii\authclient\clients\YandexOAuth as BaseYandex;

class Yandex extends BaseYandex implements ClientInterface
{
    public function getEmail()
    {
        $emails = isset($this->getUserAttributes()['emails'])
            ? $this->getUserAttributes()['emails']
            : null;

        if ($emails !== null && isset($emails[0])) {
            return $emails[0];
        } else {
            return null;
        }
    }

    public function getUsername()
    {
        return isset($this->getUserAttributes()['login'])
            ? $this->getUserAttributes()['login']
            : null;
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
