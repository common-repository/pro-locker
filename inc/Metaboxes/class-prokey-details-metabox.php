<?php
/**
 * Prokey_Details_Metabox class
 * 
 * @package Prolocker\Metaboxes
 * @since 1.0.0
 */

namespace Prolocker\Metaboxes;

use Prolocker\Posts\Prolocker_Prokey_Post as Prokey;

/**
 * Class used to manage the metabox that displays a Prokey details.
 * 
 * @since 1.0.0
 */
class Prokey_Details_Metabox {
    /**
     * Creates an instance of Prokey_Details_Metabox.
     * 
     * @since 1.0.0
     */
    public function __construct() {
        add_action( 'add_meta_boxes_prokey', [ $this, 'prolocker_add_prokey_details_metabox' ] );
    }

    /**
     * Adds the Prokey details metabox.
     * 
     * Runs on the add_meta_boxes_prokey action hook.
     *
     * @since 1.0.0
     * @param WP_Post $post. The current post.
     * @return void
     */
    public function prolocker_add_prokey_details_metabox( $post ) {
        add_meta_box( 
            PROLOCKER_PREFIX . 'prokey_details', 
            esc_html__( 'Details', 'prolocker' ), 
            [ $this, 'prolocker_render_prokey_details_metabox' ],
            Prokey::$post_type, 
            'normal', 
            'high' 
        );
    }

    /**
     * Renders the contents of the Prokey details metabox.
     *
     * @since 1.0.0
     * @return void
     */
    public function prolocker_render_prokey_details_metabox() {
        require_once PROLOCKER_PLUGIN_DIR_PATH . 'templates/prokey-details.php';
    }
}
