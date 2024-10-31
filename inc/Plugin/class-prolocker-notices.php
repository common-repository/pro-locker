<?php
/**
 * Prolocker_Notices class
 * 
 * @package Prolocker\Plugin
 * @since 1.0.0
 */

namespace Prolocker\Plugin;

use Prolocker\Posts\Prolocker_Proip_Post as Proip;
use Prolocker\Posts\Prolocker_Prokey_Post as Prokey;

/**
 * Class used to manage admin notices.
 * 
 * @since 1.0.0
 */
class Prolocker_Notices {
    /**
     * Creates an instances of Prolocker_Notices.
     * 
     * @since 1.0.0
     */
    public function __construct() {
        add_action( 'admin_notices', [ $this, 'prolocker_admin_notices' ] );
    }

    /**
     * Calls functions to be run on the admin_notices action hook.
     *
     * @since 1.0.0
     * @return void
     */
    public function prolocker_admin_notices() {
        $this->prolocker_gutenberg_check_notice();
    }

    /**
     * Checks whether the Gutenberg plugin is activated or the current page 
     * uses Gutenberg and prints a notice to the admin screen.
     *
     * @since 1.0.0
     * @global WP_Post $post
     * @return boolean False if Gutenberg is the editor or post type is an ignored post type.
     */
    private function prolocker_gutenberg_check_notice() {
        global $post;

        if ( function_exists( 'is_gutenberg_page' ) && is_gutenberg_page() ) {
            return false;
        }

        $current_screen = get_current_screen();

        if ( method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor() ) {
            return false;
        }

        if ( method_exists( $current_screen, 'is_block_editor' ) && 
            false === $current_screen->is_block_editor() && 'post' === $current_screen->base ) {
            $ingnored_post_types = [
                Prokey::$post_type,
                Proip::$post_type,
                'product',
                'product_visibility',
                'shop_order',
                'shop_coupon',
                'shop_webhook'
            ];

            if ( in_array( $post->post_type, $ingnored_post_types ) ) {
                return false;
            }

            printf( 
                '<div class="%1$s"><p><strong>%2$s</strong> %3$s</p></div>', 
                'notice notice-warning is-dismissible', 
                esc_html__( 'ProLocker:', 'prolocker' ), 
                esc_html__( 'Gutenberg must be the default editor for the plugin to work correctly.', 'prolocker' ) 
            );
        }
    }
}
