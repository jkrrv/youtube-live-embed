<?php

use PHPUnit\Framework\TestCase;
use jkrrv\YouTubeLiveEmbed;

global $API_KEY; // put your API key in the TestCredentials.php file.  See TestCredentials.sample.php

include "TestCredentials.php";

class YouTubeLiveEmbedTests extends TestCase {

	/** @var YouTubeLiveEmbed $ytle */
	private static $ytle;

	/** @var YouTubeLiveEmbed\Video[] $videos */
	private static $videos;

	public function test_init() {
		global $API_KEY;
		YouTubeLiveEmbed::setApiKey($API_KEY);
		self::$ytle = new YouTubeLiveEmbed('UCZvXaNYIcapCEcaJe_2cP7A'); // California Academy of Natural Sciences
		$this->assertInstanceOf(YouTubeLiveEmbed::class, self::$ytle);
	}

	public function test_thereAreLiveVideos() {
		self::$videos = self::$ytle->videos();
		$this->assertGreaterThan(0, count(self::$videos));
	}

	public function test_videoIdIsString() {
		$this->assertStringMatchesFormat('%s' , self::$videos[0]->id);
	}

	public function test_videoPublishedAtIsDateTimeObject() {
		$this->assertInstanceOf(DateTimeImmutable::class, self::$videos[0]->publishedAtDT);
	}

	public function test_videoAttributeDoesNotExist() {
		$this->expectException(YouTubeLiveEmbed\YTLEException::class);
		self::$videos[0]->thingThatDoesNotExist;
	}

	public function test_videoEmbedCodeReturn() {
		$opts = new YouTubeLiveEmbed\VideoOptions();
		$expected = "<iframe width=\"" . $opts->width . "\" height=\"" . $opts->height . "\" src=\"//www.youtube.com/embed/" . self::$videos[0]->id . "\" frameborder=\"0\" allowfullscreen></iframe>";
		$embedCode = self::$videos[0]->embedCode(false, $opts);
		$this->assertEquals($expected, $embedCode);
	}

	public function test_videoEmbedCodeReturnInheritOptions() {
		$opts = self::$videos[0]->options;
		$expected = "<iframe width=\"" . $opts->width . "\" height=\"" . $opts->height . "\" src=\"//www.youtube.com/embed/" . self::$videos[0]->id . $opts->getParamString() . "\" frameborder=\"0\" allowfullscreen></iframe>";
		$embedCode = self::$videos[0]->embedCode(false, []);
		$this->assertEquals($expected, $embedCode);
	}

	public function test_videoEmbedCodeEcho() {
		$opts = new YouTubeLiveEmbed\VideoOptions();
		$expected = "<iframe width=\"" . $opts->width . "\" height=\"" . $opts->height . "\" src=\"//www.youtube.com/embed/" . self::$videos[0]->id . "\" frameborder=\"0\" allowfullscreen></iframe>";
		$this->expectOutputString($expected);
		self::$videos[0]->embedCode(true, $opts);
	}

	public function test_videoEmbedUrlReturn() {
		$opts = new YouTubeLiveEmbed\VideoOptions();
		$expected = "//www.youtube.com/embed/" . self::$videos[0]->id;
		$embedCode = self::$videos[0]->embedUrl($opts);
		$this->assertEquals($expected, $embedCode);
	}

	public function test_videoEmbedUrlReturnInheritOptions() {
		$expected = "//www.youtube.com/embed/" . self::$videos[0]->id . self::$videos[0]->options->getParamString();
		$embedCode = self::$videos[0]->embedUrl([]);
		$this->assertEquals($expected, $embedCode);
	}

}