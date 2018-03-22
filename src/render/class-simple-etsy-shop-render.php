<?php
/**
 * Etsy Widget for WordPress: WP_Gallery_Etsy_Shop_Render class.
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
			'<p style="line-height: 1"><b><img src="%s" style="float: left; width:37.5px; height:37.5px; margin-right: 1ex " />%s</b><br/><span style=" display:inline-block; padding-top: 0.5ex">%s</span><br style="clear:left;"></p>',
			esc_attr( $shop['icon_url_fullxfull'] ),
			esc_html( $shop['shop_name'] ),
			esc_html( $profile['city'] . ', ' . $profile['region'] )
		);
	}
}
