<?php
require_once 'config.php';
require_once 'FacebookBot.php';
$bot = new FacebookBot(FACEBOOK_VALIDATION_TOKEN, FACEBOOK_PAGE_ACCESS_TOKEN);
$updated = $bot->deleteWelcomeMessage(FACEBOOK_PAGE_ID);
if($updated)
{
	echo "Welcome Message updated succesfully!";
}
else 
{
	echo "Error during Welcome Message update";
}