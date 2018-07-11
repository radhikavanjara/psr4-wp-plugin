<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.chinmayaclix.com/radhika
 * @since             1.0.0
 * @package           Wp_Ccmt_Clix
 *
 * @wordpress-plugin
 * Plugin Name:       CCMT CLIX Plugin
 * Plugin URI:        http://www.chinmayaclix.com/wp-ccmt-clix
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Radhika & Suresh
 * Author URI:        http://www.chinmayaclix.com/radhika
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-ccmt-clix
 * Domain Path:       /languages
 */
use ccmt\clix\CcmtClix;
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );


/** 
 * Wrapper funtion for error_log to print logs
 */
if ( ! function_exists('write_log')) {
   function write_log ( $log )  {
      if ( is_array( $log ) || is_object( $log ) ) {
         error_log( print_r( $log, true ) );
      } else {
         error_log( $log );
      }
   }
}



// If we haven't loaded this plugin from Composer we need to add our own autoloader
if (!class_exists('ccmt\clix\CcmtClix')) {
    // Get a reference to our PSR-4 Autoloader function that we can use to add our
    // Ccmt namespace

	$autoloader = require_once('autoload.php');

    // Use the autoload function to setup our class mapping
	$autoloader('ccmt\\clix\\', __DIR__ . '/src/includes/');
	$autoloader('ccmt\\clix\\', __DIR__ . '/src/admin/');
	$autoloader('ccmt\\clix\\', __DIR__ . '/src/public/');
	$autoloader('ccmt\\clix\\', __DIR__ . '/src/widget/');
}

// We are now able to autoload classes under the Ccmt namespace so we
// can implement what ever functionality this plugin is supposed to have
$ccmtClix = new CcmtClix();
$ccmtClix ->init();
//write_log("CCMT CLix Plugin Inialized");
