<?php
/**
 * Plugin Name: Etsy Widget
 * Description: A widget to display Etsy products.
 * Version: 0.0.1
 * Requires at least: 4.9
 * Tested up to: 4.9
 * Requires PHP: 7.1
 * Text Domain: etsy_widget
 *
 * @package wp-etsy-widget
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( version_compare( PHP_VERSION, '7.1', '<' ) ) {
	exit;
}

require dirname( __FILE__ ) . '/vendor/autoload.php';

// Bootstrap script.
( function() {

	// Dependency Injection Container.
	$container = require 'container.php';

	// Hooks.
	add_action(
		'widgets_init',
		function() use ( $container ) {
			register_widget( $container['widget'] );
		}
	);

	add_action(
		'wp_enqueue_scripts',
		function() {
			wp_enqueue_style(
				'etsy_widget',
				plugins_url( '/public/etsy-widget.css', __FILE__ ),
				array(),
				'1.0.0'
			);
		}
	);

} )();

