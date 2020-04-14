<?php
/**
 * WordPress Etsy Widget: Gallery_Render interface.
 *
 * @package wp-etsy-widget
 */

namespace WP_Etsy_Widget;

/**
 * Renders a gallery preview.
 */
interface Gallery_Render {

	/**
	 * Get the render.
	 *
	 * @param int    $columns Amount of columns to display the gallery.
	 * @param array  $items Array of images to display in the gallery. With keys:
	 *                      url: URL this image links to.
	 *                      title: Title of image.
	 *                      alt: Alt text for this image.
	 *                      image: URL to the image source.
	 * @param int    $total Total amount of images in the gallery.
	 * @param string $url URL to the full listing.
	 * @return string Markup fragment.
	 */
	function get( int $columns, array $items, int $total, string $url );
}
