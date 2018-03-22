<?php
/**
 * WordPress Etsy Widget: Etsy_Shop_Render interface.
 *
 * @package wp-etsy-widget
 */

namespace WP_Etsy_Widget;

/**
 * Renders an Etsy shop.
 */
interface Etsy_Shop_Render {

	/**
	 * Get the render.
	 *
	 * @param array $shop Array of shop data from REST API.
	 * @return string Markup fragment.
	 */
	function get( array $shop ) : string;
}
