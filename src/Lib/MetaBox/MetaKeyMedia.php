<?php
/**
 * MetaKeyMedia.php
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\MetaBox
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/MetaBox/MetaKeyMedia.html
 */

namespace ElebeeCore\Lib\MetaBox;


use ElebeeCore\Lib\Util\Template;

\defined( 'ABSPATH' ) || exit;

/**
 * Class MetaKeyMedia
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\MetaBox
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/MetaBox/MetaKeyMedia.html
 */
class MetaKeyMedia extends MetaKeyBase {

    /**
     * MetaKeyCheckbox constructor.
     *
     * @param string $key
     * @param string $label
     * @param int    $type
     */
    public function __construct( string $key, string $label, int $type = self::TYPE_DEFAULT ) {

        $defaultTemplate = new Template( __DIR__ . '/partials/meta-key-media-default.php' );
        $this->setTemplate( $defaultTemplate );

        parent::__construct( $key, $label, $type );

    }

    /**
     * Echo the post media.
     *
     * @since   0.2.0
     *
     * @param string   $key
     * @param null|int $postId
     * @param string   $size
     * @param string   $attr
     *
     * @return void
     */
    public static function thePostMedia( string $key, int $postId = null, string $size = 'thumbnail', string $attr = '' ) {

        echo self::getThePostMedia( $key, $postId, $size, $attr );

    }

    /**
     * Get the post media.
     *
     * @since   0.2.0
     *
     * @param string   $key
     * @param null|int $postId
     * @param string   $size
     * @param string   $attr
     *
     * @return string
     */
    public static function getThePostMedia( string $key, int $postId = null, string $size = 'thumbnail', string $attr = '' ): string {

        return wp_get_attachment_image( self::getPostMediaId( $key, $postId ), $size, false, $attr );

    }

    /**
     * Get the post media id.
     *
     * @since   0.2.0
     *
     * @param string   $key
     * @param null|int $postId
     *
     * @return mixed
     */
    public static function getPostMediaId( string $key, int $postId = null ) {

        if ( null === $postId ) {
            $postId = get_the_ID();
        }

        return get_post_meta( $postId, $key, true );

    }

}