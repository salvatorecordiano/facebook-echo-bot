<?php
require_once 'config.php';
require_once 'FacebookBot.php';
$bot = new FacebookBot(FACEBOOK_VALIDATION_TOKEN, FACEBOOK_PAGE_ACCESS_TOKEN);
$isSubscribed = $bot->subscribeAppToThePage();
if($isSubscribed)
{
	echo "App subscribed to the Page succesfully!";
}
else 
{
	echo "Error during App subscription to the Page";
}