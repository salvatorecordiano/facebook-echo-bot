<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/FacebookBot.php';

if (isset($_REQUEST['hub_challenge'])
    && isset($_REQUEST['hub_verify_token'])
    && FACEBOOK_VALIDATION_TOKEN === $_REQUEST['hub_verify_token']
) {
    echo $_REQUEST['hub_challenge'];
    die;
}

$bot = new FacebookBot(FACEBOOK_PAGE_ACCESS_TOKEN);
$bot->run();

foreach ($bot->getReceivedMessages() as $message)
{
	$recipientId = $message['senderId'];
	if($message['text']) {
		$bot->sendTextMessage($recipientId, $message['text']);
	} else {
		$bot->sendTextMessage($recipientId, 'Attachment received');
	}
}
