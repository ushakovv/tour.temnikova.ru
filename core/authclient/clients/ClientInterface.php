<?php


namespace core\authclient\clients;

use yii\authclient\ClientInterface as BaseInterface;


interface ClientInterface extends BaseInterface
{
    /** @return string|null User's email */
    public function getEmail();

    /** @return string|null User's username */
    public function getUsername();

    /** @return string|null  */
    public function getFirstName();

    /** @return string|null */
    public function getLastName();

    /** @return string|null */
    public function getPhoto();
}
