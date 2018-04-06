<?php

namespace core\authclient\clients;

use yii\authclient\clients\LinkedIn as BaseLinkedIn;


class LinkedIn extends BaseLinkedIn implements ClientInterface
{
    public function getEmail()
    {
        return isset($this->getUserAttributes()['email-address'])
            ? $this->getUserAttributes()['email-address']
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
