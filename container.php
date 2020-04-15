<?php
/**
 * Solid WordPress Functions: Pimple Dependency Injection Container script.
 *
 * @package wp-etsy-widget
 */

declare( strict_types=1 );

namespace WP_Etsy_Widget;

$container = new \Pimple\Container();

$container['etsy_api_client'] = function( $c ) {
	return new WP_Etsy_API_Client();
};

$container['config'] = function( $c ) {
	return new Env_Config();
};

$container['widget_input_render'] = function( $c ) {
	return new HTML5_Widget_Input_Render();
};

$container['etsy_shop_render'] = function( $c ) {
	return new Simple_Etsy_Shop_Render();
};

$container['gallery_render'] = function( $c ) {
	return new WP_Gallery_Render();
};

$container['widget'] = function( $c ) {
	return new Etsy_Latest_WP_Widget(
		$c['config'],
		$c['etsy_api_client'],
		$c['etsy_shop_render'],
		$c['gallery_render'],
		$c['widget_input_render']
	);
};

return $container;
