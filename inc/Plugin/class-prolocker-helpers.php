<?php
/**
 * Prolocker_Helpers class
 * 
 * @package Prolocker\Plugin
 * @since 1.0.0
 */

namespace Prolocker\Plugin;

/**
 * Class used to declare optional custom helpers.
 * 
 * @since 1.0.0
 */
class Prolocker_Helpers {
    /**
     * Gets the client's IP address.
     * 
     * @since 1.0.0
     * @return string|null IP address. Null when the IP address could not be detected.
     */
    public static function get_client_ip_address() {
        $headers = [ 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR' ];

        foreach ( $headers as $key => $header ) {
            if ( array_key_exists( $header, $_SERVER ) ) {
                $client_ip = explode( ',', $_SERVER[ $header ] );
                $client_ip = trim( $client_ip[0] );
            }

            if ( filter_var( $client_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
                return $client_ip;
            }
        }

        return null;
    }
}
