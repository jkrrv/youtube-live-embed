<?php

namespace jkrrv\YouTubeLiveEmbed;


/**
 * Class Video
 *
 * This class is a container for each YouTube embeddable video.  Every video is represented by an instance of this class.
 *
 * @package jkrrv\YouTubeLiveEmbed
 * @property-read string $id The video ID from YouTube
 * @property-read string $title The Title from YouTube
 * @property-read string $description The Description from YouTube
 * @property-read string $channelTitle The Channel Title from YouTube
 * @property-read \DateTimeImmutable $publishedAtDT A DateTimeImmutable object representing the Date/Time in which the video was published.
 * @property-read string $thumb_default A URL for the Default video thumbnail image
 * @property-read string $thumb_medium A URL for the Medium-resolution video thumbnail image
 * @property-read string $thumb_high A URL for the High-resolution video thumbnail image
 *
 */
class Video
{
	protected $_id;
	protected $_title;
	protected $_description;
	protected $_channelId;
	protected $_channelTitle;

	protected $_publishedAtDT;
	protected $_thumb_default;
	protected $_thumb_medium;
	protected $_thumb_high;

	protected $_isLive = false;

	/** @var VideoOptions $options */
	public $options;

	protected function __construct($videoId) {
		$this->_id = $videoId;

		$this->options = new VideoOptions(['modestbranding'=>true,'autoplay'=>true]); // TODO defer to parent for defaults.
	}

	public static function newFromSearchResultAssoc($searchResultItem) {
		if (!isset($searchResultItem['kind']) || !isset($searchResultItem['kind'])) {
			throw new YTLEException("Thing provided to NewFromSearchResultAssoc doesn't appear to be a valid thing to provide.");
		}

		if ($searchResultItem['kind'] !== 'youtube#searchResult') {
			throw new YTLEException("Thing provided to NewFromSearchResultAssoc doesn't appear to be a search result.");
		}

		if ($searchResultItem['id']['kind'] !== 'youtube#video') {
			throw new YTLEException("Thing provided to Video::NewFromSearchResultAssoc() doesn't appear to be a video.");
		}

		$v = new Video($searchResultItem['id']['videoId']);

		$v->_publishedAtDT 	= $searchResultItem['snippet']['publishedAt'];
		$v->_channelId 		= $searchResultItem['snippet']['channelId'];
		$v->_title 			= $searchResultItem['snippet']['title'];
		$v->_description 	= $searchResultItem['snippet']['description'];
		$v->_channelTitle 	= $searchResultItem['snippet']['channelTitle'];

		$v->_thumb_default	= $searchResultItem['snippet']['thumbnails']['default']['url'];
		$v->_thumb_medium	= $searchResultItem['snippet']['thumbnails']['medium']['url'];
		$v->_thumb_high		= $searchResultItem['snippet']['thumbnails']['high']['url'];

		if (isset($searchResultItem['snippet']['liveBroadcastContent']) && $searchResultItem['snippet']['liveBroadcastContent'] == 'live')
			$v->_isLive = true;

		return $v;
	}

	/**
	 * Standard Parameter Getter.  Using a getter helps ensure that properties are treated as read-only, since there is
	 * no corresponding Setter.
	 *
	 * @param string $what The parameter being requested.  See Property-Read tags in the LiveVideo class for options.
	 * @return string|\DateTimeImmutable The requested value.
	 * @throws YTLEException "Requested Parameter Does Not Exist"
	 */
	public function __get($what) {
		// handle any special cases here:
		switch ($what) {
			case "publishedAtDT":
//				var_dump("here", is_string($this->_publishedAtDT), $this->_publishedAtDT);
				if (is_string($this->_publishedAtDT)) {  // convert to DT object
					$this->_publishedAtDT = \DateTimeImmutable::createFromFormat("Y-m-d*H:i:s*uP", $this->_publishedAtDT);
				}
				return $this->_publishedAtDT;
		}

		// everything else
		$what = "_" . $what;
		if(isset($this->$what)) {
			return $this->$what;
		}

		// requested something that isn't a thing
		throw new YTLEException('Requested Parameter Does Not Exist.');
	}


	/**
	 * Provides standard embed code for a given video.
	 *
	 * @param bool $echo Whether the code should be printed in place (or returned as a string).  Default is 'true' which prints in-place.
	 * @param array|VideoOptions $options
	 * @return null|string
	 */
	public function embedCode($echo = true, $options = []) {
		if (count($options) > 0 && $options != $this->options) { // if the options are getting changed, clone them so changes aren't back-washed.
			$opts = clone $this->options;
			$opts->merge($options);
		} else {
			$opts = &$this->options;
		}

		ob_start();
		?><iframe width="<?=$opts->width ?>" height="<?= $opts->height ?>" src="<?= $opts->getHttps(); ?>//www.youtube.com/embed/<?= $this->id . $opts->getParamString(); ?>" frameborder="0" allowfullscreen></iframe><?php

		if ($echo) {
			echo ob_get_clean();
			return null;
		} else {
			return ob_get_clean();
		}
	}

}