<?php
/**
 * Prolocker_Hit_Counter_Block class
 * 
 * @package Prolocker\Blocks
 * @since 1.0.0
 */

namespace Prolocker\Blocks;

/**
 * Class used to manage the Hit Counter block functionality.
 * 
 * @since 1.0.0
 */
class Prolocker_Hit_Counter_Block {
    /**
     * Creates an instance of Prolocker_Hit_Counter_Block.
     * 
     * @since 1.0.0
     */
    public function __construct() {
        add_action( 'init', [ $this, 'prolocker_register_hit_counter_block' ] );
    }

    /**
     * Registers the Hit Counter block and its scripts. 
     *
     * @since 1.0.0
     * @return void
     */
    public function prolocker_register_hit_counter_block() {
        $theme = wp_get_theme();

        wp_register_style( 
            PROLOCKER_PREFIX . 'locking-block', 
            PROLOCKER_PLUGIN_DIR_URL . 'assets/css/locking-block.css', 
            [], 
            PROLOCKER_VERSION 
        );

        wp_register_style( 
            PROLOCKER_PREFIX . 'editor-hit-counter-block', 
            PROLOCKER_PLUGIN_DIR_URL . 'assets/css/editor-hit-counter-block.css', 
            [], 
            PROLOCKER_VERSION 
        );

        wp_register_script( 
            PROLOCKER_PREFIX . 'editor-hit-counter-block', 
            PROLOCKER_PLUGIN_DIR_URL . 'assets/js/hit-counter-block.js', 
            [
                'wp-blocks',
                'wp-element',
                'wp-editor',
                'wp-components',
                'wp-i18n'
            ], 
            PROLOCKER_VERSION, 
            true 
        );

        wp_set_script_translations( 
            PROLOCKER_PREFIX . 'editor-hit-counter-block', 
            'prolocker', 
            PROLOCKER_PLUGIN_LANGUAGES_FULL_PATH 
        );

        wp_localize_script( 
            PROLOCKER_PREFIX . 'editor-hit-counter-block', 
            'prolocker', 
            [
                'theme' => $theme->name
            ] 
        );

        register_block_type( 'prolocker/hit-counter', [
            'style'           => PROLOCKER_PREFIX . 'locking-block',
            'editor_style'    => PROLOCKER_PREFIX . 'editor-hit-counter-block',
            'editor_script'   => PROLOCKER_PREFIX . 'editor-hit-counter-block',
            'render_callback' => [ $this, 'prolocker_render_hit_counter_block' ],
            'attributes'      => [
                'count'   => [ 'type' => 'number', 'default' => 5 ],
                'message' => [ 
                    'type'    => 'string', 
                    /* translators: Number of times the URL has to be shared. */
                    'default' => sprintf( esc_html__( 'This content is locked. Share the following URL %s times to unlock it.', 'prolocker' ), '{count}' ) 
                ]
            ]
        ] );
    }

    /**
     * Renders the contents of the Hit Counter block on the front end.
     *
     * @since 1.0.0
     * @since 1.0.1 Removed unused code.
     * @global Prolocker\Key $prokey.
     * @param array $attributes
     * @param string $content
     * @return string|null The contents of the Hit Counter block. Null when the global $prokey variable is null.
     */
    public function prolocker_render_hit_counter_block( $attributes, $content ) {
        global $prokey;

        if ( $prokey ) {
            $count             = (int) $attributes['count'];
            $message           = $attributes['message'];
            $hit_count         = $prokey->get_hit_count();
            $identifier        = $prokey->get_identifier();
            $post_id           = $prokey->get_post_id();
            $formatted_message = ( 0 === $hit_count ) ? str_replace( '{count}', $count, $message ) : str_replace( '{count}', $count - $hit_count, $message );
            $locked_message    = sprintf( wp_kses_post( '%1$s<br><br>%2$s' ), $formatted_message, add_query_arg( 'pk', $identifier, get_permalink( $post_id ) ) );
                        
            if ( $count > $hit_count ) {
                $theme     = wp_get_theme();
                $classname = 'prolocker-locking-block';
                
                switch ( $theme->name ) {
                    case 'Twenty Nineteen':
                        $classname .= ' prolocker-locking-block--twenty-nineteen';
                        break;
                    case 'Twenty Twenty':
                        $classname .= ' prolocker-locking-block--twenty-twenty';
                        break;
                    case 'Go First':
                        $classname .= ' prolocker-locking-block--go-first';
                        break;
                }

                return sprintf( '<p class="%1$s">%2$s</p>', $classname, $locked_message );
            }

            return $content;
        }

        return null;
    }
}
