<?php
/**
 * Etsy Widget for WordPress: Env_Config class.
 *
 * @package wp-etsy-widget
 */

declare( strict_types=1 );

namespace WP_Etsy_Widget;

/**
 *  Provides configuration from environment.
 */
class Env_Config implements Config {

	/**
	 * Get the Etsy developer key.
	 *
	 * @return string Etsy developer key.
	 * @throws \Exception If the ETSY_KEY environment variable does not exist.
	 */
	function get_etsy_developer_key() : string {
		$key = getenv( 'ETSY_KEY' );

		if ( false === $key ) {
			throw new \Exception( 'ETSY_KEY environment variable does not exist.' );
		}

		return $key;
	}
}
