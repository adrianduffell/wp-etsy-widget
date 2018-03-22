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
class HTML5_Widget_Input_Render implements Widget_Input_Render {

	/**
	 * Get the render.
	 *
	 * @param string $label Label for the field.
	 * @param string $field_type Type of field.
	 * @param string $field_id Field ID.
	 * @param string $field_name Field name.
	 * @param string $field_value Field value.
	 * @return string Markup fragment.
	 */
	function get(
		string $label,
		string $field_type,
		string $field_id,
		string $field_name,
		string $field_value
	) : string {
		return sprintf(
			'<p><label for="%s">%s</label><input id="%s" class="widefat" type="%s" name="%s" value="%s" /></p>',
			esc_attr( $field_id ),
			esc_html( $label ),
			esc_attr( $field_id ),
			esc_attr( $field_type ),
			esc_attr( $field_name ),
			esc_attr( $field_value )
		);
	}
}
