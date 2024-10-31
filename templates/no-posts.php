<?php 
    use Prolocker\Posts\Prolocker_Proip_Post as Proip;
    use Prolocker\Posts\Prolocker_Prokey_Post as Prokey;

    global $post_type;

    switch ( $post_type ) {
        case Prokey::$post_type:
            $messages = [
                'dashicon_class' => 'dashicons-lock', 
                'title'          => esc_html__( 'Keys will show up here.', 'prolocker' ), 
                'description'    => esc_html__( 'Currently, keys have not been created.', 'prolocker' )
            ];
            break;
        case Proip::$post_type:
            $messages = [
                'dashicon_class' => 'dashicons-admin-site',
                'title'          => esc_html__( 'Blacklisted IPs will show up here.', 'prolocker' ), 
                'description'    => esc_html__( 'Currently, IP addresses have not been added to the blacklist.', 'prolocker' )
            ];
            break;
    }
?>
<div class="prolocker-no-posts">
    <?php $class = sprintf( 'dashicons %s prolocker-no-posts__icon', $messages['dashicon_class'] ); ?>
    <span class="<?php echo esc_attr( $class ); ?>"></span>
    <h2 class="prolocker-no-posts__message">
        <?php echo esc_html( $messages['title'] ); ?>
        <br>
        <?php echo esc_html( $messages['description'] ); ?>
    </h2>
</div>
<style>.subsubsub, .tablenav.top, .wp-list-table, .tablenav.bottom .actions { display: none; }</style>
