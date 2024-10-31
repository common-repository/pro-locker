<?php
/**
 * Prokey_Actions_Metabox class
 * 
 * @package Prolocker\Metaboxes
 * @since 1.0.0
 */

namespace Prolocker\Metaboxes; 

use Prolocker\Posts\Prolocker_Prokey_Post as Prokey;

/**
 * Class used to manage the metabox that display a Prokey actions.
 * 
 * @since 1.0.0
 */
class Prokey_Actions_Metabox {
    /**
     * Creates an instance of Prokey_Actions_Metabox.
     * 
     * @since 1.0.0
     */
    public function __construct() {
        add_action( 'add_meta_boxes_prokey', [ $this, 'prolocker_add_pro_key_actions_metabox' ] );
    }

    /**
     * Adds the Prokey actions metabox.
     * 
     * Runs on the add_meta_boxes_prokey action hook.
     *
     * @since 1.0.0
     * @param WP_Post $post. The current post.
     * @return void
     */
    public function prolocker_add_pro_key_actions_metabox( $post ) {
        add_meta_box( 
            PROLOCKER_PREFIX . 'prokey_actions', 
            esc_html__( 'Actions', 'prolocker' ), 
            [ $this, 'prolocker_render_prokey_actions_metabox' ], 
            ProKey::$post_type, 
            'side', 
            'high'
        );
    }

    /**
     * Renders the contents of the Prokey actions metabox.
     *
     * @since 1.0.0
     * @return void
     */
    public function prolocker_render_prokey_actions_metabox() {
        require_once PROLOCKER_PLUGIN_DIR_PATH . 'templates/prokey-actions.php';
    }
}
