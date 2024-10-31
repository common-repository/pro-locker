<?php
/**
 * Uninstall script
 * 
 * Runs when the plugin is deleted.
 * 
 * @package ProLocker
 * @since 1.0.0
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

global $wpdb;

/**
 * Delete posts and their data. 
 * 
 * Unfortunately there is no other way to delete several posts at once efficiently but directly accessing the database. 
 * The wp_delete_post function only deletes one post at a time, which could result in poor performance.
 * 
 * @link https://wordpress.stackexchange.com/questions/208608/delete-all-posts-of-a-custom-post-type-efficiently
 */
$wpdb->query( "DELETE FROM {$wpdb->posts} WHERE post_type IN ( 'prokey', 'proip' );" );
$wpdb->query( "DELETE meta FROM {$wpdb->postmeta} meta LEFT JOIN {$wpdb->posts} posts ON posts.ID = meta.post_id WHERE posts.ID IS NULL;" );

// Removes all cache items.
wp_cache_flush();
