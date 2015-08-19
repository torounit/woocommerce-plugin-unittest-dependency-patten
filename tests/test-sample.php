<?php

class SampleTest extends WP_UnitTestCase {

	function test_sample() {
		$this->assertTrue( class_exists('WooCommerce') );
	}
}

