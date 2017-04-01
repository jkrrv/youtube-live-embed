<?php

use PHPUnit\Framework\TestCase;
use jkrrv\YouTubeLiveEmbed\VideoOptions;

class VideoOptionsTests extends TestCase {

	public function test_mergeDoesNotMergeUnknownParameters() {
		$optionsA = new VideoOptions();
		$optionsB = clone $optionsA;
		$optionsB->merge(['completely_unknown_parameter' => true]);
		$this->assertEquals($optionsA, $optionsB);
	}

	public function test_mergeDoesMergeKnownParameters() {
		$options = new VideoOptions();
		$options->merge(['height' => 1000]);
		$this->assertEquals(1000, $options->height);
	}

	public function test_getHttpsGivesHttpsWhenForceIsEnabled() {
		$options = new VideoOptions(['forceHttps' => true]);
		$this->assertEquals('https', $options->getHttps());
	}

	public function test_getHttpsGivesBlankWhenForceIsDisabled() {
		$options = new VideoOptions(['forceHttps' => false]);
		$this->assertEquals('', $options->getHttps());
	}

	public function test_defaultsHaveEmptyParameterString() {
		$options = new VideoOptions();
		$this->assertEquals("", $options->getParamString());
	}

	public function test_booleanNonDefaultZeroParameterString() {
		$options = new VideoOptions(['modestbranding' => true]);
		$this->assertEquals("?modestbranding=1", $options->getParamString());
	}

	public function test_booleanNonDefaultOneParameterString() {
		$options = new VideoOptions(['fs' => false]);
		$this->assertEquals("?fs=0", $options->getParamString());
	}

	public function test_booleanNonDefaultNullParameterString() {
		$options = new VideoOptions(['showinfo' => false]);
		$this->assertEquals("?showinfo=0", $options->getParamString());
	}

	public function test_integerNonDefaultNullParameterString() {
		$options = new VideoOptions(['start' => 120]);
		$this->assertEquals("?start=120", $options->getParamString());
	}

	public function test_integerNonDefaultOneParameterString() {
		$options = new VideoOptions(['controls' => 2]);
		$this->assertEquals("?controls=2", $options->getParamString());
	}

	public function test_stringNonDefaultNullParameterString() {
		$options = new VideoOptions(['color' => 'white']);
		$this->assertEquals("?color=white", $options->getParamString());
	}

}