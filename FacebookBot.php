<?php

class FacebookBot
{
    const BASE_URL = 'https://graph.facebook.com/v6.0';

    /** @var string */
    private $pageAccessToken;
    /** @var array */
    private $receivedMessages;

    public function __construct($pageAccessToken)
    {
        $this->pageAccessToken = $pageAccessToken;
        $this->receivedMessages = array();
    }

    public function getReceivedMessages()
    {
        return $this->receivedMessages;
    }

    public function getPageAccessToken()
    {
        return $this->pageAccessToken;
    }

    public function sendTextMessage($recipientId, $text)
    {
        $url = \sprintf('%s/me/messages?access_token=%s', self::BASE_URL, $this->getPageAccessToken());
        $parameters = array(
            'recipient' => array('id' => $recipientId),
            'message' => array('text' => $text)
        );
        $response = self::executePost($url, $parameters);

        if(empty($response)) {

            return false;
        }

        $responseObject = \json_decode($response, true);
        return isset($responseObject['recipient_id'])
            && isset($responseObject['message_id']);
    }

    public function run()
    {
        $request = self::getJsonRequest();

        if(empty($request)) {
            return;
        }

        if(!isset($request['entry'])) {
            return;
        }

        foreach ($request['entry'] as $entry) {
            foreach ($entry['messaging'] as $messaging) {
                $this->receivedMessages[] = array(
                    'senderId' => isset($messaging['sender']['id']) ? $messaging['sender']['id'] : null,
                    'text' => isset($messaging['message']['text']) ? $messaging['message']['text'] : null,
                    'attachments' => isset($messaging['message']['attachments']) ? $messaging['message']['attachments'] : null
                );
            }
        }
    }

    private static function getJsonRequest()
    {
        return \json_decode(
            \file_get_contents('php://input'),
            true,
            512,
            JSON_BIGINT_AS_STRING
        );
    }

    private static function executePost($url, $parameters)
    {
        $ch = \curl_init();
        \curl_setopt($ch, CURLOPT_URL, $url);
        $data = \json_encode($parameters);
        \curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        \curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        \curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        \curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = \curl_exec($ch);
        \curl_close($ch);

        return $response;
    }
}
