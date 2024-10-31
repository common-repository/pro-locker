<?php
/**
 * Prolocker_Post_Statuses class
 * 
 * @package Prolocker\Plugin
 * @since 1.0.0
 */

namespace Prolocker\Plugin;

/**
 * Class used to manage custom post statuses.
 * 
 * @since 1.0.0
 */
class Prolocker_Post_Statuses {
    /**
     * Creates an instance of Prolocker_Post_Statuses.
     * 
     * @since 1.0.0
     */
    public function __construct() {
        add_action( 'init', [ $this, 'prolocker_register_post_status' ] );
    }

    /**
     * Registers the blacklisted custon post status.
     * 
     * Runs on the init action hook.
     *
     * @since 1.0.0
     * @return void
     */
    public function prolocker_register_post_status() {
        register_post_status( 'blacklisted', [
            'label'                     => esc_html__( 'Blacklisted', 'prolocker' ),
            /* translators: Label count. */
            'label_count'               => _n_noop( 'All <span class="count">(%s)</span>', 'All <span class="count">(%s)</span>', 'prolocker' ),
            'exclude_from_search'       => false,
            'public'                    => true,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true
        ] );
    }
}
