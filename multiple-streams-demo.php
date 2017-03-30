<?

/* this should use class from branch 'multiple-streams' and not 'master' !!! */

require_once('EmbedYoutubeLiveStreaming.php'); // Use this if the class file from repo is in the same directory

$channelId = 'REPLACE_ME'; // This is the your CHANNEL ID
$api_key = 'REPLACE_ME'; // This is your google project API KEY with youtube api enabled

$YouTubeLive = new EmbedYoutubeLiveStreaming($channelId, $api_key);

if($YouTubeLive->isLive)
{
	echo "There are {$YouTubeLive->how_many_streams} live streams!<br/><br/>";

	for($i=0; $i<$YouTubeLive->how_many_streams; $i++)
	{
		$a = $i+1;
		echo "Stream no. {$a}:<br/>";

		if($i==0) { $YouTubeLive->embed_autoplay = true; } // this will cause autoplay for the first stream only
		else { $YouTubeLive->embed_autoplay = false; }

		echo $YouTubeLive->embedCode($i) . "<br/><br/>";
	}
}
else
{
	echo "There is no live streaming currently!<br/>";
}

?>
