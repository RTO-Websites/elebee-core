<?php
/**
 * Walker.php
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Elementor\Widgets\CommentList/Lib
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Elementor/Widgets/General/CommentList/Lib/Walker.html
 */

namespace ElebeeCore\Elementor\Widgets\CommentList\Lib;


use DateTime;
use Walker_Comment;
use WP_Comment;

\defined( 'ABSPATH' ) || exit;

/**
 * Class Walker
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Elementor\Widgets\CommentList\Lib
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Elementor/Widgets/CommentList/Lib/Walker.html
 */
class Walker extends Walker_Comment {

    /**
     * @since 0.1.0
     * @var array
     */
    private $settings;

    /**
     * Walker constructor.
     *
     * @since 0.1.0
     *
     * @param array $args
     */
    public function __construct( $args = [] ) {

        $defaults = [];

        $this->settings = wp_parse_args( $args, $defaults );

    }

    /**
     * Outputs a single comment.
     *
     * @since 0.1.0
     *
     * @see   wp_list_comments()
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
        <<?php echo $tag; ?><?php comment_class( $this->has_children ? 'parent' : '', $comment ); ?> id="comment-<?php comment_ID(); ?>">
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
            <br/>
        <?php endif; ?>

        <div class="comment-meta commentmetadata">
            <a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
                <?php
                /* translators: 1: comment date, 2: comment time */
                printf( __( '%1$s at %2$s', 'elebee' ), get_comment_date( '', $comment ), get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)' ), '&nbsp;&nbsp;', '' );
            ?>
        </div>

        <?php comment_text( $comment, array_merge( $args, [ 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ] ) ); ?>

        <?php
        if ( 'yes' === $this->settings['comment_list_allow_reply'] ) {
            comment_reply_link( array_merge( $args, [
                'add_below' => $add_below,
                'depth' => $depth,
                'max_depth' => $args['max_depth'],
                'before' => '<div class="reply">',
                'after' => '</div>',
            ] ) );
        }
        ?>

        <?php if ( 'div' != $args['style'] ) : ?>
            </div>
        <?php endif; ?>
        <?php

    }

    /**
     * Outputs a comment in the HTML5 format.
     * https://developer.wordpress.org/reference/classes/walker_comment/html5_comment/
     *
     * @since 0.1.0
     *
     * @see   wp_list_comments()
     *
     * @param WP_Comment $comment Comment to display.
     * @param int        $depth   Depth of the current comment.
     * @param array      $args    An array of arguments.
     */
    protected function html5_comment( $comment, $depth, $args ) {
        $preDefinedDates = [
            'j-f-y' => 'j. F Y',
            'y-m-d' => 'Y-m-d',
            'm-d-y' => 'm/d/Y',
            'd-m-y' => 'd/m/Y',
            'mdy' => 'm.d.Y',
        ];

        $dType = $comment->comment_parent > 0 ? 'reply_' : '';
        $dateFormatKey = $this->settings[ 'comment_' . $dType . 'date_format' ];
        if( $dateFormatKey === 'custom' ) {
            $dateFormat = $this->settings[ 'comment_' . $dType . 'date_format_custom' ];
        }
        else {
            $dateFormat = $preDefinedDates[ $dateFormatKey ];
        }

        $timeFormat = $this->settings[ 'comment_' . $dType . 'time_format_custom' ];

        $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
        $id = $comment->comment_ID;
        $class = comment_class( $comment->comment_parent === 0 ? 'parent' : '', $id, $comment->post_ID, false );
        $avatar = 0 !== $args[ 'avatar_size' ]  ? get_avatar( $comment, $args[ 'avatar_size' ] ) : '';
        $type = $comment->comment_parent > 0 ? 'reply' : 'list';
        $authorStructure = $this->settings[ 'comment_' . $type . '_author_structure' ];
        $date = date( $dateFormat, strtotime( $comment->comment_date ) );
        $time = date( $timeFormat, strtotime($comment->comment_date ) );
        $dateStructure = $this->settings[ 'comment_' . $type . '_date_structure' ];

        $headerItemsClass = $this->settings[ 'comment_header_break' ] !== 'yes' ? 'elebee-display-inline' : '';
        ?>
        <<?php echo $tag; ?> id="comment-<?php echo $id; ?>" <?php echo $class; ?>>
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

            <section>
                <div class="comment-content">
                    <?php comment_text(); ?>
                </div><!-- .comment-content -->
            </section>

            <footer>
                <?php
                if ( 'yes' === $this->settings[ 'comment_list_allow_reply' ] ) {
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
        <?php

    }

}