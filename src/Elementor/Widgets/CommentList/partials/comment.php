<<?php echo $tag; ?>
<?php comment_class( $has_children ? 'parent' : '', $comment ); ?> id="comment-<?php comment_ID(); ?>">
<?php if ( 'div' != $args[ 'style' ] ) : ?>
    <div id="div-comment-<?php comment_ID(); ?>" class="comment-body">
<?php endif; ?>
    <div class="comment-author vcard">
        <?php if ( 0 != $args[ 'avatar_size' ] ) echo get_avatar( $comment, $args[ 'avatar_size' ] ); ?>
        <?php
        /* translators: %s: comment author link */
        printf( __( '%s <span class="says">says:</span>' ),
            sprintf( '<cite class="fn">%s</cite>', get_comment_author_link( $comment ) )
        );
        ?>
    </div>

        <div class="comment-meta commentmetadata">
            <?php echo $date->format( $settings[ 'comment_list_date_format' ] ); ?>
            <?php edit_comment_link( __( '(Edit)' ), '&nbsp;&nbsp;', '' ); ?>
        </div>

        <table class="elebee-ratings">
            <?php if ( is_array( $ratingInfos ) ) : ?>
                <?php
                foreach ( $ratingInfos as $ratingInfo ) {
                    ?>
                    <tr class="elebee-rating">
                        <td class="elebee-rating-name">
                            <?php echo $ratingInfo[ 'category' ]->name; ?>
                        </td>

                        <td class="elebee-rating-stars">
                            <?php $selectedColor = ! empty( $ratingInfo[ 'category' ]->colorSelected ) ? $ratingInfo[ 'category' ]->colorSelected : 'orange'; ?>
                            <?php $defaultColor = ! empty( $ratingInfo[ 'category' ]->color ) ? $ratingInfo[ 'category' ]->color : 'black'; ?>

                            <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                                <?php $color = ( $ratingInfo[ 'rating' ] >= $i ? $selectedColor : $defaultColor ); ?>

                                <div class="elebee-rating-star">
                                    <i class="<?php echo $ratingInfo[ 'category' ]->icon; ?>"
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

        <?php if ( '0' == $comment->comment_approved ) : ?>
    <em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ) ?></em>
    <br/>
<?php endif; ?>
        
        <?php comment_text( $comment, array_merge( $args, [ 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args[ 'max_depth' ] ] ) ); ?>

<?php
if ( 'yes' === $settings[ 'comment_list_allow_reply' ] ) {
    comment_reply_link( array_merge( $args, [
        'add_below' => $add_below,
        'depth' => $depth,
        'max_depth' => $args[ 'max_depth' ],
        'before' => '<div class="reply">',
        'after' => '</div>',
    ] ) );
}
?>

<?php if ( 'div' != $args[ 'style' ] ) : ?>
    </div>
<?php endif; ?>