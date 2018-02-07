<?php
/**
 * Album.php
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Widgets\General\AspectRatioImage\Lib
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Widgets/General/BetterWidgetImageGallery/Lib/Album.html
 */

namespace ElebeeCore\Widgets\General\BetterWidgetImageGallery\Lib;


use ElebeeCore\Lib\Visitee;

defined( 'ABSPATH' ) || exit;

/**
 * Class Album
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Widgets\General\AspectRatioImage\Lib
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Widgets/General/BetterWidgetImageGallery/Lib/Album.html
 */
class Album extends Visitee implements \Iterator {

    /**
     * @since 0.1.0
     * @var string
     */
    private $id;

    /**
     * @since 0.1.0
     * @var array
     */
    private $galleryList;

    /**
     * @since 0.1.0
     * @var int
     */
    private $iteratorPointer;

    /**
     * Album constructor.
     *
     * @since 0.1.0
     *
     * @param string $id
     */
    public function __construct( string $id ) {

        $this->id = $id;
        $this->galleryList = [];
        $this->rewind();

    }

    /**
     * @since 0.1.0
     *
     * @param Gallery $gallery
     * @return void
     */
    public function addGallery( Gallery $gallery ) {

        $gallery->setId( $this->id . $this->count() );
        $this->galleryList[] = $gallery;

    }

    /**
     * Shuffles the gallery order.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function shuffle() {

        shuffle( $this->galleryList );

    }

    /**
     * @since 0.1.0
     *
     * @return int
     */
    public function count(): int {

        return count( $this->galleryList );

    }

    /**
     * @since 0.1.0
     *
     * @return Gallery
     */
    public function current(): Gallery {

        return $this->galleryList[$this->iteratorPointer];

    }

    /**
     * @since 0.1.0
     */
    public function next() {

        ++$this->iteratorPointer;

    }

    /**
     * @since 0.1.0
     */
    public function key(): int {

        return $this->iteratorPointer;

    }

    /**
     * @since 0.1.0
     */
    public function valid(): bool {

        return isset( $this->galleryList[$this->iteratorPointer] );

    }

    /**
     * @since 0.1.0
     */
    public function rewind() {

        $this->iteratorPointer = 0;

    }

}