<?php

class EmbedYoutubeLiveStreaming
{
	public $channelId;
	public $API_Key;

	public $jsonResponse; // pure server response
	public $objectResponse; // response decoded as object
	public $arrayRespone; // response decoded as array

	public $isLive; // true if there is a live streaming at the channel
	public $how_many_streams; // show how many streams are live
	
	public $queryData; // query values as an array
	public $getAddress; // address to request GET
	public $getQuery; // data to request, encoded

	public $queryString; // Address + Data to request

	public $part;
	public $eventType;
	public $type;

	public $default_embed_width;
	public $default_embed_height;
	public $default_ratio;

	public $embed_code; // contain the embed code
	public $embed_autoplay;
	public $embed_width;
	public $embed_height;

	public $live_video_id;
	public $live_video_title;
	public $live_video_description;

	public $live_video_publishedAt;

	public $live_video_thumb_default;
	public $live_video_thumb_medium;
	public $live_video_thumb_high;

	public $channel_title;

	public function __construct($ChannelID, $API_Key, $autoQuery = true)
	{
		$this->channelId = $ChannelID;
		$this->API_Key = $API_Key;

		$this->part = "id,snippet";
		$this->eventType = "live";
		$this->type = "video";

		$this->getAddress = "https://www.googleapis.com/youtube/v3/search?";

		$this->default_embed_width = "560";
		$this->default_embed_height = "315";
		$this->default_ratio = $this->default_embed_width / $this->default_embed_height;

		$this->embed_width = $this->default_embed_width;
		$this->embed_height = $this->default_embed_height;

		$this->embed_autoplay = true;

		if($autoQuery == true) { $this->queryIt(); }
	}

	public function queryIt()
	{
		$this->queryData = array(
			"part" => $this->part,
			"channelId" => $this->channelId,
			"eventType" => $this->eventType,
			"type" => $this->type,
			"key" => $this->API_Key,
		);
		$this->getQuery = http_build_query($this->queryData); // transform array of data in url query
		$this->queryString = $this->getAddress . $this->getQuery;

		$this->jsonResponse = file_get_contents($this->queryString); // pure server response
		$this->objectResponse = json_decode($this->jsonResponse); // decode as object
		$this->arrayResponse = json_decode($this->jsonResponse, TRUE); // decode as array

		$this->how_many_streams = $this->isLive();
		if($this->isLive)
		{
			for($ii=0; $ii < $this->how_many_streams; $ii++)
			{
				$this->live_video_id[$ii] = $this->objectResponse->items[$ii]->id->videoId;
				$this->live_video_title[$ii] = $this->objectResponse->items[$ii]->snippet->title;
				$this->live_video_description[$ii] = $this->objectResponse->items[$ii]->snippet->description;

				$this->live_video_published_at[$ii] = $this->objectResponse->items[$ii]->snippet->publishedAt;
				$this->live_video_thumb_default[$ii] = $this->objectResponse->items[$ii]->snippet->thumbnails->default->url;
				$this->live_video_thumb_medium[$ii] = $this->objectResponse->items[$ii]->snippet->thumbnails->medium->url;
				$this->live_video_thumb_high[$ii] = $this->objectResponse->items[$ii]->snippet->thumbnails->high->url;

				$this->channel_title = $this->objectResponse->items[$ii]->snippet->channelTitle;
				$this->embedCode($ii);
			}
		}
	}

	public function isLive($getOrNot = false)
	{
		if($getOrNot==true)
		{
			$this->queryIt();
		}

		$this->how_many_streams = count($this->objectResponse->items);

		if($live_items > 0)
		{
			$this->isLive = true;
			return $this->how_many_streams;
		}
		else
		{
			$this->isLive = false;
			return false;
		}
	}

	public function setEmbedSizeByWidth($width, $refill_code = true)
	{
		$ratio = $this->default_embed_width / $this->default_embed_height;
		$this->embed_width = $width;
		$this->embed_height = $width / $ratio;

		if( $refill_code == true ) { $this->embedCode(); }
	}

	public function setEmbedSizeByHeight($height, $refill_code = true)
	{
                $ratio = $this->default_embed_width / $this->default_embed_height;
                $this->embed_height = $height;
                $this->embed_width = $height * $ratio;

		if( $refill_code == true ) { $this->embedCode(); }
	}

	public function embedCode($ii = 0)
	{
		$autoplay = $this->embed_autoplay ? "?autoplay=1" : "";

		$this->embed_code[$ii] = <<<EOT
<iframe
	width="{$this->embed_width}"
	height="{$this->embed_height}"
	src="//www.youtube.com/embed/{$this->live_video_id[$ii]}{$autoplay}"
	frameborder="0"
	allowfullscreen>
</iframe>
EOT;

		return $this->embed_code;
	}
}

?>
