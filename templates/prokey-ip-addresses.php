<?php 
    use Prolocker\Prolocker_Key as Key;
    use Prolocker\Posts\Prolocker_Proip_Post as Proip;

    $key    = new Key( get_the_ID() );
    $ip_ids = $key->get_ip_IDs();

    if ( $ip_ids ) {
        $ip_addresses = get_posts( [
            'post__in'    => $ip_ids,
            'post_type'   => Proip::$post_type,
            'post_status' => 'any'
        ] );
    }
?>
<div>
    <?php if ( $ip_addresses ): ?>
        <h4>
            <?php esc_html_e( 'The following is a list of IP addresses that have contributed to the hit count for this key.', 'prolocker' ); ?>
        </h4>
        <div id="ip-addresses-root" data-ip-addresses='<?php echo json_encode( $ip_addresses ); ?>'></div>
    <?php else: ?>
        <h4 class="m-0">
            <?php esc_html_e( 'The IP addresses that contribute to the hit count will appear here.', 'prolocker' ); ?>
        </h4>
    <?php endif; ?>
</div>
