<?php
/**
 * Prokey_IP_Addresses_Metabox class
 * 
 * @package Prolocker\Metaboxes
 * @since 1.0.0
 */

namespace Prolocker\Metaboxes;

use Prolocker\Posts\Prolocker_Prokey_Post as Prokey;

/**
 * Class used to manage the metabox that displays a Prokey IP addreseses.
 * 
 * @since 1.0.0
 */
class Prokey_IP_Addresses_Metabox {
    /**
     * Creates an instance of Prokey_IP_Addresses_Metabox.
     * 
     * @since 1.0.0
     */
    public function __construct() {
        add_action( 'add_meta_boxes_prokey', [ $this, 'prolocker_add_prokey_ip_addresses_metabox' ] );
    }

    /**
     * Adds the prokeys details metabox.
     * 
     * Runs on the add_meta_boxes_prokey action hook.
     *
     * @since 1.0.0
     * @param WP_Post $post. The current post.
     * @return void
     */
    public function prolocker_add_prokey_ip_addresses_metabox( $post ) {
        add_meta_box( 
            PROLOCKER_PREFIX . 'prokey_ip_addreses', 
            esc_html__( 'IP Addresses', 'prolocker' ), 
            [ $this, 'prolocker_render_prokey_ip_addresses_metabox' ],
            Prokey::$post_type, 
            'normal', 
            'low' 
        );
    }

    /**
     * Renders the contents of the Prokey IP addresses metabox.
     *
     * @since 1.0.0
     * @return void
     */
    public function prolocker_render_prokey_ip_addresses_metabox() {
        require_once PROLOCKER_PLUGIN_DIR_PATH . 'templates/prokey-ip-addresses.php';
    }
}
