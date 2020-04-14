<?php
/**
 * WordPress Etsy Widget: Etsy_API_Request interface.
 *
 * @package wp-etsy-widget
 */

namespace WP_Etsy_Widget;

/**
 * Etsy REST API client.
 */
interface Etsy_API_Client {

	/**
	 * Perform a GET request to the Etsy API.
	 *
	 * @param string $endpoint API endpoint.
	 * @param array  $params Paramaters to send in the request.
	 *
	 * @return array|null Response data or null on error.
	 */
	function get( string $endpoint, array $params ) : ?array;
}
