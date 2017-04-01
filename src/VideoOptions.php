<?php

namespace jkrrv\YouTubeLiveEmbed;

class VideoOptions
{
	public $height = 315;
	public $width = 560;

	public $forceHttps = false;

	// Each of the following parameters are defined by [supported parameters](https://developers.google.com/youtube/player_parameters)
	// The comments mark valid and default options: 01:1 means "0 or 1, default is 1."  ni:n means "null or int, default is null."

	public $autoplay = false; // 01:0

	public $cc_load_policy = false; // 01:0

	public $color = null; // ns:n

	public $controls = 1; // 012:1

	public $disablekb = false; // 01:0

	public $enablejsapi = false; // 01:0

	public $end = null; // ni:n

	public $fs = true; // 01:1

	public $hl = null; // ns:n

	public $iv_load_policy = 1; // 13:1

	public $list = null; // ns:n

	public $listType = null; // ns:n

	public $loop = false; // 01:0

	public $modestbranding = false; // 01:0

	public $origin = null; // ns:n

	public $playlist = null; // ns:n

	public $playsinline = null; // 01n:n

	public $rel = true; // 01:1

	public $showinfo = null; // 01n:n

	public $start = null; // ni:n



	public function __construct($options = []) {
		$this->merge($options);
	}


	/**
	 * Combine an array of options or VideoOptions with this VideoOptions object.
	 *
	 * @param VideoOptions|string[] $options
	 */
	public function merge($options) {
		foreach ($options as $opt => $val) {
			if (property_exists(__CLASS__, $opt)) {
				$this->$opt = $val;
			}
		}
	}

	public function getParamString() {
		$string = [];
		foreach ($this as $param => $value) {
			switch ($param) {
				// Options that aren't URL parameters
				case "height":
				case "width":
				case "forceHttps":
					break;

				// Options that are Boolean, default 0: (01:0)
				case "autoplay":
				case "cc_load_policy":
				case "disablekb":
				case "enablejsapi":
				case "loop":
				case "modestbranding":
					if ($value == true) {
						$string[] = $param . "=1";
					}
					break;

				// Options that are Boolean, default 1: (01:1)
				case "fs":
				case "rel":
					if ($value == false) {
						$string[] = $param . "=0";
					}
					break;

				// Options that are ints or strings, default null: (in:n and sn:n)
				case "color":
				case "end":
				case "hl":
				case "list":
				case "listType":
				case "origin":
				case "playlist":
				case "start":
					if ($value !== null) {
						$string[] = $param . "=" . $value;
					}
					break;

				// Options that are Boolean, default null: (01n:n)
				case "playsinlne":
				case "showinfo":
					if ($value !== null) {
						$string[] = $param . "=" . (int)$value;
					}
					break;

				// Options that are integers and default to 1: (0123:1, etc)
				case "controls":
				case "iv_load_policy":
					if ($value != 1) {
						$string[] = $param . "=" . (int)$value;
					}
					break;
			}
		}
		return (count($string) > 0 ? "?" : "") . implode("&", $string);
	}

	public function getHttps() {
		if ($this->forceHttps) {
			return "https";
		}
		return "";
	}
}