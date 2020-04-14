<?php
/**
 * WordPress Etsy Widget: Widget_Input_Render interface.
 *
 * @package wp-etsy-widget
 */

declare( strict_types=1 );

namespace WP_Etsy_Widget;

/**
 * Renders a widget input.
 */
interface Widget_Input_Render {

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
	) :string;
}
