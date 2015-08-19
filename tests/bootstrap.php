<?php

$_tests_dir = getenv('WP_TESTS_DIR');
if ( !$_tests_dir ) $_tests_dir = '/tmp/wordpress-tests-lib';

require_once $_tests_dir . '/includes/functions.php';

function _manually_load_dependencies()
{

	$local_plugin_directory = dirname( dirname( dirname( __FILE__ ) ) );

	$dependencies = require __DIR__ . '/dependencies-array.php';
	foreach ( $dependencies as $k => $dependency ){
		if ( is_dir( $local_plugin_directory .'/' . $k ) ) {
			require $local_plugin_directory .'/' . $dependency['include'];
			echo "Loaded $k\n";
		} elseif ( is_dir( WP_PLUGIN_DIR . '/' . $k ) ) {
			require WP_PLUGIN_DIR .'/' . $dependency['include'];
			echo "Loaded $k\n";
		} else {
			echo "COULD NOT LOAD $k\n";
		}
	}
}
tests_add_filter( 'muplugins_loaded', '_manually_load_dependencies' );

function _install_woocommerce() {
	$local_plugin_directory = dirname( dirname( dirname( __FILE__ ) ) );

	if ( ! is_dir( $local_plugin_directory .'/woocommerce' ) ) {
		$local_plugin_directory = WP_PLUGIN_DIR;
	}

	define( 'WP_UNINSTALL_PLUGIN', true );
	include( $local_plugin_directory . '/woocommerce/uninstall.php' );

	WC_Install::install();
	update_option( 'woocommerce_calc_shipping', 'yes' );
	$GLOBALS['wp_roles']->reinit();
}

tests_add_filter( 'setup_theme', '_install_woocommerce' );

function _manually_load_plugin() {
	require dirname( __FILE__ ) . '/../hello-woocommerce.php';
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

require $_tests_dir . '/includes/bootstrap.php';

