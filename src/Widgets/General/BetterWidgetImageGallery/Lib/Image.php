<?php
/**
 * @since 0.1.0
 * @author RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 */

namespace ElebeeCore\Widgets\General\BetterWidgetImageGallery\Lib;


use ElebeeCore\Lib\Visitee;

class Image extends Visitee {

    /**
     * @var Gallery|null
     */
    private $gallery;

    /**
     * @var string
     */
    private $src;

    /**
     * @var string
     */
    private $caption;

    /**
     * @var string
     */
    private $link;

    /**
     * Image constructor.
     * @param string $src
     * @param string $caption
     * @param string $link
     * @param array $meta
     */
    public function __construct( string $src, string $caption, string $link ) {

        $this->gallery = null;
        $this->src = $src;
        $this->caption = $caption;
        $this->link = $link;

    }

    /**
     * @return Gallery
     */
    public function getGallery(): Gallery {

        return $this->gallery;

    }

    /**
     * @param Gallery $gallery
     */
    public function setGallery( Gallery $gallery ) {

        $this->gallery = $gallery;

    }

    /**
     * @return string
     */
    public function getSrc(): string {

        return $this->src;

    }

    /**
     * @return string
     */
    public function getCaption(): string {

        return $this->caption;

    }

}