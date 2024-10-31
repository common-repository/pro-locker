<?php 
    $reset_hit_count_url = add_query_arg( [
        'post'     => get_the_ID(),
        'action'   => 'reset_hit_count',
        '_wpnonce' => wp_create_nonce( 'reset_hit_count' )
    ], admin_url( '/admin-post.php' ) );
?>
<a href="<?php echo esc_url( $reset_hit_count_url ); ?>" class="button button-primary"><?php esc_html_e( 'Reset Hit Count', 'prolocker' ); ?></a>
<a href="<?php echo esc_url( get_delete_post_link( get_the_ID(), '', true ) ); ?>" class="button button-default"><?php esc_html_e( 'Delete Key', 'prolocker' ); ?></a>
