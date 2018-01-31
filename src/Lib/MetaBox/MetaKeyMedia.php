<?php
/**
 * MetaKeyMedia.php
 */

namespace ElebeeCore\Lib\MetaBox;


use ElebeeCore\Lib\Template;

/**
 * Class MetaKeyMedia
 *
 * @package ElebeeCore
 * @author RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @since 0.2.0
 */
class MetaKeyMedia extends MetaKey {

    /**
     * MetaKeyCheckbox constructor.
     *
     * @param $key
     * @param $label
     * @param int $type
     */
    public function __construct( $key, $label, $type = self::TYPE_DEFAULT ) {

        $defaultTemplate = new Template( __DIR__ . '/partials/meta-key-media-default.php' );
        $this->setTemplate( $defaultTemplate );

        parent::__construct( $key, $label, $type );

    }

    /**
     * Echo the post media.
     *
     * @param $key
     * @param null $postId
     * @param string $size
     * @param string $attr
     *
     * @return void
     */
    public static function thePostMedia( $key, $postId = null, $size = 'thumbnail', $attr = '' ) {

        echo self::getThePostMedia( $key, $postId, $size, $attr );

    }

    /**
     * Get the post media.
     *
     * @param $key
     * @param null $postId
     * @param string $size
     * @param string $attr
     *
     * @return string
     */
    public static function getThePostMedia( $key, $postId = null, $size = 'thumbnail', $attr = '' ) {

        return wp_get_attachment_image( self::getPostMediaId( $key, $postId ), $size, false, $attr );

    }

    /**
     * Get the post media id.
     *
     * @param $key
     * @param null $postId
     *
     * @return mixed
     */
    public static function getPostMediaId( $key, $postId = null ) {

        if ( null === $postId ) {
            $postId = get_the_ID();
        }

        return get_post_meta( $postId, $key, true );

    }

}