<?php

namespace jkrrv;

use jkrrv\YouTubeLiveEmbed\Video;
use jkrrv\YouTubeLiveEmbed\VideoOptions;
use GuzzleHttp\Client;

class YouTubeLiveEmbed
{
	protected $_channelId;
	protected static $_apiKey;

	protected static $getAddress = "https://www.googleapis.com/youtube/v3/search?";

	protected $_videos = [];
	protected $_fetched = false;

	/** @var Client|null $guzzleClient */
	public $guzzleClient = null;

	/** @var VideoOptions|null $defaultVideoOptions */
	public static $defaultVideoOptions = null;
	public $videoOptions;

	public function __construct($channelId)
	{
		$this->_channelId = $channelId;

		if (self::$defaultVideoOptions === null)
			self::$defaultVideoOptions = new VideoOptions();

		$this->part = "id,snippet";
		$this->eventType = "live";
		$this->type = "video";

		// Initialize the Guzzle Client, since it's realistic to think we'll need it eventually.
		$this->guzzleClient = new Client();
	}

	/**
	 * Set the API Key to be used for all queries of all channels.
	 *
	 * @param $apiKey
	 */
	public static function setApiKey($apiKey)
	{
		self::$_apiKey = $apiKey;
	}

	/**
	 *  Load data from YouTube.
	 */
	private function query()
	{
		$queryString = [
			"part" => $this->part,
			"channelId" => $this->_channelId,
			"eventType" => $this->eventType,
			"type" => $this->type,
			"key" => self::$_apiKey,
			"maxResults" => 50
		];
		$queryString = self::$getAddress . http_build_query($queryString);

		$res = $this->guzzleClient->request("GET", $queryString); // submit the API request
		$response = json_decode($res->getBody(), true, 7); // decode as associative array

		foreach($response['items'] as $item) {
			if ($item['id']['kind'] == "youtube#video") {
				$this->_videos[] = Video::newFromSearchResultAssoc($item);
			}
		}
	}

	/**
	 * @return Video[]
	 */
	public function videos()
	{
		if (!$this->_fetched) {
			$this->query();
		}
		return $this->_videos;
	}
}

?>
