<?php
/**
 * Etsy Widget for WordPress: HTML_Widget_Input_Render class.
 *
 * @package wp-etsy-widget
 */

declare( strict_types=1 );

namespace WP_Etsy_Widget;

/**
 * Renders a widget input as HTML.
 */
class WP_Etsy_API_Client implements Etsy_API_Client {

	const BASE_URL = 'https://openapi.etsy.com/v2';

	/**
	 * Perform a GET request to the Etsy API.
	 *
	 * @param string $endpoint API endpoint.
	 * @param array  $params Paramaters to send in the request.
	 *
	 * @return array|null Response data or null on error.
	 */
	function get( string $endpoint, array $params = [] ) : ?array {

		$cache_key = 'etsy_widget' . sha1( $endpoint . json_encode( $params ) );

		$cache_value = get_transient( $cache_key );

		if ( ! empty( $cache_value ) ) {
			return $cache_value;
		}

		$url = self::BASE_URL . $endpoint . '?' . http_build_query( $params );
		$response = wp_safe_remote_get( $url );
		$body = wp_remote_retrieve_body( $response );

		if ( '' === $body ) {
			return null;
		}

		$return = json_decode( $body, true );

		set_transient( $cache_key, $return, 3600 );

		return $return;
	}
}
