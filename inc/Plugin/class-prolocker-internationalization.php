<?php
/**
 * Prolocker_Internationalization class
 * 
 * @package Prolocker\Plugin
 * @since 1.0.0
 */

namespace Prolocker\Plugin;

/**
 * Class used to manage the plugin's internationalization.
 * 
 * @since 1.0.0
 */
class Prolocker_Internationalization {
    /**
     * Creates an instance of Prolocker_Internationalization.
     * 
     * @since 1.0.0
     */
    public function __construct() {
        add_action( 'plugins_loaded', [ $this, 'prolocker_load_textdomain' ] );
    }

    /**
     * Loads the plugin's textdomain.
     *
     * @since 1.0.0
     * @return void
     */
    public function prolocker_load_textdomain() {
        load_plugin_textdomain( 'prolocker', false, PROLOCKER_PLUGIN_LANGUAGES_BASE_PATH );
    }
}
