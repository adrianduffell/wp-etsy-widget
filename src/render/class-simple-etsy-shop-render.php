<?php
/**
 * Etsy Widget for WordPress: Simple_Etsy_Shop_Render  class.
 *
 * @package wp-etsy-widget
 */

declare( strict_types=1 );

namespace WP_Etsy_Widget;

/**
 * Renders the branding of an Etsy shop as HTML.
 */
class Simple_Etsy_Shop_Render implements Etsy_Shop_Render {

	/**
	 * Get the render.
	 *
	 * @param array $shop Array of shop data from REST API.
	 * @return string Markup fragment.
	 */
	function get( array $shop ) : string {

		$profile = $shop['User']['Profile'];

		return sprintf(
			'<p class="etsy-widget-shop-container"><img class="etsy-widget-shop-icon" src="%s" /><span class="etsy-widget-shop-name">%s</span><br/><span class="etsy-widget-shop-location">%s</span></p>',
			esc_attr( $shop['icon_url_fullxfull'] ),
			esc_html( $shop['shop_name'] ),
			esc_html( $profile['city'] . ', ' . $profile['region'] )
		);
	}
}
