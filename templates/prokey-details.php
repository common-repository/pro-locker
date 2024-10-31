<?php 
    use Prolocker\Prolocker_Key as Key;

    $pro_key       = new Key( get_the_ID() );
    $ip_address   = $pro_key->get_client_ip_address();
    $hit_count    = $pro_key->get_hit_count();
    $post_id      = $pro_key->get_post_id();
    $post_used_on = get_post( $post_id ); 
?>
<article>
    <h1 class="prokey-details-title"><?php echo esc_html( get_the_title() ); ?></h1>
    <section class="prokey-details-info">
        <span>
            <strong><?php esc_html_e( 'Created by IP address:', 'prolocker' ); ?></strong>
        </span>
        <span>
            <?php echo esc_html( $ip_address ); ?>
        </span>
        <span>
            <strong><?php esc_html_e( 'Hit count:', 'prolocker' ); ?></strong>
        </span>
        <span>
            <?php echo esc_html( $hit_count ); ?>
        </span>
    </section>
    <section>
        <h2 class="prokey-details-post-title"><?php esc_html_e( 'Post used on', 'prolocker' ); ?></h2>
        <?php if ( $post_used_on ): ?>
            <?php $class = ( has_post_thumbnail( $post_used_on ) ) ? 'prokey-details-post-info' : ''; ?>
            <div class="<?php echo esc_attr( $class ); ?>">
                <?php if ( has_post_thumbnail( $post_used_on ) ): ?>
                    <figure class="m-0">
                        <?php echo get_the_post_thumbnail( $post_used_on, [ 100, 100 ], [] ); ?>
                    </figure>    
                <?php endif; ?>
                <div>
                    <p class="mt-0">
                        <?php 
                            /* translators: Post ID. */
                            printf( esc_html__( 'ID: %s', 'prolocker' ), $post_used_on->ID ); 
                        ?>
                    </p>
                    <p class="mt-0">
                        <?php echo esc_html( get_the_title( $post_used_on ) ); ?>
                    </p>
                    <a href="<?php echo esc_url( get_the_permalink( $post_used_on ) ); ?>" class="button button-primary" target="_blank">
                        <?php esc_html_e( 'View post', 'prolocker' ); ?>
                    </a>
                    <?php if ( current_user_can( 'edit_posts' ) ): ?>
                        <a href="<?php echo esc_url( get_edit_post_link( $post_used_on ) ); ?>" class="button button-secondary">
                            <?php esc_html_e( 'Edit post', 'prolocker' ); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <p>
                <?php esc_html_e( 'The post has not been found. It was probably deleted. It is recommended that you delete this key.', 'prolocker' ); ?>
            </p>
        <?php endif; ?>
    </section>
</article>
