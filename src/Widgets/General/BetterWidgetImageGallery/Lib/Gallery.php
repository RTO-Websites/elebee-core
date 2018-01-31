<?php
/**
 * @since 0.1.0
 * @author RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 */

namespace ElebeeCore\Widgets\BetterWidgetImageGallery\Lib;


use ElebeeCore\Lib\Visitee;

class Gallery extends Visitee implements \Iterator {

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var Image
     */
    private $thumb;

    /**
     * @var array
     */
    private $meta;

    /**
     * @var array
     */
    private $imgList;

    /**
     * @var int
     */
    private $iteratorPointer;

    /**
     * Gallery constructor.
     * @param string $title
     * @param Image|null $thumb
     */
    public function __construct( string $title, Image $thumb = null, array $meta = [] ) {

        $this->id = null;
        $this->title = $title;
        $this->thumb = $thumb;
        $this->meta = $meta;
        $this->imgList = [];
        $this->rewind();

        if ( $thumb ) {

            $thumb->setGallery( $this );

        }

    }

    /**
     * @param Image $img
     */
    public function addImage( Image $img ) {

        if ( $this->thumb === null && $this->count() === 0 ) {
            $this->thumb = $img;
        }

        $img->setGallery( $this );
        $this->imgList[] = $img;

    }

    /**
     * Shuffles the image order.
     */
    public function shuffle() {

        shuffle( $this->imgList );

    }

    public function getId() {

        return $this->id;

    }

    /**
     * @param string $id
     */
    public function setId( string $id ) {

        $this->id = $id;

    }

    /**
     * @return string
     */
    public function getTitle(): string {

        if ( $this->title === '' ) {
            return '&ensp;';
        }

        return $this->title;

    }

    /**
     * @return Image
     */
    public function getThumb(): Image {

        return $this->thumb;

    }

    /**
     * @return array
     */
    public function getAttributeList(): array {

        return $this->meta;

    }

    /**
     * @return int
     */
    public function count(): int {

        return count( $this->imgList );

    }

    /**
     * @return Image
     */
    public function current() {

        return $this->imgList[$this->iteratorPointer];

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

        return isset( $this->imgList[$this->iteratorPointer] );

    }

    /**
     *
     */
    public function rewind() {

        $this->iteratorPointer = 0;

    }

}