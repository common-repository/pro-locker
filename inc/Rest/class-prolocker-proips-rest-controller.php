<?php
/**
 * Prolocker_Proips_REST_Controller class
 * 
 * @package Prolocker\Rest
 * @since 1.0.0
 */

namespace Prolocker\Rest;

use WP_Error;
use WP_REST_Server;
use WP_REST_Response;
use WP_REST_Controller;
use Prolocker\Posts\Prolocker_Proip_Post as Proip;

/**
 * Class used to manage the prolocker/v1/proips set of REST endpoints.
 * 
 * @since 1.0.0
 */
class Prolocker_Proips_REST_Controller extends WP_REST_Controller {
    /**
     * Namespace.
     *
     * @since 1.0.0
     * @var string $namespace
     */
    protected $namespace = 'prolocker/v1';

    /**
     * Route.
     *
     * @since 1.0.0
     * @var string $route
     */
    protected $route = '/proips';

    /**
     * Registers the REST routes.
     *
     * @since 1.0.0
     * @return void
     */
    public function register_routes() {
        register_rest_route( $this->namespace, $this->route, [
            'methods'             => WP_REST_Server::CREATABLE,
            'callback'            => [ $this, 'prolocker_create_item' ],
            'permission_callback' => [ $this, 'prolocker_create_item_permissions_check' ]
        ] );
    }

    /**
     * Creates a new item for blacklisted IP addresses.
     *
     * @since 1.0.0
     * @param WP_REST_Request $request Request data.
     * @return WP_Error|WP_REST_Response
     */
    public function prolocker_create_item( $request ) {
        $body       = $request->get_json_params();
        $ip_address = $body['ip_address'];

        if ( false === filter_var( $ip_address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
            return new WP_Error( 'invalid_ip_address', esc_html__( 'Invalid IP Address.', 'prolocker' ), [ 'status' => 400 ] );
        }

        $ip_address_slug = str_replace( '.', '-', $ip_address );
        $posts           = get_posts( [
            'name'           => $ip_address_slug,
            'post_type'      => Proip::$post_type,
            'post_status'    => 'any',
            'posts_per_page' => 1
        ] );

        if ( $posts ) {
            $is_blacklisted = 'blacklisted' === get_post_status( $posts[0] );

            if ( $is_blacklisted ) {
                return new WP_Error( 'ip_address_already_exists', esc_html__( 'The IP Address is already blacklisted.', 'prolocker' ), [ 'status' => 409 ] );
            }

            $result = wp_update_post( [
                'ID'          => $posts[0]->ID,
                'post_status' => 'blacklisted'
            ] );

            if ( 0 === $result ) {
                return new WP_Error( 'try_again', esc_html__( 'An error occurred. Please refresh the page and try again.', 'prolocker' ), [ 'status' => 409 ] );
            }

            return new WP_REST_Response( [ 'status' => 'success' ], 201 );
        }

        $result = wp_insert_post( [ 
            'post_title'  => $ip_address,
            'post_status' => 'blacklisted',
            'post_type'   => Proip::$post_type
        ] );
        
        if ( 0 === $result ) {
            return new WP_Error( 'try_again', esc_html__( 'An error occurred. Please refresh the page and try again.', 'prolocker' ), [ 'status' => 409 ] );
        }

        return new WP_REST_Response( [ 'status' => 'success' ], 201 );
    }

    /**
     * Checks if a given request has access to create items.
     *
     * @since 1.0.0
     * @param WP_REST_Request $request Request data.
     * @return WP_Error|bool
     */
    public function prolocker_create_item_permissions_check( $request ) {
        return current_user_can( 'edit_posts' );
    }
}
