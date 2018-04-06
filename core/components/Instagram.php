<?php
/**
 * Created by PhpStorm.
 * User: dlatikov
 * Date: 18.11.2016
 * Time: 13:42
 */

namespace core\components;


use InstagramAPI\ChallengeResponse;
use InstagramAPI\InstagramException;
use InstagramAPI\LoginResponse;
use InstagramAPI\SignatureUtils;

class Instagram extends \InstagramAPI\Instagram
{
    public function login($force = false)
    {
        if (!$this->isLoggedIn || $force) {
            $this->syncFeatures(true);
            $fetch = $this->http->request('si/fetch_headers/?challenge_type=signup&guid='.SignatureUtils::generateUUID(false), null, true);
            $header = $fetch[0];
            $response = new ChallengeResponse($fetch[1]);

            if (!isset($header) || (!$response->isOk())) {
                throw new InstagramException("Couldn't get challenge, check your connection");
            }

            if (!preg_match('#Set-Cookie: csrftoken=([^;]+)#', $fetch[0], $token)) {
                throw new InstagramException('Missing csfrtoken');
            }

            $data = [
                'phone_id'            => SignatureUtils::generateUUID(true),
                '_csrftoken'          => $token[0],
                'username'            => $this->username,
                'guid'                => $this->uuid,
                'device_id'           => $this->device_id,
                'password'            => $this->password,
                'login_attempt_count' => '0',
            ];

            $login = $this->http->request('accounts/login/', SignatureUtils::generateSignature(json_encode($data)), true);
            $response = new LoginResponse($login[1]);

            if (!$response->isOk()) {
                throw new InstagramException($response->getMessage());
            }

            $this->isLoggedIn = true;
            $this->username_id = $response->getUsernameId();
            $this->settings->set('username_id', $this->username_id);
            $this->rank_token = $this->username_id.'_'.$this->uuid;
            preg_match('#Set-Cookie: csrftoken=([^;]+)#', $login[0], $match);
            $this->token = $match[1];
            $this->settings->set('token', $this->token);

            $this->syncFeatures();
            //$this->autoCompleteUserList();
            /*$this->timelineFeed();
            $this->getRankedRecipients();
            $this->getRecentRecipients();
            $this->megaphoneLog();
            $this->getv2Inbox();
            $this->getRecentActivity();
            $this->getReelsTrayFeed();
            $this->explore();*/

            return $response;
        }

        $check = $this->timelineFeed();
        if ($check->getMessage() == 'login_required') {
            $this->login(true);
        }
        //$this->autoCompleteUserList();
        /*$this->getReelsTrayFeed();
        $this->getRankedRecipients();
        //push register
        $this->getRecentRecipients();
        //push register
        $this->megaphoneLog();
        $this->getv2Inbox();
        $this->getRecentActivity();
        $this->explore();*/
    }

}