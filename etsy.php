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

namespace WP_Etsy_Widget;

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

	add_action( 'init', __NAMESPACE__ . '\register_settings' );
	add_action( 'admin_menu', __NAMESPACE__ . '\admin_menu' );
	add_action( 'admin_print_scripts-settings_page_wpreact_page', __NAMESPACE__ . '\admin_print_scripts' );
} )();

/**
 * Hook to provide admin menu and page.
 */
function admin_menu() {
	add_options_page(
		__( 'WP Etsy Listings', 'wpreact' ),
		__( 'WP Etsy Listings', 'wpreact' ),
		'manage_options',
		'wpreact_page',
		function() {
			echo '<div id="wp-etsy-listings-container"></div>';
		}
	);
}

/**
 * Hook to provide assets.
 */
function admin_print_scripts() {
	$build = require __DIR__ . '/dist/admin.asset.php';
	wp_enqueue_style( 'wp-components' );
	wp_enqueue_script( 'wp-etsy-listings-admin', plugins_url( './dist/admin.js', __FILE__ ), $build['dependencies'], $build['version'], true );
}

/**
 * Hook to provide settings.
 */
function register_settings() {
	register_setting(
		'codeinwp_settings',
		'wp_etsy_listings_shop',
		array(
			'type'         => 'string',
			'show_in_rest' => true,
		)
	);
}
