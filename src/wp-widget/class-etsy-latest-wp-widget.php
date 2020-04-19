<?php
/**
 * Extended Tweets for WordPress: User_Timeline_WP_Widget class.
 *
 * @package wp-extended-tweets
 */

declare( strict_types=1 );

namespace WP_Etsy_Widget;

/**
 * A WordPress widget that displays a Twitter User Timeline.
 *
 * @see https://codex.wordpress.org/Widgets_API
 */
class Etsy_Latest_WP_Widget extends \WP_Widget {

	/**
	 * Injected dependency.
	 *
	 * @var Config
	 */
	private $config;

	/**
	 * Injected dependency.
	 *
	 * @var Etsy_API_Client
	 */
	private $etsy_api_client;

	/**
	 * Injected dependency.
	 *
	 * @var Etsy_Shop_Render
	 */
	private $etsy_shop_render;

	/**
	 * Injected dependency.
	 *
	 * @var Gallery_Render
	 */
	private $gallery_render;

	/**
	 * Injected dependency.
	 *
	 * @var Widget_Input_Render
	 */
	private $widget_input_render;

	/**
	 * Constructor, provides dependency injection.
	 *
	 * Additionally, widget configuration options are established via the
	 * parent constructor.
	 *
	 * @param Config              $config Dependency.
	 * @param Etsy_API_Client     $etsy_api_client Dependency.
	 * @param Etsy_Shop_Render    $etsy_shop_render Dependency.
	 * @param Gallery_Render      $gallery_render Dependency.
	 * @param Widget_Input_Render $widget_input_render Dependency.
	 */
	public function __construct(
		Config $config,
		Etsy_API_Client $etsy_api_client,
		Etsy_Shop_Render $etsy_shop_render,
		Gallery_Render $gallery_render,
		Widget_Input_Render $widget_input_render
	) {
		$this->config              = $config;
		$this->etsy_api_client     = $etsy_api_client;
		$this->etsy_shop_render    = $etsy_shop_render;
		$this->gallery_render      = $gallery_render;
		$this->widget_input_render = $widget_input_render;

		$widget_options = array(
			'classname'                   => 'etsy_latest1',
			'description'                 => __( 'Display the latest products on an Etsy store.', 'wp_etsy_plugin' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct(
			'etsy_latest1',
			'Display the latest products on an Etsy store.',
			$widget_options
		);

	}

	/**
	 * Widget output
	 *
	 * @param array $args Display arguments including `before_title`,
	 *        `after_title`, `before_widget`, `after_widget` as keys.
	 * @param array $instance The settings for the instance of the widget.
	 * @return void
	 */
	public function widget( $args, $instance ) {

		if ( empty( $instance['shop'] ) ) {
			return;
		}

		echo $args['before_widget']; // WPCS: XSS ok.
		echo $args['before_title']; // WPCS: XSS ok.
		echo apply_filters( // WPCS: XSS ok.
			'widget_title',
			$instance['title']
		);
		echo $args['after_title']; // WPCS: XSS ok.

		$preview_count = ( $instance['columns'] * $instance['rows'] ) - 1;

		$endpoint = sprintf( '/shops/%s', $instance['shop'] );

		$params = array(
			'includes' => sprintf(
				'Listings:%s:0:active/Images,User/Profile',
				$preview_count
			),
			'api_key'  => $this->config->get_etsy_developer_key(),
		);

		$response = $this->etsy_api_client->get( $endpoint, $params );

		if ( is_null( $response ) ) {
			return;
		}

		$shop = $response['results'][0];

		$gallery_items = array_map(
			function( array $listing ) : array {
				return array(
					'image' => $listing['Images'][0]['url_170x135'],
					'title' => $listing['title'],
					'alt'   => '', // No alt text supplied by etsy.
					'url'   => $listing['url'],
				);
			},
			$shop['Listings']
		);

		echo $this->etsy_shop_render->get( $shop );

		echo $this->gallery_render->get(
			$instance['columns'],
			$gallery_items,
			$shop['listing_active_count'],
			$shop['url']
		);

		echo $args['after_widget']; // WPCS: XSS ok.

	}

	/**
	 * Admin form
	 *
	 * @param array $instance Current settings for instance.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args(
			$instance,
			array(
				'title'   => '',
				'shop'    => '',
				'columns' => '3',
				'rows'    => '3',
			)
		);

		echo $this->widget_input_render->get( // WPCS: XSS ok.
			__( 'Title', 'extended_tweets' ),
			'text',
			$this->get_field_id( 'title' ),
			$this->get_field_name( 'title' ),
			$instance['title']
		);

		echo $this->widget_input_render->get( // WPCS: XSS ok.
			__( 'Etsy Shop Name', 'extended_tweets' ),
			'text',
			$this->get_field_id( 'shop' ),
			$this->get_field_name( 'shop' ),
			$instance['shop']
		);

		echo $this->widget_input_render->get( // WPCS: XSS ok.
			__( 'Grid Columns (maximum of 10)', 'extended_tweets' ),
			'number',
			$this->get_field_id( 'columns' ),
			$this->get_field_name( 'columns' ),
			$instance['columns']
		);

		echo $this->widget_input_render->get( // WPCS: XSS ok.
			__( 'Grid Rows (maximum of 10)', 'extended_tweets' ),
			'number',
			$this->get_field_id( 'rows' ),
			$this->get_field_name( 'rows' ),
			$instance['rows']
		);

	}

	/**
	 * Admin save
	 *
	 * @param array $new_instance New settings for this instance.
	 * @param array $old_instance Previous settings this instance.
	 * @return array Settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$new_title    = strip_tags( trim( $new_instance['title'] ) );
		$new_username = preg_replace(
			'/[^A-Za-z0-9_]/',
			'',
			strip_tags( $new_instance['shop'] )
		);
		$new_columns  = (int) filter_var(
			$new_instance['columns'],
			FILTER_SANITIZE_NUMBER_FLOAT,
			FILTER_FLAG_ALLOW_FRACTION
		);
		$new_rows     = (int) filter_var(
			$new_instance['rows'],
			FILTER_SANITIZE_NUMBER_FLOAT,
			FILTER_FLAG_ALLOW_FRACTION
		);

		if ( $new_rows < 1 ) {
			$new_rows = 1;
		}

		if ( $new_rows > 10 ) {
			$new_rows = 10;
		}

		if ( $new_columns < 1 ) {
			$new_columns = 1;
		}

		if ( $new_columns > 10 ) {
			$new_columns = 10;
		}

		return array(
			'title'   => $new_title,
			'shop'    => $new_username,
			'rows'    => $new_rows,
			'columns' => $new_columns,
		);
	}

}
