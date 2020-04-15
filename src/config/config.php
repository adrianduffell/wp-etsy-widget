<?php
/**
 * WordPress Etsy Widget: Config interface.
 *
 * @package wp-etsy-widget
 */

namespace WP_Etsy_Widget;

/**
 * Provides configuration.
 */
interface Config {

	/**
	 * Get the etsy developer key.
	 *
	 * @return string Etsy developer key.
	 */
	function get_etsy_developer_key() : string;
}
