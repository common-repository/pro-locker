<?php
/**
 * Prolocker_Proip_Post class
 * 
 * @package Prolocker\Posts;
 * @since 1.0.0
 */

namespace Prolocker\Posts;

use Prolocker\Pages\Prolocker_Menu_Page;

/**
 * Class used to manage the proip custom post type.
 * 
 * @since 1.0.0
 */
class Prolocker_Proip_Post {
    /**
     * Post type name.
     *
     * @since 1.0.0
     * @var string $post_type.
     */
    public static $post_type = 'proip';

    /**
     * Creates an instance of Prolocker_Proip_Post.
     * 
     * @since 1.0.0
     */
    public function __construct() {
        add_action( 'init', [ $this, 'prolocker_init' ] );
        add_filter( 'post_row_actions', [ $this, 'prolocker_post_row_actions' ], 10, 2 );
        add_filter( 'bulk_actions-edit-proip', [ $this, 'prolocker_bulk_actions_edit_proip' ] );
        add_filter( 'handle_bulk_actions-edit-proip', [ $this, 'prolocker_handle_bulk_actions_edit_proip' ], 10, 3 );
        add_filter( 'manage_proip_posts_columns', [ $this, 'prolocker_manage_proip_posts_columns' ] );
        add_action( 'manage_proip_posts_custom_column', [ $this, 'prolocker_manage_proip_posts_custom_column' ], 10, 2 );
        add_filter( 'post_updated_messages', [ $this, 'prolocker_post_updated_messages' ] );
        add_filter( 'bulk_post_updated_messages', [ $this, 'prolocker_bulk_post_updated_messages' ], 10, 2 );
        add_action( 'transition_post_status', [ $this, 'prolocker_transition_post_status' ], 10, 3 );
        add_filter( 'views_edit-proip', [ $this, 'prolocker_views_edit_proip' ] );
        add_action( 'admin_notices', [ $this, 'prolocker_admin_notices' ] );
        add_action( 'pre_get_posts', [ $this, 'prolocker_pre_get_posts' ] );
        add_action( 'admin_init', [ $this, 'prolocker_admin_init' ] );
    }

    /**
     * Registers the proip custom post type.
     *
     * @since 1.0.0
     * @return void
     */
    public static function prolocker_register_custom_post_type() {
        $labels = [
            'name'                  => esc_html_x( 'Blacklisted IPs', 'Post Type General Name', 'prolocker' ),
            'singular_name'         => esc_html_x( 'Blacklisted IP', 'Post Type Singular Post Name', 'prolocker' ),
            'edit_item'             => esc_html__( 'Edit IP', 'prolocker' ),
            'view_item'             => esc_html__( 'View IP', 'prolocker' ),
            'view_items'            => esc_html__( 'View IPs', 'prolocker' ),
            'search_items'          => esc_html__( 'Search IPs', 'prolocker' ),
            'not_found'             => esc_html__( 'No IPs found', 'prolocker' ),
            'filter_items_list'     => esc_html__( 'Filter IPs list', 'prolocker' ),
            'items_list_navigation' => esc_html__( 'IPs list navigation', 'prolocker' ),
            'items_list'            => esc_html__( 'IPs list', 'prolocker' )
        ];

        $args = [
            'labels'              => $labels,
            'description'         => esc_html__( 'Blacklisted IP addresses are not taken into account during the content unlocking process and cannot create new keys either.', 'prolocker' ),
            'public'              => true,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'show_in_nav_menus'   => false,
            'show_in_menu'        => Prolocker_Menu_Page::$menu_slug,
            'show_in_admin_bar'   => false,
            'capabilities'        => [
                'create_posts'         => false,
                'edit_published_posts' => false,
                'edit_private_posts'   => false
            ],
            'map_meta_cap'        => true,
            'supports'            => false,
            'can_export'          => false
        ];

        register_post_type( self::$post_type, $args );
    }

    /**
     * Unregisters the proip custom post type.
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
        $this->prolocker_avoid_edit();
    }

    /**
     * Modifies the post row actions for the proip custom post type.
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
            unset( $actions['edit'] );
            unset( $actions['inline hide-if-no-js'] );
            unset( $actions['trash'] );
            unset( $actions['view'] );
            
            $url      = admin_url( 'post.php' );
            $remove_url = add_query_arg( [ 
                'post'     => $post->ID,
                'action'   => 'remove_ip',
                '_wpnonce' => wp_create_nonce( 'remove_blacklisted_ip' )
            ], $url );

            $aria_label        = sprintf( '%1$s "%2$s"', esc_html__( 'Remove', 'prolocker' ), $post->post_title );
            $actions['delete'] = sprintf( '<a href="%1$s" aria-label="%2$s">%3$s</a>', esc_url( $remove_url ), esc_attr( $aria_label ), esc_html__( 'Remove', 'prolocker' ) );
        }

        return $actions;
    }

    /**
     * Modifies the bulk actions dropdown on the edit admin screen 
     * for the proip custom post type.
     * 
     * Runs on the bulk_actions-edit-proip filter hook.
     *
     * @since 1.0.0
     * @param array $actions
     * @return array List of actions.
     */
    public function prolocker_bulk_actions_edit_proip( $actions ) {
        unset( $actions['edit'] );
        unset( $actions['trash'] );
        unset( $actions['delete'] );
        $actions['remove'] = esc_html__( 'Remove', 'prolocker' );

        return $actions;
    }

    /**
     * Handles the bulk actions functionality for the proip custom post type.
     * 
     * Runs on the handle_bulk_actions-edit-proip filter hook.
     *
     * @param string $redirect_url The redirect URL.
     * @param string $doaction The action being taken.
     * @param array $items The items to take the action on.
     * @return string The redirect URL.
     */
    public function prolocker_handle_bulk_actions_edit_proip( $redirect_url, $doaction, $items ) {
        if ( 'remove' !== $doaction ) {
            return $redirect_url;
        }

        foreach ( $items as $key => $post_id ) {
            wp_update_post( [ 'ID' => $post_id, 'post_status' => 'publish' ] );
        }

        $redirect_url = remove_query_arg( 'saved', $redirect_url );
        $redirect_url = add_query_arg( 'bulk_removed', count( $items ), $redirect_url );
        return $redirect_url;
    }

    /**
     * Adds custom columns to the proip custom post type listing on the admin screen.
     * 
     * Runs on the manage_proip_posts_columns filter hook.
     *
     * @since 1.0.0
     * @param array $columns
     * @return array List of columns.
     */
    public function prolocker_manage_proip_posts_columns( $columns ) {
        unset( $columns['title'] );
        unset( $columns['date'] );

        return array_merge( $columns, [
            'new_title' => esc_html__( 'IP address', 'prolocker' ),
            'date'      => esc_html__( 'Date', 'prolocker' )
        ] );
    }

    /**
     * Displays the contents of the custom columns added to the proip custom post type 
     * on the admin screen.
     * 
     * Runs on the manage_proip_posts_custom_column action hook.
     *
     * @param string $column The name of the column.
     * @param int $post_id The ID of the current post.
     * @return void
     */
    public function prolocker_manage_proip_posts_custom_column( $column, $post_id ) {
        switch ( $column ) {
            case 'new_title':
                printf( '<strong><span>%s</span></strong>', esc_html( get_the_title() ) );
                break;
        }
    }

    /**
     * Customizes the proip custom post type updated messages.
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

        $messages['proip'] = [
            0 => '',
            1 => esc_html__( 'IP updated.', 'prolocker' ),
            2 => esc_html__( 'Custom field updated.', 'prolocker' ),
            3 => esc_html__( 'Custom field deleted.', 'prolocker' ),
            4 => esc_html__( 'IP updated.', 'prolocker' ),
            /* translators: Revision title. */
            5 => isset( $_GET['revision'] ) ? sprintf( esc_html__( 'IP restored to revision from %s', 'prolocker' ), wp_post_revision_title( absint( $_GET['revision'] ), false ) ) : false,
            6 => esc_html__( 'IP published.', 'prolocker' ),
            7 => esc_html__( 'IP saved.', 'prolocker' ),
            8 => esc_html__( 'IP submitted.', 'prolocker' ),
            9 => sprintf( 
                    /* translators: Pos date. */
                    __( 'IP scheduled for: <strong>%1$s</strong>.', 'prolocker' ),
                    /* translators: Publish box date format, see http://php.net/date  */
                    date_i18n( esc_html__( 'M j, Y @ G:i', 'prolocker' ), strtotime( $post->post_date ) ) ),
            10 => esc_html__( 'IP draft updated.', 'prolocker' )
        ];

        return $messages;
    }

    /**
     * Customizes the proip custom post type bulk updated messages.
     * 
     * Runs on the bulk_post_updated_messages filter hook.
     *
     * @since 1.0.0
     * @param array $bulk_messages Messages to customize.
     * @param array $bulk_counts Item counts for each message.
     * @return array $bulk_messages The customized messages.
     */
    public function prolocker_bulk_post_updated_messages( $bulk_messages, $bulk_counts ) {
        $bulk_messages['proip'] = [
            /* translators: Updated count. */
            'updated'   => _n( '%s IP address updated.', '%s IP addresses updated.', $bulk_counts['updated'], 'prolocker' ),
            /* translators: Locked count. */
            'locked'    => _n( '%s IP address not updated, somebody is editing it.', '%s IP addresses not updated, somebody is editing them.', $bulk_counts['locked'], 'prolocker' ),
            /* translators: Deleted count. */
            'deleted'   => _n( '%s IP address removed from the blacklist.', '%s IP addresses removed from the blacklist.', $bulk_counts['deleted'], 'prolocker' ),
            /* translators: Trashed count. */
            'trashed'   => _n( '%s IP address moved to the Trash.', '%s IP addresses moved to the Trash.', $bulk_counts['trashed'], 'prolocker' ),
            /* translators: Untrashed count. */
            'untrashed' => _n( '%s IP address restored from the Trash.', '%s IP addresses restored from the Trash.', $bulk_counts['untrashed'], 'prolocker' )
        ];

        return $bulk_messages;
    }

    /**
     * Avoids changing the proip custom post type status to other than publish.
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
        if ( $post->post_type === self::$post_type && ! in_array( $new_status, [ 'publish', 'blacklisted' ] ) ) {
            wp_update_post( [ 'ID' => $post->ID, 'post_status' => $old_status ] );
            wp_die( esc_html__( 'This action is not allowed.', 'prolocker' ) );
        }
    }

    /**
     * Displays the proip custom post type description on the edit screen
     * and removes the post count of statuses different from blacklisted.
     * 
     * Runs on the views_edit-proip filter hook.
     *
     * @since 1.0.0
     * @param array $views
     * @return array $views
     */
    public function prolocker_views_edit_proip( $views ) {
        $pro_ip_obj = get_post_type_object( self::$post_type );
        printf( '<h4>%s</h4>', esc_html( $pro_ip_obj->description ) );

        foreach ( $views as $key => $view ) {
            if ( 'blacklisted' !== $key ) {
                unset( $views[ $key ] );
            }
        }
        
        return $views;
    }

    /**
     * Displays admin notices for the proip custom post type.
     *
     * @since 1.0.0
     * @since 1.1.1 Added sanitation for $_GET values.
     * @global string $pagenow Current admin page. Current admin page.
     * @global string $post_type Post type.
     * @return void
     */
    public function prolocker_admin_notices() {
        global $pagenow;
        global $post_type;

        if ( 'edit.php' === $pagenow && self::$post_type === $post_type && isset( $_GET['saved'] ) && 1 === absint( $_GET['saved'] ) ) {
            $class   = 'notice notice-success is-dismissible';
            $message = esc_html__( 'IP address added to the blacklist.', 'prolocker' );

            printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
        }

        if ( 'edit.php' === $pagenow && self::$post_type === $post_type && isset( $_GET['bulk_removed'] ) ) {
            $class   = 'notice notice-success is-dismissible';
            $number  = absint( $_GET['bulk_removed'] );
            $message = _n( $number . ' IP address removed from the blacklist.', $number . ' IP addresses removed from the blacklist.', $number, 'prolocker' );
                                    
            printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
        }
    }

    /**
     * Alters the main query for the pro-ip custom post type 
     * to display only blacklisted IP addresses in the admin screen.
     *
     * @since 1.0.0
     * @param WP_Query $query The query object.
     * @global string $pagenow Current admin page.
     * @global string $post_type Post type.
     * @return void
     */
    public function prolocker_pre_get_posts( $query ) {
        global $pagenow;
        global $post_type;
        
        if ( 'edit.php' === $pagenow && $post_type == self::$post_type && is_admin() && $query->is_main_query() ) {
            $query->set( 'post_status', 'blacklisted' );
        }
    }

    /**
     * Runs on the admin_init action hook.
     *
     * Removes an IP address from the blacklist.
     * 
     * @since 1.0.0
     * @since 1.1.1 Added sanitation for $_GET values.
     * @return void
     */
    public function prolocker_admin_init() {
        if (
            isset( $_GET['post'] )  &&
            isset( $_GET['action'] ) && 
            isset( $_GET['_wpnonce'] ) && 
            'remove_ip' === sanitize_text_field( $_GET['action'] ) && 
            wp_verify_nonce( sanitize_text_field( $_GET['_wpnonce'] ), 'remove_blacklisted_ip' ) 
        ) {
            $post_id   = absint( $_GET['post'] );
            $post_type = get_post_type( $post_id );

            if ( $post_type !== self::$post_type ) {
                wp_die( esc_html__( 'This action is not allowed.', 'prolocker' ) );
            }

            wp_update_post( [ 'ID' => $post_id, 'post_status' => 'publish', ] );

            $redirect_url = add_query_arg( [
                'post_type' => self::$post_type,
                'deleted'   => 1
            ], admin_url( '/edit.php' ) );
            
            wp_redirect( $redirect_url );
            exit;
        }
    }

    /**
     * Avoids the editing of the pro-ip custom post type.
     * 
     * Runs on the init action hook.
     *
     * @since 1.0.0
     * @since 1.1.1 Added sanitation for $_GET values.
     * @global string $pagenow Current admin page.
     * @return void
     */
    private function prolocker_avoid_edit() {
        global $pagenow;
        
        if (
            is_admin() &&
            'post.php' === $pagenow &&
            isset( $_GET['post'] ) &&
            isset( $_GET['action'] ) &&
            'edit' === sanitize_text_field( $_GET['action'] )
        ) {
            if ( self::$post_type === get_post_type( absint( $_GET['post'] ) ) ) {
                wp_die( esc_html__( 'Sorry, you are not allowed to edit this item.', 'prolocker' ) );
            }
        }
    }
}
