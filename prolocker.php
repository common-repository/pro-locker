<?php
/*
 * Plugin Name: ProLocker
 * Description: Lock your content at the expense of sharing. The more your visitor engages in unlocking mechanisms you set up, the more your visitor is able to see.
 * Version: 1.1.1
 * Requires at least: 5.0.0
 * Requires PHP: 5.4
 * Author: Frontier Themes
 * Author URI: https://www.frontier.dev/
 * License: GNU General Public License v2
 * License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: prolocker
 * Domain Path: /languages
 * Network: true
 */

/**
 * Plugin version.
 * 
 * @since 1.0.0
 * @var string PROLOCKER_VERSION.
 */
define( 'PROLOCKER_VERSION', '1.1.0' );

/**
 * Plugin prefix.
 * 
 * @since 1.0.0
 * @var string PROLOCKER_PREFIX.
 */
define( 'PROLOCKER_PREFIX', 'pl_' );

/**
 * Plugin directory URL.
 * 
 * @since 1.0.0
 * @var string PROLOCKER_PLUGIN_DIR_URL.
 */
define( 'PROLOCKER_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );

/**
 * Plugin directory path.
 * 
 * @since 1.0.0
 * @var string PROLOCKER_PLUGIN_DIR_PATH.
 */
define( 'PROLOCKER_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Plugin directory languages full path.
 * 
 * @since 1.0.0
 * @var string PROLOCKER_PLUGIN_LANGUAGES_FULL_PATH.
 */ 
define( 'PROLOCKER_PLUGIN_LANGUAGES_FULL_PATH', PROLOCKER_PLUGIN_DIR_PATH . 'languages/' );

/**
 * Plugin directory languages base path.
 * 
 * @since 1.0.0
 * @var string PROLOCKER_PLUGIN_LANGUAGES_BASE_PATH.
 */ 
define( 'PROLOCKER_PLUGIN_LANGUAGES_BASE_PATH', dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

// Checks if the Composer autoloader file exists.
if ( file_exists( PROLOCKER_PLUGIN_DIR_PATH . '/vendor/autoload.php' ) ) {
    // Requires the Composer autoloader file.
    require_once PROLOCKER_PLUGIN_DIR_PATH . '/vendor/autoload.php';
}

// Checks if the plugin's main class exists.
if ( class_exists( 'Prolocker\\Prolocker' ) ) {
    // Instatiates the plugin's main class and runs the plugin.
    $prolocker = new \Prolocker\Prolocker;
    $prolocker->run();

    register_activation_hook( __FILE__, [ $prolocker, 'activate' ] );
    register_deactivation_hook( __FILE__, [ $prolocker, 'deactivate' ] );
}
