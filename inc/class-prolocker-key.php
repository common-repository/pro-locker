<?php
/**
 * Prolocker_Key class.
 * 
 * @package Prolocker
 * @since 1.0.0
 */

namespace Prolocker;

/**
 * Class used to manage a key data.
 * 
 * @since 1.0.0
 */
class Prolocker_Key {
    /**
     * Key ID.
     *
     * @since 1.0.0
     * @var int $ID.
     */
    private $ID;

    /**
     * Key identifier.
     *
     * @since 1.0.0
     * @var string $identifier.
     */
    private $identifier;

    /**
     * Creates an instance of Key.
     *
     * @since 1.0.0
     * @param int $id Optional. Key ID. Null.
     */
    public function __construct( $id = null ) {
        if ( is_numeric( $id ) && 0 < $id ) {
            $this->set_id( $id );
            $this->set_identifier( get_the_title( $id ) );
        }
    }

    /**
     * Gets the key ID.
     *
     * @since 1.0.0
     * @return int $this->ID.
     */
    public function get_id() {
        return (int) $this->ID;
    }

    /**
     * Gets the key identifier.
     *
     * @since 1.0.0
     * @return string $this->identifier.
     */
    public function get_identifier() {
        return $this->identifier;
    }

    /**
     * Gets the key client's IP address.
     *
     * @since 1.0.0
     * @return string
     */
    public function get_client_ip_address() {
        return get_post_meta( $this->get_id(), PROLOCKER_PREFIX . 'pro_key_client_ip_address', true );
    }

    /**
     * Gets the key post id.
     *
     * @since 1.0.0
     * @return int
     */
    public function get_post_id() {
        return (int) get_post_meta( $this->get_id(), PROLOCKER_PREFIX . 'pro_key_post_id', true );
    }

    /**
     * Gets the key hit count.
     *
     * @since 1.0.0
     * @return int
     */
    public function get_hit_count() {
        return (int) get_post_meta( $this->get_id(), PROLOCKER_PREFIX . 'pro_key_hit_count', true );
    }

    /**
     * Gets the key IP IDs.
     *
     * @since 1.0.0
     * @return array List of IP IDs.
     */
    public function get_ip_IDs() {
        return (array) get_post_meta( $this->get_id(), PROLOCKER_PREFIX . 'pro_key_ip_ids', true );
    }

    /**
     * Gets key meta data by meta key.
     *
     * @since 1.0.0
     * @param string $meta_key.
     * @return mixed data.
     */
    public function get_data( $meta_key ) {
        return get_post_meta( $this->get_id(), $meta_key, true );
    }

    /**
     * Sets the key ID.
     *
     * @since 1.0.0
     * @param int $id.
     */
    public function set_id( $id ) {
        $this->ID = $id;
    }

    /**
     * Sets the key identifier.
     *
     * @since 1.0.0
     * @param string $identifier
     */
    public function set_identifier( $identifier ) {
        $this->identifier = $identifier;
    }

    /**
     * Sets the key meta data using meta key value pairs.
     *
     * @since 1.0.0
     * @param string $meta_key.
     * @param mixed $value.
     */
    public function set_data( $meta_key, $value ) {
        update_post_meta( $this->get_id(), $meta_key, $value );
    }

    /**
     * Resets the key hit count back to zero.
     *
     * @since 1.0.0
     * @return void
     */
    public function reset_hit_count() {
        update_post_meta( $this->get_id(), PROLOCKER_PREFIX . 'pro_key_hit_count', 0 );
        update_post_meta( $this->get_id(), PROLOCKER_PREFIX . 'pro_key_ip_ids', [] );
    }
}
