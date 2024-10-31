<?php
/**
 * Prolocker_Key_Handler class
 * 
 * @package Prolocker\Plugin
 * @since 1.0.0
 */

namespace Prolocker\Plugin;

use Prolocker\Prolocker_Key as Key;
use Prolocker\Posts\Prolocker_Proip_Post as Proip;
use Prolocker\Posts\Prolocker_Prokey_Post as Prokey;
use Prolocker\Plugin\Prolocker_Helpers as Helpers;

/**
 * Class used to manage the key functionality.
 * 
 * @since 1.0.0
 */
class Prolocker_Key_Handler {
    /**
     * Client's IP address.
     *
     * @since 1.0.0
     * @var string $client_ip
     */
    private $client_ip;

    /**
     * Client's IP ID.
     *
     * @since 1.0.0
     * @var int $ip_id
     */
    private $ip_id;

    /**
     * Creates an instance of Prolocker_Key_Handler.
     * 
     * @since 1.0.0
     */
    public function __construct() {
        add_action( 'wp', [ $this, 'prolocker_wp' ] );
    }

    /**
     * Runs functionality on the wp action hook.
     *
     * @since 1.0.0
     * @since 1.1.1 Added sanitation for $_GET values.
     * @global WP_Post $post
     * @global Prolocker\Key $prokey
     * @return void When the client's IP address is empty or the client's IP address is blacklisted.
     */
    public function prolocker_wp() {
        global $post;

        if ( is_singular() && has_block( 'prolocker/hit-counter', $post ) ) {
            global $prokey;
            $this->client_ip = Helpers::get_client_ip_address();
                                                
            if ( empty( $this->client_ip ) ) {
                $prokey = false;
                return;
            }
            
            $ip_address_slug = str_replace( '.', '-', $this->client_ip );
            $posts           = get_posts( [
                'name'           => $ip_address_slug,
                'post_type'      => Proip::$post_type,
                'post_status'    => 'any',
                'posts_per_page' => 1,
            ] );
                        
            if ( $posts && 'blacklisted' === get_post_status( $posts[0] ) ) {
                $prokey = false;
                return;
            } else if ( $posts && 'blacklisted' !== get_post_status( $posts[0] ) ) {
                $this->ip_id = $posts[0]->ID;
            } else {
                $this->ip_id = 0;
            }

            $pk     = sanitize_text_field( $_GET['pk'] );
            $prokey = ( null === $pk ) ? $this->get_key() : $this->get_key( $pk );

            if ( empty( $prokey ) ) {
                $prokey = $this->create_key();
            }
        }
    }

    /**
     * Creates a new key.
     *
     * @since 1.0.0
     * @global WP_Post $post.
     * @return null|object When WP_Error occurs. The prolock data.
     */
    private function create_key() {
        global $post;
        
        $args = [
            'post_title'     => uniqid( mt_rand( 0000, 9999 ) ),
            'post_status'    => 'publish',
            'post_type'      => Prokey::$post_type,
            'comment_status' => 'closed',
            'ping_status'    => 'closed',
            'meta_input'     => [
                PROLOCKER_PREFIX . 'pro_key_client_ip_address' => $this->client_ip,
                PROLOCKER_PREFIX . 'pro_key_post_id'           => $post->ID,
                PROLOCKER_PREFIX . 'pro_key_hit_count'         => 0,
                PROLOCKER_PREFIX . 'pro_key_ip_ids'            => []
            ]
        ];

        $result_id = wp_insert_post( $args, true );

        if ( is_wp_error( $result_id ) ) {
            return null;
        }

        $prokey = get_post( $result_id );

        return $this->set_key_data( $prokey );
    }

    /**
     * Gets a key.
     *
     * @since 1.0.0
     * @global WP_Post $post.
     * @param string $identifier. Optional. The key identifier.
     * @return null|object When result is empty. The Prokey data.
     */
    private function get_key( $identifier = null ) {
        global $post;

        if ( $identifier ) {
            $identifier_result = $this->handle_identifier( $identifier );

            if ( 'IDENTIFIER_HANDLED' !== $identifier_result ) {
                return $identifier_result;
            }
        }

        $args = [
            'numberposts' => 1,
            'post_status' => 'publish',
            'post_type'   => Prokey::$post_type,
            'meta_query'  => [
                'relation' => 'AND',
                [ 'key' => PROLOCKER_PREFIX . 'pro_key_client_ip_address', 'value' => $this->client_ip ],
                [ 'key' => PROLOCKER_PREFIX . 'pro_key_post_id', 'value' => $post->ID ]
            ]
        ];

        $posts   = get_posts( $args );
        $prokey = ( empty( $posts ) ) ? null : $posts[0];

        return ( empty( $prokey ) ) ? null : $this->set_key_data( $prokey );
    }

    /**
     * Handles a key identifier.
     *
     * @since 1.0.0
     * @param string $identifier.
     * @return string|object Identifier handler result. The Prokey data.
     */
    private function handle_identifier( $identifier ) {
        $args = [
            'numberposts' => 1,
            'post_status' => 'publish',
            'post_type'   => Prokey::$post_type, 
            'name'        => $identifier
        ];

        $posts   = get_posts( $args );
        $prokey = ( empty( $posts ) ) ? null : $posts[0];
        
        if ( $prokey ) {
            $client_ip_address = get_post_meta( $prokey->ID, PROLOCKER_PREFIX . 'pro_key_client_ip_address', true );
        
            if ( $this->client_ip === $client_ip_address ) {
                return $this->set_key_data( $prokey );
            }

            if ( 0 === $this->ip_id ) {
                $this->ip_id = wp_insert_post( [
                    'post_title'  => $this->client_ip,
                    'post_status' => 'publish',
                    'post_type'   => Proip::$post_type
                ] );
            }

            $ip_ids    = (array) get_post_meta( $prokey->ID, PROLOCKER_PREFIX . 'pro_key_ip_ids', true );
            $hit_count = (int) get_post_meta( $prokey->ID, PROLOCKER_PREFIX . 'pro_key_hit_count', true );

            if ( ! in_array( $this->ip_id, $ip_ids ) ) {
                array_push( $ip_ids, $this->ip_id );
                $hit_count++;

                update_post_meta( $prokey->ID, PROLOCKER_PREFIX . 'pro_key_ip_ids', $ip_ids );
                update_post_meta( $prokey->ID, PROLOCKER_PREFIX . 'pro_key_hit_count', $hit_count );
            }
        }
        
        return 'IDENTIFIER_HANDLED';
    }

    /**
     * Sets a key data.
     *
     * @since 1.0.0
     * @param WP_Post $prokey
     * @return Prolocker\Key $key. Key data.
     */
    private function set_key_data( $prokey ) {
        $key = new Key( $prokey->ID );
        return $key;
    }
}
