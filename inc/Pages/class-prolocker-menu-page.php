<?php
/**
 * Prolocker_Menu_Page class
 * 
 * @package Prolocker\Pages
 * @since 1.0.0
 */

namespace Prolocker\Pages;

/**
 * Class used to manage the creation of the ProLocker menu page.
 * 
 * @since 1.0.0
 */
class Prolocker_Menu_Page {
    /**
     * Menu slug.
     *
     * @since 1.0.0
     * @var string $menu_slug.
     */
    public static $menu_slug = 'prolocker';

    /**
     * Creates an instance of Prolocker_Menu_Page.
     * 
     * @since 1.0.0
     */
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'prolocker_add_menu_page' ] );
    }

    /**
     * Adds the menu page.
     *
     * @since 1.0.0
     * @return void
     */
    public function prolocker_add_menu_page() {
        add_menu_page(
            esc_html__( 'ProLocker', 'prolocker' ),
            esc_html__( 'ProLocker', 'prolocker' ),
            'manage_options',
            self::$menu_slug,
            null,
            'dashicons-lock',
            85
        );
    }
}
