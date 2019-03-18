<?php
/**
 * Image.php
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Elementor\Widgets\BetterImageGallery\Lib
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Elementor/Widgets/BetterImageGallery/Lib/Image.html
 */

namespace ElebeeCore\Elementor\Widgets\BetterImageGallery\Lib;


use ElebeeCore\Lib\Util\Visitee;

\defined( 'ABSPATH' ) || exit;

/**
 * Class Image
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Elementor\Widgets\BetterImageGallery\Lib
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Elementor/Widgets/BetterImageGallery/Lib/Image.html
 */
class Image extends Visitee {

    /**
     * @since 0.1.0
     * @var Gallery
     */
    private $gallery;

    /**
     * @since 0.1.0
     * @var string
     */
    private $src;

    /**
     * @since 0.1.0
     * @var string
     */
    private $caption;

    /**
     * @var string
     */
    private $link;

    /**
     * Image constructor.
     *
     * @since 0.1.0
     *
     * @param string $src
     * @param string $caption
     * @param string $link
     * @param array  $meta
     */
    public function __construct( string $src, string $caption, string $link ) {

        $this->gallery = null;
        $this->src = $src;
        $this->caption = $caption;
        $this->link = $link;

    }

    /**
     * @since 0.1.0
     *
     * @return Gallery
     */
    public function getGallery(): Gallery {

        return $this->gallery;

    }

    /**
     * @since 0.1.0
     *
     * @param Gallery $gallery
     */
    public function setGallery( Gallery $gallery ) {

        $this->gallery = $gallery;

    }

    /**
     * @since 0.1.0
     *
     * @return string
     */
    public function getSrc(): string {

        return $this->src;

    }

    /**
     * @since 0.1.0
     *
     * @return string
     */
    public function getCaption(): string {

        return $this->caption;

    }

}