<?php
/**
 * Prolocker_Prokey_Post class
 * 
 * @package Prolocker\Posts;
 * @since 1.0.0
 */

namespace Prolocker\Posts;

use Prolocker\Prolocker_Key as Key;
use Prolocker\Pages\Prolocker_Menu_Page;

/**
 * Class used to manage the prokey custom post type.
 * 
 * @since 1.0.0
 */
class Prolocker_Prokey_Post {
    /**
     * Post type name.
     *
     * @since 1.0.0
     * @var string $post_type.
     */
    public static $post_type = 'prokey';

    /**
     * Creates an instance of Prolocker_Prokey_Post.
     * 
     * @since 1.0.0
     */
    public function __construct() {
        add_action( 'init', [ $this, 'prolocker_init' ] );
        add_filter( 'post_row_actions', [ $this, 'prolocker_post_row_actions' ], 10, 2 );
        add_filter( 'bulk_actions-edit-prokey', [ $this, 'prolocker_bulk_actions_edit_prokey' ] );
        add_filter( 'manage_prokey_posts_columns', [ $this, 'prolocker_manage_prokey_posts_columns' ] );
        add_action( 'manage_prokey_posts_custom_column', [ $this, 'prolocker_manage_prokey_posts_custom_column' ], 10, 2 );
        add_filter( 'post_updated_messages', [ $this, 'prolocker_post_updated_messages' ] );
        add_filter( 'bulk_post_updated_messages', [ $this, 'prolocker_bulk_post_updated_messages' ], 10, 2 );
        add_action( 'transition_post_status', [ $this, 'prolocker_transition_post_status' ], 10, 3 );
        add_filter( 'views_edit-prokey', [ $this, 'prolocker_views_edit_prokey' ] );
        add_action( 'admin_menu', [ $this, 'prolocker_remove_prokey_fields' ] );
        add_action( 'admin_post_reset_hit_count', [ $this, 'prolocker_reset_hit_count' ] );
    }

    /**
     * Registers the prokey custom post type.
     *
     * @since 1.0.0
     * @return void
     */
    public static function prolocker_register_custom_post_type() {
        $labels = [
            'name'                  => esc_html_x( 'Prokeys', 'Post Type General Name', 'prolocker' ),
            'singular_name'         => esc_html_x( 'Prokey', 'Post Type Singular Post Name', 'prolocker' ),
            'edit_item'             => esc_html__( 'Edit Prokey', 'prolocker' ),
            'view_item'             => esc_html__( 'View Prokey', 'prolocker' ),
            'view_items'            => esc_html__( 'View Prokeys', 'prolocker' ),
            'search_items'          => esc_html__( 'Search Prokeys', 'prolocker' ),
            'not_found'             => esc_html__( 'No Prokeys found', 'prolocker' ),
            'filter_items_list'     => esc_html__( 'Filter Prokeys list', 'prolocker' ),
            'items_list_navigation' => esc_html__( 'Prokeys list navigation', 'prolocker' ),
            'items_list'            => esc_html__( 'Prokeys list', 'prolocker' )
        ];

        $args = [
            'labels'              => $labels,
            'description'         => esc_html__( 'Keys are created automatically when users view posts or pages with blocked content. They must be used to unlock the content in question.', 'prolocker' ),
            'public'              => true,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'show_in_nav_menus'   => false,
            'show_in_menu'        => Prolocker_Menu_Page::$menu_slug,
            'show_in_admin_bar'   => false,
            'capabilities'        => [
                'create_posts' => false
            ],
            'map_meta_cap'        => true,
            'supports'            => false,
            'can_export'          => false
        ];

        register_post_type( self::$post_type, $args );
    }

    /**
     * Unregisters the prokey custom post type.
     *
     * @since 1.0.0
     * @return void
     */
    public static function prolocker_unregister_custom_post_type() {
        unregister_post_type( self::$post_type );
    }

    /**
     * Calls functions to be run on the init action hook.
     *
     * @since 1.0.0
     * @return void
     */
    public function prolocker_init() {
        self::prolocker_register_custom_post_type();
    }

    /**
     * Modifies the post row actions for the prokey custom post type.
     * 
     * Runs on the post_row_actions filter hook.
     *
     * @since 1.0.0
     * @param array $actions
     * @param WP_Post $post
     * @return array The modified post row actions.
     */
    public function prolocker_post_row_actions( $actions, $post ) {
        if ( $post->post_type === self::$post_type ) {
            unset( $actions['inline hide-if-no-js'] );
            unset( $actions['trash'] );
            unset( $actions['view'] );

            $url      = admin_url( 'post.php' );
            $edit_url = add_query_arg( [ 
                'post'   => $post->ID,
                'action' => 'edit'
            ], $url );

            $aria_label      = sprintf( '%1$s "%2$s"', esc_html__( 'View', 'prolocker' ), $post->post_title );
            $actions['edit'] = sprintf( '<a href="%1$s" aria-label="%2$s">%3$s</a>', esc_url( $edit_url ), esc_attr( $aria_label ), esc_html__( 'View', 'prolocker' ) );

            $delete_url        = get_delete_post_link( $post->ID, '', true );
            $aria_label        = sprintf( '%1$s "%2$s"', esc_html__( 'Delete', 'prolocker' ), $post->post_title );
            $actions['delete'] = sprintf( '<a href="%1$s" aria-label="%2$s">%3$s</a>', esc_url( $delete_url ), esc_attr( $aria_label ), esc_html__( 'Delete Permanently', 'prolocker' ) );
        }

        return $actions;
    }

    /**
     * Modifies the bulk actions dropdown on the edit admin screen 
     * for the prokey custom post type.
     * 
     * Runs on the bulk_actions-edit-prokey filter hook.
     *
     * @since 1.0.0
     * @param array $actions
     * @return array List of actions.
     */
    public function prolocker_bulk_actions_edit_prokey( $actions ) {
        unset( $actions['edit'] );
        unset( $actions['trash'] );
        $actions['delete'] = esc_html__( 'Delete Permanently', 'prolocker' );

        return $actions;
    }

    /**
     * Adds custom columns to the prokey custom post type listing on the admin screen.
     * 
     * Runs on the manage_prokey_posts_columns filter hook.
     *
     * @since 1.0.0
     * @param array $columns
     * @return array List of columns.
     */
    public function prolocker_manage_prokey_posts_columns( $columns ) {
        unset( $columns['title'] );
        unset( $columns['date'] );
        
        return array_merge( $columns, [
            'title'                 => esc_html__( 'Key', 'prolocker' ),
            'created-by-ip-address' => esc_html__( 'Created by IP address', 'prolocker' ),
            'post-used-on'          => esc_html__( 'Post being used on', 'prolocker' ),
            'date'                  => esc_html__( 'Date', 'prolocker' )
        ] );
    }

    /**
     * Displays the contents of the custom columns added to the prokey custom post type 
     * on the admin screen.
     * 
     * Runs on the manage_prokey_posts_custom_column action hook.
     *
     * @param string $column The name of the column.
     * @param int $post_id The ID of the current post.
     * @return void
     */
    public function prolocker_manage_prokey_posts_custom_column( $column, $post_id ) {
        $key = new Key( $post_id );
        
        switch ( $column ) {
            case 'created-by-ip-address':
                echo esc_html( $key->get_client_ip_address() );
                break;
            case 'post-used-on':
                $id           = $key->get_post_id();
                $post_used_on = get_post( $id );
                
                if ( $post_used_on ) {
                    printf( '<a href="%1$s" title="%2$s" target="_blank">%2$s</a>', get_the_permalink( $post_used_on ), get_the_title( $post_used_on ) );
                } else {
                    esc_html_e( 'The post has not been found. It was probably deleted.', 'prolocker' );
                }

                break;
        }
    }

    /**
     * Customizes the prokey custom post type updated messages.
     * 
     * Runs on the post_updated_messages filter hook.
     *
     * @since 1.0.0
     * @since 1.1.1 Added sanitation for $_GET values.
     * @param array $messages Messages to customize.
     * @return array $messages The customized messages.
     */
    public function prolocker_post_updated_messages( $messages ) {
        $post = get_post();

        $messages['pro-key'] = [
            0 => '',
            1 => esc_html__( 'Prokey updated.', 'prolocker' ),
            2 => esc_html__( 'Custom field updated.', 'prolocker' ),
            3 => esc_html__( 'Custom field deleted.', 'prolocker' ),
            4 => esc_html__( 'Prokey updated.', 'prolocker' ),
            /* translators: Revision title. */
            5 => isset( $_GET['revision'] ) ? sprintf( esc_html__( 'Prokey restored to revision from %s', 'prolocker' ), wp_post_revision_title( absint( $_GET['revision'] ), false ) ) : false,
            6 => esc_html__( 'Prokey published.', 'prolocker' ),
            7 => esc_html__( 'Prokey saved.', 'prolocker' ),
            8 => esc_html__( 'Prokey submitted.', 'prolocker' ),
            9 => sprintf(
                    /* translators: Post date. */ 
                    __( 'Prokey scheduled for: <strong>%1$s</strong>.', 'prolocker' ),
                    /* translators: Publish box date format, see http://php.net/date  */
                    date_i18n( esc_html__( 'M j, Y @ G:i', 'prolocker' ), strtotime( $post->post_date ) ) ),
            10 => esc_html__( 'Prokey draft updated.', 'prolocker' )
        ];

        return $messages;
    }

    /**
     * Customizes the prokey custom post type bulk updated messages.
     * 
     * Runs on the bulk_post_updated_messages filter hook.
     *
     * @since 1.0.0
     * @param array $bulk_messages Messages to customize.
     * @param array $bulk_counts Item counts for each message.
     * @return array $bulk_messages The customized messages.
     */
    public function prolocker_bulk_post_updated_messages( $bulk_messages, $bulk_counts ) {
        $bulk_messages['prokey'] = [
            /* translators: Updated count. */
            'updated'   => _n( '%s Prokey updated.', '%s Prokeys updated.', $bulk_counts['updated'], 'prolocker' ),
            /* translators: Locked count. */
            'locked'    => _n( '%s Prokey not updated, somebody is editing it.', '%s Prokeys not updated, somebody is editing them.', $bulk_counts['locked'], 'prolocker' ),
            /* translators: Deleted count. */
            'deleted'   => _n( '%s Prokey permanently deleted.', '%s Prokeys permanently deleted.', $bulk_counts['deleted'], 'prolocker' ),
            /* translators: Trashed count. */
            'trashed'   => _n( '%s Prokey moved to the Trash.', '%s Prokeys moved to the Trash.', $bulk_counts['trashed'], 'prolocker' ),
            /* translators: Untrashed count. */
            'untrashed' => _n( '%s Prokey restored from the Trash.', '%s Prokeys restored from the Trash.', $bulk_counts['untrashed'], 'prolocker' )
        ];

        return $bulk_messages;
    }

    /**
     * Avoids changing the prokey custom post type status to other than publish.
     * 
     * Runs on the transition_post_status action hook.
     *
     * @since 1.0.0
     * @param string $new_status. The new status.
     * @param string $old_status. The old status.
     * @param WP_Post $post. The post object.
     * @return void
     */
    public function prolocker_transition_post_status( $new_status, $old_status, $post ) {
        if ( $post->post_type === self::$post_type && 'publish' !== $new_status ) {
            wp_update_post( [ 'ID' => $post->ID, 'post_status' => 'publish' ] );
            wp_die( esc_html__( 'This action is not allowed.', 'prolocker' ) );
        }
    }

    /**
     * Displays the prokey custom post type description on the edit screen.
     * 
     * Runs on the views_edit-prokey filter hook.
     *
     * @since 1.0.0
     * @param array $views
     * @return array $views
     */
    public function prolocker_views_edit_prokey( $views ) {
        $pro_key_obj = get_post_type_object( self::$post_type );
        printf( '<h4>%s</h4>', esc_html( $pro_key_obj->description ) );
        
        return $views;
    }

    /**
     * Removes the submitdiv metabox from the prokey custom post type.
     *
     * Runs on the admin_menu action hook.
     * 
     * @since 1.0.0
     * @return void
     */
    public function prolocker_remove_prokey_fields() {
        remove_meta_box( 'submitdiv', self::$post_type, 'side' );
    }

    /**
     * Resets the prokey hit count.
     * 
     * Runs on the admin_post_reset_hit_count custom action hook.
     *
     * @since 1.0.0
     * @since 1.1.1 Added sanitation and validation for $_REQUEST values.
     * @return void
     */
    public function prolocker_reset_hit_count() {
        $post_id = absint( $_REQUEST['post'] );
        $action  = sanitize_text_field( $_REQUEST['action'] );

        if ( ! current_user_can( 'edit_posts' ) || 'reset_hit_count' !== $action ) {
            wp_die( esc_html__( 'This action is not allowed.', 'prolocker' ), esc_html__( 'ProLocker - Forbidden', 'prolocker' ), [
                'response'  => 403,
                'link_url'  => esc_url( admin_url( '/' ) ),
                'link_text' => esc_html__( '&laquo; Back to Dashboard', 'prolocker' )
            ]  );
        }

        $check_admin_referer = check_admin_referer( $action );
        $post_type           = get_post_type( get_post( $post_id ) );

        if ( false === $check_admin_referer || 2 === $check_admin_referer || $post_type !== self::$post_type ) {
            wp_die( esc_html__( 'An error occurred processing the request. Please try again.', 'prolocker' ), esc_html__( 'ProLocker - Bad Request', 'prolocker' ), [
                'response'  => 400,
                'back_link' => true
            ]  );
        }

        $key = new Key( $post_id );
        $key->reset_hit_count();

        wp_redirect( add_query_arg( [ 'message' => '1' ], get_edit_post_link( $post_id, '' ) ) );
        exit;
    }
}
