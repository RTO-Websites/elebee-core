<?php
namespace ElebeeCore\Widgets\CommentList\Lib;

use \WP_Comment;
use \Walker_Comment;
use \DateTime;
use \DateTimeZone;

class Walker extends Walker_Comment {

    private $settings;

    public function __construct($args = []) {
        $defaults = [];

        $this->settings = wp_parse_args($args, $defaults);
    }

    /**
     * Outputs a single comment.
     *
     * @since 3.6.0
     *
     * @see wp_list_comments()
     *
     * @param WP_Comment $comment Comment to display.
     * @param int        $depth   Depth of the current comment.
     * @param array      $args    An array of arguments.
     */
    protected function comment( $comment, $depth, $args ) {
        if ( 'div' == $args['style'] ) {
            $tag = 'div';
            $add_below = 'comment';
        } else {
            $tag = 'li';
            $add_below = 'div-comment';
        }
        ?>
        <<?php echo $tag; ?> <?php comment_class( $this->has_children ? 'parent' : '', $comment ); ?> id="comment-<?php comment_ID(); ?>">
        <?php if ( 'div' != $args['style'] ) : ?>
            <div id="div-comment-<?php comment_ID(); ?>" class="comment-body">
        <?php endif; ?>
        <div class="comment-author vcard">
            <?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
            <?php
            /* translators: %s: comment author link */
            printf( __( '%s <span class="says">says:</span>' ),
                sprintf( '<cite class="fn">%s</cite>', get_comment_author_link( $comment ) )
            );
            ?>
        </div>
        <?php if ( '0' == $comment->comment_approved ) : ?>
            <em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ) ?></em>
            <br />
        <?php endif; ?>

        <div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
                <?php
                /* translators: 1: comment date, 2: comment time */
                printf( __( '%1$s at %2$s', 'elebee' ), get_comment_date( '', $comment ),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)' ), '&nbsp;&nbsp;', '' );
            ?>
        </div>

        <?php comment_text( $comment, array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>

        <?php
        if ('yes' === $this->settings['comment_list_allow_reply']) {
            comment_reply_link( array_merge( $args, array(
                'add_below' => $add_below,
                'depth' => $depth,
                'max_depth' => $args['max_depth'],
                'before' => '<div class="reply">',
                'after' => '</div>'
            ) ) );
        }
        ?>

        <?php if ( 'div' != $args['style'] ) : ?>
            </div>
        <?php endif; ?>
        <?php
    }

    /**
     * Outputs a comment in the HTML5 format.
     *
     * @since 3.6.0
     *
     * @see wp_list_comments()
     *
     * @param WP_Comment $comment Comment to display.
     * @param int        $depth   Depth of the current comment.
     * @param array      $args    An array of arguments.
     */
    protected function html5_comment( $comment, $depth, $args ) {
        $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
        ?>
        <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $this->has_children ? 'parent' : '', $comment ); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <footer class="comment-meta">
                <div class="comment-author vcard">
                    <?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
                    <?php
                    printf( $this->settings['comment_list_author_format'], get_comment_author( $comment ) );
                    ?>
                </div><!-- .comment-author -->

                <div class="comment-metadata">
                    <time datetime="<?php comment_time( 'c' ); ?>">
                        <?php
                        $date = DateTime::createFromFormat('Y-m-d H:i:s', $comment->comment_date);
                        echo $date->format($this->settings['comment_list_date_format']);
                        ?>
                    </time>
                    <?php edit_comment_link( __( 'Edit' ), '<span class="edit-link">', '</span>' ); ?>
                </div><!-- .comment-metadata -->

                <?php if ( '0' == $comment->comment_approved ) : ?>
                    <p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ); ?></p>
                <?php endif; ?>
            </footer><!-- .comment-meta -->

            <div class="comment-content">
                <?php comment_text(); ?>
            </div><!-- .comment-content -->

            <?php
            if ('yes' === $this->settings['comment_list_allow_reply']) {
                comment_reply_link( array_merge( $args, array(
                    'add_below' => 'div-comment',
                    'depth'     => $depth,
                    'max_depth' => $args['max_depth'],
                    'before'    => '<div class="reply">',
                    'after'     => '</div>'
                ) ) );
            }
            ?>
        </article><!-- .comment-body -->
        <?php
    }
}