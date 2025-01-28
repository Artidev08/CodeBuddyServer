<?php

namespace App\Traits;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

trait CanSendFCMNotification
{
    public $optionBuilder;
    public $notificationBuilder;
    public $dataBuilder;
    public $tokens;
    public $showNotification = true;

    public function fcm()
    {
        $this->dataBuilder = new PayloadDataBuilder();
        $this->optionBuilder = new OptionsBuilder();
        $this->notificationBuilder = new PayloadNotificationBuilder('Undefined Title!');

        $this->optionBuilder->setTimeToLive(60 * 20);
        $this->notificationBuilder->setSound('default');
        return $this;
    }


    public function setTitle($title = 'Undefined Title!')
    {
        $this->notificationBuilder->setTitle($title);
        return $this;
    }

    public function setBadge($icon = null)
    {
        if ($icon != null) {
            $this->notificationBuilder->setBadge($icon);
        }
        return $this;
    }

    public function setBody($body)
    {
        $this->notificationBuilder->setBody($body);
        return $this;
    }

    public function showNotification($visible = true)
    {
        $this->showNotification = "$visible";
        return $this;
    }

    public function addData($data = [])
    {
        if (!array_key_exists("visible", $data)) {
            $data['visible'] = "$this->showNotification";
        }
        $this->dataBuilder->addData($data);
        return $this;
    }

    public function setFcmServer($serverKey, $senderId)
    {
        config(
            [
            'fcm.http.server_key' => $serverKey,
            'fcm.http.sender_id' => $senderId
            ]
        );
        return $this;
    }



    public function setTokens($tokens = [])
    {
        $this->tokens = $tokens;
        return $this;
    }

    public function send()
    {
        if (!empty($this->tokens)) {
            $option = $this->optionBuilder->build();
            $notification = $this->notificationBuilder->build();
            $data = $this->dataBuilder->build();
            FCM::sendTo($this->tokens, $option, $notification, $data);
        }
    }
}
