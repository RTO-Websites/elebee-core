<?php
/**
 * @since 0.1.0
 * @author RTO GmbH <info@rto.de>
 * @licence MIT
 */

namespace ElebeeCore\Widgets\BetterWidgetImageGallery\Lib;


use ElebeeCore\Lib\Visitee;

class Album extends Visitee implements \Iterator {

    /**
     * @var string
     */
    private $id;

    /**
     * @var array
     */
    private $galleryList;

    /**
     * @var int
     */
    private $iteratorPointer;

    /**
     * Album constructor.
     * @param string $id
     */
    public function __construct( string $id ) {

        $this->id = $id;
        $this->galleryList = [];
        $this->rewind();

    }

    /**
     * @param Gallery $gallery
     */
    public function addGallery( Gallery $gallery ) {

        $gallery->setId( $this->id . $this->count() );
        $this->galleryList[] = $gallery;

    }

    /**
     * Shuffles the gallery order.
     */
    public function shuffle() {

        shuffle( $this->galleryList );

    }

    /**
     * @return int
     */
    public function count(): int {

        return count( $this->galleryList );

    }

    /**
     * @return Gallery
     */
    public function current(): Gallery {

        return $this->galleryList[$this->iteratorPointer];

    }

    /**
     *
     */
    public function next() {

        ++$this->iteratorPointer;

    }

    /**
     * @return int
     */
    public function key(): int {

        return $this->iteratorPointer;

    }

    /**
     * @return bool
     */
    public function valid(): bool {

        return isset( $this->galleryList[$this->iteratorPointer] );

    }

    /**
     *
     */
    public function rewind() {

        $this->iteratorPointer = 0;

    }

}