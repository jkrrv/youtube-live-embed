<?php

use PHPUnit\Framework\TestCase;
use jkrrv\YouTubeLiveEmbed\Video;
use jkrrv\YouTubeLiveEmbed\YTLEException;

global $API_KEY; // put your API key in the TestCredentials.php file.  See TestCredentials.sample.php

include "TestCredentials.php";

class VideoTests extends TestCase
{
	public function test_newFromSearchResultAssoc_completelyWrongThing() {
		$thing = [];
		$this->expectException(YTLEException::class);
		Video::newFromSearchResultAssoc($thing);
	}

	public function test_newFromSearchResultAssoc_notSearchResult() {
		$thing = ['kind' => 'unexpected'];
		$this->expectException(YTLEException::class);
		Video::newFromSearchResultAssoc($thing);
	}

	public function test_newFromSearchResultAssoc_notVideo() {
		$thing=[];
		$thing['kind'] = "youtube#searchResult";
		$thing['id'] = ['kind' => 'unexpected'];
		$this->expectException(YTLEException::class);
		Video::newFromSearchResultAssoc($thing);
	}
}