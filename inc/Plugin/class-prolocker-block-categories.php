<?php
/**
 * Prolocker_Block_Categories class
 * 
 * @package Prolocker\Plugin
 * @since 1.0.0
 */

namespace Prolocker\Plugin;

/**
 * Class used to manage Gutenberg block categories.
 * 
 * @since 1.0.0
 */
class Prolocker_Block_Categories {
    /**
     * Creates an instance of Prolocker_Block_Categories.
     * 
     * @since 1.0.0
     */
    public function __construct() {
        add_filter( 'block_categories', [ $this, 'prolocker_block_categories' ], 10, 2 );
    }

    /**
     * Implements the ProLocker block category for Gutenberg.
     *
     * @since 1.0.0
     * @param array $categories
     * @param string $post
     * @return array List of categories.
     */
    public function prolocker_block_categories( $categories, $post ) {
        return array_merge( $categories, [
            [
                'slug'  => 'prolocker',
                'title' => esc_html__( 'ProLocker', 'prolocker' ),
                'icon'  => 'dashicons-admin-network'
            ],
        ] );
    }
}
