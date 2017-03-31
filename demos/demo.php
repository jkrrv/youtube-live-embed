<?php

/*
 *  how to use this demo:
 *
 *  0.  Replace the $channelId and $api_key with yours.
 *
 *  1.  Upload it to an webserver with php, with the EmbedYoutubeLiveStreaming.php
 *        in the same directory.
 *
 *  2.  Then open it in your browser.
 *
 *  3.  Then, start a live broadcast "Hangouts On Air" from Google Plus, from the user of Channel ID.
 *
 *  4.  Wait several seconds while reloading the page, it has a delay for the server to
 *        change it's response after a video is broadcasting.
 *
 *  5.  When the server response changes, it will embed the video with autoplay (which is default)
 *        and show some video info.
 *
 *  Check the code and the class for more info.
 *
 */

require_once('EmbedYoutubeLiveStreaming.php');

$channelId = "REPLACE_ME";
$api_key = "REPLACE_ME";

$YouTubeLive = new EmbedYoutubeLiveStreaming($channelId,$api_key);

if(!$YouTubeLive->isLive)
{
	echo "There is no live streaming right now! Response is (Decoded to object):<br><br>";
	echo "<pre><code>";
	var_dump($YouTubeLive->objectResponse);
	// print_r($YouTubeLive->arrayResponse);
	echo "</code></pre>";
}
else
{
	echo <<<EOT
There is a live streaming currently! You can see below!<br>
<br>
Title is: {$YouTubeLive->live_video_title}<br>
<br>
Description is: {$YouTubeLive->live_video_description}<br>
<br>
Video ID is: {$YouTubeLive->live_video_id}<br><br>
Thumbs are: {$YouTubeLive->live_video_thumb_default}, {$YouTubeLive->live_video_thumb_medium}, {$YouTubeLive->live_video_thumb_high} <br><br>
Published at: {$YouTubeLive->live_video_published_at}<br><br>
Channel Title: {$YouTubeLive->channel_title}<br><br>

EOT;

	// $YouTubeLive->setEmbedSizeByWidth(200);
	// $YouTubeLive->setEmbedSizeByHeight(200);
	// $YouTubeLive->embed_autoplay = false;

	echo $YouTubeLive->embedCode();
}
?>
