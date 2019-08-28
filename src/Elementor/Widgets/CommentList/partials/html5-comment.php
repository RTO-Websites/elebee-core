<article id="div-comment-<?php echo $id; ?>" class="comment-body">
    <header class="comment-meta">
        <div class="comment-author <?php echo $headerItemsClass; ?> vcard">
            <?php echo $avatar; ?>
            <?php printf( $authorStructure, get_comment_author( $comment ) ); ?>
        </div><!-- .comment-author -->

        <div class="comment-metadata <?php echo $headerItemsClass; ?>">
            <time datetime="<?php comment_time( 'c' ); ?>">
                <?php printf( $dateStructure, $date, $time ); ?>
            </time>
        </div><!-- .comment-metadata -->

        <?php if ( !is_admin() ) {
            edit_comment_link( __( 'Edit' ), '<span class="edit-link">', '</span>' );
        }
        ?>
        <?php if ( '0' == $comment->comment_approved ) : ?>
            <p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ); ?></p>
        <?php endif; ?>
    </header><!-- .comment-meta -->

    <table class="elebee-ratings">
        <?php if ( is_array( $ratingInfos ) ) : ?>
            <?php
            foreach ( $ratingInfos as $ratingInfo ) {
                ?>
                <tr class="elebee-rating">
                    <td class="elebee-rating-name">
                        <?php echo !empty( $ratingInfo['category'] ) ? $ratingInfo['category']->name : ''; ?>
                    </td>

                    <td class="elebee-rating-stars">
                        <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                            <?php
                            $selectedColor = !empty( $ratingInfo['category']->colorSelected ) ? $ratingInfo['category']->colorSelected : 'orange';
                            $defaultColor = !empty( $ratingInfo['category']->color ) ? $ratingInfo['category']->color : 'black';
                            ?>
                            <?php $color = ( $ratingInfo['rating'] >= $i ? $selectedColor : $defaultColor ); ?>

                            <div class="elebee-rating-star">
                                <i class="<?php echo $ratingInfo['category']->icon; ?>"
                                        style="color: <?php echo $color; ?>"></i>
                            </div>
                        <?php
                        endfor;
                        ?>
                    </td>
                </tr>
            <?php } ?>
        <?php endif; ?>
    </table>

    <section>
        <div class="comment-content">
            <?php comment_text(); ?>
        </div><!-- .comment-content -->
    </section>

    <footer>
        <?php
        if ( 'yes' === $settings['comment_list_allow_reply'] ) {
            comment_reply_link( array_merge( $args, [
                'add_below' => 'div-comment',
                'depth' => $depth,
                'max_depth' => $args['max_depth'],
                'before' => '<div class="reply">',
                'after' => '</div>',
            ] ) );
        }
        ?>
    </footer>
</article><!-- .comment-body -->