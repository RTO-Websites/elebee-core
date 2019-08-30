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
use ElementorPro\Classes\Utils;
use WP_Comment;
use Walker_Comment;
use ElebeeCore\Database\Database;
use ElebeeCore\Lib\Util\Template;

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
     * @param array $args
     * @since 0.1.0
     *
     */
    public function __construct( $args = [] ) {

        $defaults = [];

        $this->settings = wp_parse_args( $args, $defaults );

    }

    public function substrwords( $text, $maxchar, $end = '...' ) {
        if ( strlen( $text ) > $maxchar || $text == '' ) {

            $words = preg_split( '/\s/', $text );
            $output = '';
            $i = 0;

            while ( 1 ) {
                $length = strlen( $output ) + strlen( $words[$i] );

                if ( $length > $maxchar ) {
                    break;
                } else {
                    $output .= " " . $words[$i];
                    ++$i;
                }
            }

            $output .= $end;

        } else {

            $output = $text;

        }

        if ( $output == $end ) {
            $output = substr( $text, 0, $maxchar ) . $end;
        }

        return $output;
    }

    /**
     * Outputs a single comment.
     *
     * @param WP_Comment $comment Comment to display.
     * @param int $depth Depth of the current comment.
     * @param array $args An array of arguments.
     * @see   wp_list_comments()
     *
     * @since 0.1.0
     *
     */
    protected function comment( $comment, $depth, $args ) {
        $commentMeta = get_comment_meta( $comment->comment_ID, 'elebeeRatings', true );
        $ratings = isset( $commentMeta['ratings'] ) ? $commentMeta['ratings'] : [];
        $date = DateTime::createFromFormat( 'Y-m-d H:i:s', $comment->comment_date );

        $database = new Database();
        $page = $this->settings['comments_from_post'];
        if ( $page === 'dynamic' ) {
            $page = get_the_ID();
        }

        $categories = $database->categories->getByTargetPostID( $page );

        if ( empty( $categories ) && class_exists( 'ElementorPro\Classes\Utils' ) ) {
            $categories = $database->categories->getByTargetPostID( Utils::get_current_post_id() );
        }

        $ratingInfos = [];

        if ( strlen( $comment->comment_content ) > 100 ) {
            $comment->comment_content = $this->substrwords( $comment->comment_content, 100 ) . '<br><br><a href="#no-js" class="showmore" data-commentid="' . $comment->comment_ID . '">' . __( 'Show more..', 'elebee' ) . '</a>';
        }

        foreach ( $ratings as $key => $rating ) {
            $found = null;

            foreach ( $categories as $category ) {
                if ( $category->categoryID == $key ) {
                    $found = $category;
                }
            }

            $ratingInfo = [
                'category' => $found,
                'rating' => $rating,
            ];

            array_push( $ratingInfos, $ratingInfo );
        }

        $vars = [
            'has_children' => $this->has_children,
            'comment' => $comment,
            'args' => $args,
            'depth' => $depth,
            'settings' => $this->settings,
            'ratingInfos' => $ratingInfos,
            'date' => $date,
        ];

        $templateLocation = __DIR__ . '/../partials/comment.php';
        $template = new Template( $templateLocation, $vars );
        $template->render();
    }

    /**
     * Outputs a comment in the HTML5 format.
     * https://developer.wordpress.org/reference/classes/walker_comment/html5_comment/
     *
     * @param WP_Comment $comment Comment to display.
     * @param int $depth Depth of the current comment.
     * @param array $args An array of arguments.
     * @see   wp_list_comments()
     *
     * @since 0.1.0
     *
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
        $dateFormatKey = $this->settings['comment_' . $dType . 'date_format'];
        if ( $dateFormatKey === 'custom' ) {
            $dateFormat = $this->settings['comment_' . $dType . 'date_format_custom'];
        } else {
            $dateFormat = $preDefinedDates[$dateFormatKey];
        }

        $timeFormat = $this->settings['comment_' . $dType . 'time_format_custom'];

        $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
        $id = $comment->comment_ID;
        $class = comment_class( $comment->comment_parent === 0 ? 'parent' : '', $id, $comment->post_ID, false );
        $avatar = 0 !== $args['avatar_size'] ? get_avatar( $comment, $args['avatar_size'] ) : '';
        $type = $comment->comment_parent > 0 ? 'reply' : 'list';
        $authorStructure = $this->settings['comment_' . $type . '_author_structure'];
        $date = date( $dateFormat, strtotime( $comment->comment_date ) );
        $time = date( $timeFormat, strtotime( $comment->comment_date ) );
        $dateStructure = $this->settings['comment_' . $type . '_date_structure'];

        $headerItemsClass = $this->settings['comment_header_break'] !== 'yes' ? 'elebee-display-inline' : '';

        $commentMeta = get_comment_meta( $comment->comment_ID, 'elebeeRatings', true );
        $ratings = isset( $commentMeta['ratings'] ) ? $commentMeta['ratings'] : [];

        $database = new Database();
        $page = $this->settings['comments_from_post'];
        if ( $page === 'dynamic' ) {
            $page = get_the_ID();
        }

        $categories = $database->categories->getByTargetPostID( $page );

        if ( empty( $categories ) && class_exists( 'ElementorPro\Classes\Utils' ) ) {
            $categories = $database->categories->getByTargetPostID( Utils::get_current_post_id() );
        }

        $ratingInfos = [];

        if ( strlen( $comment->comment_content ) > 100 ) {
            $comment->comment_content = $this->substrwords( $comment->comment_content, 100 ) . '<br><br><a href="#no-js" class="showmore" data-commentid="' . $comment->comment_ID . '">' . __( 'Show more..', 'elebee' ) . '</a>';
        }

        foreach ( $ratings as $key => $rating ) {
            $found = null;

            foreach ( $categories as $category ) {
                if ( $category->categoryID == $key ) {
                    $found = $category;
                }
            }

            $ratingInfo = [
                'category' => $found,
                'rating' => $rating,
            ];

            array_push( $ratingInfos, $ratingInfo );
        }

        $vars = [
            'id' => $id,
            'headerItemsClass' => $headerItemsClass,
            'avatar' => $avatar,
            'authorStructure' => $authorStructure,
            'dateStructure' => $dateStructure,
            'comment' => $comment,
            'date' => $date,
            'time' => $time,
            'settings' => $this->settings,
            'ratingInfos' => $ratingInfos,
        ];

        $templateLocation = __DIR__ . '/../partials/html5-comment.php';
        $template = new Template( $templateLocation, $vars );
        $template->render();

        ?>
        <<?php echo $tag; ?> id="comment-<?php echo $id; ?>" <?php echo $class; ?>>

        <?php

    }

}