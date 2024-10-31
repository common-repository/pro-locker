<?php
/**
 * Prolocker class
 * 
 * @package Prolocker
 * @since 1.0.0
 */

namespace Prolocker;

use Prolocker\Plugin\Prolocker_Enqueue;
use Prolocker\Plugin\Prolocker_Notices;
use Prolocker\Plugin\Prolocker_Key_Handler;
use Prolocker\Plugin\Prolocker_Post_Statuses;
use Prolocker\Plugin\Prolocker_Block_Categories;
use Prolocker\Plugin\Prolocker_Admin_List_Table;
use Prolocker\Plugin\Prolocker_Internationalization;

use Prolocker\Posts\Prolocker_Prokey_Post;
use Prolocker\Posts\Prolocker_Proip_Post;

use Prolocker\Pages\Prolocker_Menu_Page;

use Prolocker\Blocks\Prolocker_Hit_Counter_Block;

use Prolocker\Metaboxes\Prokey_Details_Metabox;
use Prolocker\Metaboxes\Prokey_Actions_Metabox;
use Prolocker\Metaboxes\Prokey_IP_Addresses_Metabox;

use Prolocker\Rest\Prolocker_Proips_REST_Controller;

/**
 * Main class used as the entry point for the plugin's functionality.
 * 
 * @since 1.0.0
 */
class Prolocker {
    /**
     * Loaded container.
     *
     * @since 1.0.0
     * @var boolean $loaded.
     */
    private static $loaded = false;

    /**
     * Creates an instance of Prolocker.
     * 
     * @since 1.0.0
     */
    public function __construct() {
        /**
         * This is strictly necessary, otherwise, when viewing single post pages and attachments 
         * the functionality of the plugin is executed incorrectly in Firefox.
         * 
         * @link https://wordpress.stackexchange.com/questions/27585/what-would-cause-the-wp-action-to-fire-twice-per-page-but-only-once-per-post
         */
        remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
        add_action( 'rest_api_init', [ $this, 'register_rest_routes' ] );
    }

    /**
     * Runs the plugin functionality.
     *
     * @since 1.0.0
     * @return void
     */
    public function run() {
        if ( self::$loaded ) {
            return;
        }

        self::$loaded = true;

        new Prolocker_Enqueue;
        new Prolocker_Notices;
        new Prolocker_Key_Handler;
        new Prolocker_Post_Statuses;
        new Prolocker_Block_Categories;
        new Prolocker_Admin_List_Table;
        new Prolocker_Internationalization;

        new Prolocker_Prokey_Post;
        new Prolocker_Proip_Post;

        new Prolocker_Menu_Page;

        new Prolocker_Hit_Counter_Block;

        new Prokey_Details_Metabox;
        new Prokey_Actions_Metabox;
        new Prokey_IP_Addresses_Metabox;
    }

    /**
     * Runs when the register_activation_hook function is called.
     *
     * @since 1.0.0
     * @return void
     */
    public function activate() {
        Prolocker_Prokey_Post::prolocker_register_custom_post_type();
        Prolocker_Proip_Post::prolocker_register_custom_post_type();
        flush_rewrite_rules();
    }

    /**
     * Runs when the register_deactivation_hook function is called.
     *
     * @since 1.0.0
     * @return void
     */
    public function deactivate() {
        Prolocker_Prokey_Post::prolocker_unregister_custom_post_type();
        Prolocker_Proip_Post::prolocker_unregister_custom_post_type();
        flush_rewrite_rules();
    }

    /**
     * Registers the plugin custom REST routes.
     *
     * @since 1.0.0
     * @return void
     */
    public function register_rest_routes() {
        $prolocker_proips_rest_controller = new Prolocker_Proips_REST_Controller;
        $prolocker_proips_rest_controller->register_routes();
    }
}
