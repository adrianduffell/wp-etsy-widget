<?php
/**
 * Etsy Widget for WordPress: WP_Gallery_Render class.
 *
 * @package wp-etsy-widget
 */

declare( strict_types=1 );

namespace WP_Etsy_Widget;

/**
 * Renders a gallery preview using WordPress stylesheet classes.
 */
class WP_Gallery_Render implements Gallery_Render {

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
	function get( int $columns, array $items, int $total, string $url ) : string {
		$svg = $this->svg_total( $total, $url );

		$open = sprintf(
			'<div class="gallery gallery-columns-%s gallery-size-thumbnail">',
			esc_attr( $columns )
		);

		$images = '';

		foreach ( $items as $item ) {
			$images .= sprintf(
				'<figure class="gallery-item"><div class="gallery-icon landscape"><a href="%s" title="%s"><img src="%s" alt="%s" /></a></div></figure>',
				esc_attr( $item['url'] ),
				esc_attr( $item['title'] ),
				esc_attr( $item['image'] ),
				esc_attr( $item['alt'] )
			);
		}

		$images .= sprintf(
			'<figure class="gallery-item"><div class="gallery-icon landscape"><a href="%s"><img src=\'data:image/svg+xml;utf-8,%s\'></a></div></figure>',
			esc_attr( $url ),
			$svg
		);

		$close = '</div>';

		return $open . $images . $close;

	}

	/**
	 * Render an SVG used for displaying the total amount of items.
	 *
	 * @param int $total Total to display.
	 * @return string Markup fragment.
	 */
	private function svg_total( int $total ) : string {
		return sprintf(
			'
			<svg xmlns="http://www.w3.org/2000/svg" width="170" height="135" viewBox="0 0 170 135">
				<rect width="100%%" height="100%%" fill="#cccccc" fill-opacity="0.15" />
				<text x="85" y="70" font-family="sans-serif" font-size="50" text-anchor="middle" fill="#E55400" text-rendering="optimizeLegibility">
					%s
				</text>
				<text x="85" y="100" font-family="sans-serif" font-size="30" text-anchor="middle" fill="#E55400" text-rendering="optimizeLegibility">
					items
				</text>
			</svg>',
			esc_html( $total )
		);
	}
}
