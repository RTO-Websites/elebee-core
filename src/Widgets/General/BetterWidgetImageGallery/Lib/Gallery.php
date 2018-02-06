<?php
/**
 * Gallery.php
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Widgets\General\AspectRatioImage\Lib
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Widgets/General/BetterWidgetImageGallery/Lib/Gallery.html
 */

namespace ElebeeCore\Widgets\General\BetterWidgetImageGallery\Lib;


use ElebeeCore\Lib\Visitee;

defined( 'ABSPATH' ) || exit;

/**
 * Class Gallery
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Widgets\General\AspectRatioImage\Lib
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Widgets/General/BetterWidgetImageGallery/Lib/Gallery.html
 */
class Gallery extends Visitee implements \Iterator {

    /**
     * @since 0.1.0
     * @var string
     */
    private $id;

    /**
     * @since 0.1.0
     * @var string
     */
    private $title;

    /**
     * @since 0.1.0
     * @var Image
     */
    private $thumb;

    /**
     * @since 0.1.0
     * @var array
     */
    private $meta;

    /**
     * @since 0.1.0
     * @var array
     */
    private $imgList;

    /**
     * @since 0.1.0
     * @var int
     */
    private $iteratorPointer;

    /**
     * Gallery constructor.
     *
     * @since 0.1.0
     *
     * @param string $title
     * @param Image  $thumb
     * @param array  $meta
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
     * @since 0.1.0
     *
     * @param Image $img
     *
     * @return void
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
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function shuffle() {

        shuffle( $this->imgList );

    }

    /**
     * @since 0.1.0
     *
     * @return string
     */
    public function getId(): string {

        return $this->id;

    }

    /**
     * @since 0.1.0
     *
     * @param string $id
     */
    public function setId( string $id ) {

        $this->id = $id;

    }

    /**
     * @since 0.1.0
     *
     * @return string
     */
    public function getTitle(): string {

        if ( $this->title === '' ) {
            return '&ensp;';
        }

        return $this->title;

    }

    /**
     * @since 0.1.0
     *
     * @return Image
     */
    public function getThumb(): Image {

        return $this->thumb;

    }

    /**
     * @since 0.1.0
     *
     * @return array
     */
    public function getAttributeList(): array {

        return $this->meta;

    }

    /**
     * @since 0.1.0
     *
     * @return int
     */
    public function count(): int {

        return count( $this->imgList );

    }

    /**
     * @since 0.1.0
     *
     * @return Image
     */
    public function current(): Image {

        return $this->imgList[$this->iteratorPointer];

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

        return isset( $this->imgList[$this->iteratorPointer] );

    }

    /**
     * @since 0.1.0
     */
    public function rewind() {

        $this->iteratorPointer = 0;

    }

}