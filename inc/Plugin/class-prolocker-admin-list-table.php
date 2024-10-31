<?php
/**
 * Prolocker_Admin_List_Table class
 * 
 * @package Prolocker\Plugin
 * @since 1.0.0
 */

namespace Prolocker\Plugin;

use Prolocker\Posts\Prolocker_Proip_Post as Proip;
use Prolocker\Posts\Prolocker_Prokey_Post as Prokey;

/**
 * Class used to manage the custom display of admin list tables.
 * 
 * @since 1.0.0
 */
class Prolocker_Admin_List_Table {
    /**
     * Creates an instance of Prolocker_Admin_List_Table.
     * 
     * @since 1.0.0
     */
    public function __construct() {
        add_action( 'manage_posts_extra_tablenav', [ $this, 'prolocker_manage_posts_extra_tablenav' ] );
    }

    /**
     * Displays a custom template when there are no posts.
     *
     * Runs on the manage_posts_extra_tablenav action hook.
     * 
     * @since 1.0.0
     * @since 1.1.1 Removed CSS string echoing.
     * @global string $post_type. Current post type.
     * @param string $which Which part of the table.
     * @return void
     */
    public function prolocker_manage_posts_extra_tablenav( $which ) {
        global $post_type;
        $post_types = [ Prokey::$post_type, Proip::$post_type ];
        
        if ( in_array( $post_type, $post_types ) && 'bottom' === $which ) {
            $counts = (array) wp_count_posts( $post_type );
            
            if ( 
                ( $post_type === Prokey::$post_type && isset( $counts['publish'] ) && 0 >= $counts['publish'] ) ||
                ( $post_type === Proip::$post_type && isset( $counts['blacklisted'] ) && 0 >= $counts['blacklisted'] )
            ) {
                require_once PROLOCKER_PLUGIN_DIR_PATH . '/templates/no-posts.php';
            }
        }
    }
}
