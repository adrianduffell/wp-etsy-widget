<?php
/**
 * Class Test_Etsy_Latest_WP_Widget_Update
 *
 * @package Wp_Etsy_Widget
 */

declare( strict_types=1 );

namespace WP_Etsy_Widget;

/**
 * Etsy_Latest_WP_Widget::update test case.
 */
class Test_Etsy_Latest_WP_Widget_Update extends \WP_UnitTestCase {

	/**
	 * Pimple dependency injection container.
	 *
	 * @var \Pimple\Container
	 */
	private $container = null;

	/**
	 * Setup the test case.
	 */
	public function setup() {
		$container = new \Pimple\Container();
		$container['etsy_api_client'] = $container->factory(
			function( $c ) : Etsy_API_Client {
				return $this->createMock( Etsy_API_Client::class );
			}
		);

		$container['config'] = $container->factory(
			function( $c ) : Config {
				return $this->createMock( Config::class );
			}
		);

		$container['widget_input_render'] = $container->factory(
			function( $c ) : Widget_Input_Render {
				return $this->createMock( Widget_Input_Render::class );
			}
		);

		$container['etsy_shop_render'] = $container->factory(
			function( $c ) : Etsy_Shop_Render {
				return $this->createMock( Etsy_Shop_Render::class );
			}
		);

		$container['gallery_render'] = $container->factory(
			function( $c ) : Gallery_Render {
				return $this->createMock( Gallery_Render::class );
			}
		);

		$container['widget'] = $container->factory(
			function( $c ) : Etsy_Latest_WP_Widget {
				return new Etsy_Latest_WP_Widget(
					$c['config'],
					$c['etsy_api_client'],
					$c['etsy_shop_render'],
					$c['gallery_render'],
					$c['widget_input_render']
				);
			}
		);

		$this->container = $container;

		parent::setup();
	}

	/**
	 * A simple test with regular input.
	 */
	public function test_update() {

		$widget = $this->container['widget'];

		$a = array(
			'title'   => 'Latest Etsy Products',
			'shop'    => 'foo_shop',
			'columns' => 3,
			'rows'    => 3,
		);

		$test = $widget->update( $a, array() );

		$this->assertEqualSets( $test, $a );
	}

	/**
	 * Tests updating the title value.
	 *
	 * @param mixed $title Test title value from data provider.
	 * @param mixed $expected Expected title value from data provider.
	 * @dataProvider data_provider_update_title
	 */
	public function test_update_title( $title, $expected ) : void {
		$widget = $this->container['widget'];

		$new_instance = array(
			'title'   => $title,
			'shop'    => 'foo_shop',
			'columns' => 3,
			'rows'    => 3,
		);

		$test = $widget->update( $new_instance, array() );
		$this->assertSame( $expected, $test['title'] );
	}

	/**
	 * Provides data for test_update_title
	 */
	public function data_provider_update_title() : array {
		return array(
			'strips html'      => array(
				'Latest <b>Etsy</b> Products',
				'Latest Etsy Products',
			),
			'trims whitespace' => array(
				' Latest Etsy Products ',
				'Latest Etsy Products',
			),
			'trims new lines'  => array(
				"\nLatest Etsy Products\n",
				'Latest Etsy Products',
			),
		);
	}

	/**
	 * Tests updating the shop value.
	 *
	 * @param mixed $shop Test shop value from data provider.
	 * @param mixed $expected Expected shop value from data provider.
	 * @dataProvider data_provider_update_shop
	 */
	public function test_update_shop( $shop, $expected ) : void {
		$widget = $this->container['widget'];

		$new_instance = array(
			'title'   => 'Latest Etsy Products',
			'shop'    => $shop,
			'columns' => 1,
			'rows'    => 1,
		);

		$test = $widget->update( $new_instance, array() );
		$this->assertSame( $expected, $test['shop'] );
	}

	/**
	 * Provides data for test_update_shop
	 */
	public function data_provider_update_shop() : array {
		return array(
			'strips html'                        => array(
				'<b>foo</b>_shop',
				'foo_shop',
			),
			'trims whitespace'                   => array(
				' foo_shop ',
				'foo_shop',
			),
			'trims new lines'                    => array(
				"\nfoo_shop\n",
				'foo_shop',
			),
			'allows alhanumeric and underscores' => array(
				'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdevghijlmnopqrstuvwzyz0123456789_',
				'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdevghijlmnopqrstuvwzyz0123456789_',
			),
			'strips hyhens'                      => array(
				'foo-shop',
				'fooshop',
			),
		);
	}

	/**
	 * Tests updating the columns value.
	 *
	 * @param mixed $columns Test columns value from data provider.
	 * @param mixed $expected Expected columns value from data provider.
	 * @dataProvider data_provider_update_columns
	 */
	public function test_update_columns( $columns, $expected ) : void {
		$widget = $this->container['widget'];

		$new_instance = array(
			'title'   => 'Latest Etsy Products',
			'shop'    => 'foo_shop',
			'columns' => $columns,
			'rows'    => '3',
		);

		$test = $widget->update( $new_instance, array() );
		$this->assertSame( $expected, $test['columns'] );
	}

	/**
	 * Provides data for test_update_columns
	 */
	public function data_provider_update_columns() : array {
		return array(
			'strips non-numeric'        => array(
				'ABC10def!@#$%^&*()_-<>',
				10,
			),
			'casts from string'         => array(
				'10',
				10,
			),
			'strips decimals 1'         => array(
				1.5,
				1,
			),
			'strips decimals 2'         => array(
				1.9,
				1,
			),
			'strips decimals 3'         => array(
				1.1,
				1,
			),
			'reduces large number'      => array(
				99999,
				10,
			),
			'increases zero value'      => array(
				0,
				1,
			),
			'increases negative number' => array(
				-1,
				1,
			),
			'converts null value'       => array(
				null,
				1,
			),
		);
	}

	/**
	 * Tests updating the rows value.
	 *
	 * @param mixed $rows Test rows value from data provider.
	 * @param mixed $expected Expected rows value from data provider.
	 * @dataProvider data_provider_update_rows
	 */
	public function test_update_rows( $rows, $expected ) {
		$widget = $this->container['widget'];

		$new_instance = array(
			'title'   => 'Latest Etsy Products',
			'shop'    => 'foo_shop',
			'columns' => '1',
			'rows'    => $rows,
		);

		$test = $widget->update( $new_instance, array() );
		$this->assertSame( $expected, $test['rows'] );
	}

	/**
	 * Provides data for test_update_rows
	 */
	public function data_provider_update_rows() : array {
		return array(
			'strips non-numeric'        => array(
				'ABC10def!@#$%^&*()_-<>',
				10,
			),
			'casts from string'         => array(
				'10',
				10,
			),
			'strips decimals 1'         => array(
				1.5,
				1,
			),
			'strips decimals 2'         => array(
				1.9,
				1,
			),
			'strips decimals 3'         => array(
				1.1,
				1,
			),
			'reduces large number'      => array(
				99999,
				10,
			),
			'increases zero value'      => array(
				0,
				1,
			),
			'increases negative number' => array(
				-1,
				1,
			),
			'converts null value'       => array(
				null,
				1,
			),
		);
	}
}
