<?php
/**
 * Class Test_WP_Etsy_API_Client.
 *
 * @package Wp_Etsy_Widget
 */

declare( strict_types=1 );

namespace WP_Etsy_Widget;

/**
 * WP_Etsy_API_Client test case.
 */
class Test_WP_Etsy_API_Client extends \WP_UnitTestCase {

	/**
	 * A sample JSON response (populated by setup).
	 *
	 * @var string
	 * @link https://www.etsy.com/developers/documentation/getting_started/requests#section_sample_response
	 */
	private $response = null;

	/**
	 * Etsy API Client (populated by setup).
	 *
	 * @var Etsy_Api_Clinet
	 */
	private $client = null;

	/**
	 * Pimple container (populated by setup).
	 *
	 * @var \Pimple\Container
	 */
	private $container = null;

	/**
	 * Setup the test case.
	 */
	public function setup() {
		$this->response = file_get_contents( __DIR__ . '/data/sample-etsy-api-response.json' );

		$container = new \Pimple\Container();

		$container['etsy_api_client'] = $container->factory(
			function( \Pimple\Container $c ) : Etsy_API_Client {
				return new WP_Etsy_API_Client();
			}
		);

		$this->container = $container;
		$this->client    = $container['etsy_api_client'];

		parent::setup();
	}

	/**
	 * A simple test with regular input.
	 */
	public function test_simple() {

		$expected = json_decode( $this->response, true );

		add_filter( 'pre_http_request', array( $this, 'mock_api_request' ) );

		$test_response  = $this->client->get( '/shops/foo_shop', array() );
		$test_transient = get_transient( 'etsy_widget1c89fdd07acc6c613d14a88c0f8c0fa0916e4776' );

		$this->assertSame( $test_response, $expected );
		$this->assertSame( $test_transient, $expected );

		remove_filter( 'pre_http_request', array( $this, 'mock_api_request' ) );
	}

	/**
	 * Test for HTTP request failure.
	 */
	public function test_http_failure() {

		add_filter( 'pre_http_request', array( $this, 'mock_api_request_failure' ) );

		$test_response  = $this->client->get( '/shops/foo_shop', array() );
		$test_transient = get_transient( 'etsy_widget1c89fdd07acc6c613d14a88c0f8c0fa0916e4776' );

		remove_filter( 'pre_http_request', array( $this, 'mock_api_request_failure' ) );

		$this->assertNull( $test_response );
		$this->assertFalse( $test_transient );
	}

	/**
	 * WordPress hook for `pre_http_request` filter, returning a sample API request.
	 */
	public function mock_api_request() : array {
		return array(
			'body' => $this->response,
		);
	}

	/**
	 * WordPress hook for `pre_http_request` filter, returning a failed request.
	 */
	public function mock_api_request_failure() : \WP_Error {
		return new \WP_Error();
	}

}
