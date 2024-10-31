<?php
/**
 * Prolocker_Enqueue class
 * 
 * @package Prolocker\Plugin
 * @since 1.0.0
 */

namespace Prolocker\Plugin;

use Prolocker\Posts\Prolocker_Proip_Post as Proip;
use Prolocker\Posts\Prolocker_Prokey_Post as Prokey;

/**
 * Class used to manage the registering and enqueueing of scripts.
 * 
 * @since 1.0.0
 */
class Prolocker_Enqueue {
    /**
     * Creates an instance of Prolocker_Enqueue.
     * 
     * @since 1.0.0
     */
    public function __construct() {
        add_action( 'admin_enqueue_scripts', [ $this, 'prolocker_admin_enqueue_scripts' ] );
    }

    /**
     * Registers and enqueues CSS and/or JS files.
     *
     * @since 1.0.0
     * @global string $post_type. The current post type.
     * @param string $hook. The current admin page.
     * @return void
     */
    public function prolocker_admin_enqueue_scripts( $hook ) {
        global $post_type;
        $post_types = [ Prokey::$post_type, Proip::$post_type ];
        
        wp_register_style( 
            PROLOCKER_PREFIX . 'style', 
            PROLOCKER_PLUGIN_DIR_URL . 'assets/css/style.css', 
            [ 'wp-components' ], 
            PROLOCKER_VERSION 
        );

        wp_register_script( 
            PROLOCKER_PREFIX . 'ip-addresses', 
            PROLOCKER_PLUGIN_DIR_URL . 'assets/js/ip-addresses.js', 
            [ 'wp-element', 'wp-components', 'wp-i18n', 'wp-api-fetch' ], 
            PROLOCKER_VERSION, 
            true 
        );
        wp_set_script_translations( 
            PROLOCKER_PREFIX . 'ip-addresses', 
            'prolocker', 
            PROLOCKER_PLUGIN_LANGUAGES_FULL_PATH 
        );
        
        wp_register_script( 
            PROLOCKER_PREFIX . 'blacklisted-add-new', 
            PROLOCKER_PLUGIN_DIR_URL . 'assets/js/blacklisted-add-new.js', 
            [ 'wp-element', 'wp-components', 'wp-i18n', 'wp-api-fetch', 'wp-url' ], 
            PROLOCKER_VERSION, 
            true 
        );
        wp_set_script_translations( 
            PROLOCKER_PREFIX . 'blacklisted-add-new', 
            'prolocker', 
            PROLOCKER_PLUGIN_LANGUAGES_FULL_PATH 
        );
        
        if ( in_array( $post_type, $post_types ) ) {
            wp_enqueue_style( PROLOCKER_PREFIX . 'style' );
        }

        if ( 'post.php' === $hook && $post_type === Prokey::$post_type ) {
            wp_enqueue_script( PROLOCKER_PREFIX . 'ip-addresses' );
        }

        if ( 'edit.php' === $hook && $post_type === Proip::$post_type ) {
            wp_enqueue_script( PROLOCKER_PREFIX . 'blacklisted-add-new' );
        }
    }
}
