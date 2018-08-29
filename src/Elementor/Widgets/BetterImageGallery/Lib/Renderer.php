<?php
/**
 * Renderer.php
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Elementor\Widgets\BetterImageGallery\Lib
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Elementor/Widgets/General/BetterImageGallery/Lib/Renderer.html
 */

namespace ElebeeCore\Elementor\Widgets\BetterImageGallery\Lib;


use ElebeeCore\Lib\Util\Template;
use ElebeeCore\Lib\Util\Visitee;
use ElebeeCore\Lib\Util\Visitor;

defined( 'ABSPATH' ) || exit;

/**
 * Class Renderer
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Elementor\Widgets\BetterImageGallery\Lib
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Elementor/Widgets/BetterImageGallery/Lib/Renderer.html
 */
class Renderer implements Visitor {

    /**
     * @since 0.1.0
     * @var array
     */
    private $widgetSettings;

    /**
     * @since 0.1.0
     * @var string
     */
    private $modalId;

    /**
     * Renderer constructor.
     *
     * @since 0.1.0
     *
     * @param array $widgetSettings
     */
    public function __construct( array $widgetSettings ) {

        $this->widgetSettings = $widgetSettings;
        $this->modalId = filter_input( INPUT_GET, 'modalId' );

    }

    /**
     * @since 0.1.0
     *
     * @return bool
     */
    public function inModal(): bool {

        return $this->modalId !== null;

    }

    /**
     * @since 0.1.0
     *
     * @return string
     */
    public function getModalId(): string {

        return $this->modalId;

    }

    /**
     * @since 0.1.0
     *
     * @param Visitee $visitee
     */
    public function visit( Visitee $visitee ) {

        switch ( get_class( $visitee ) ) {

            case Album::class:
                $this->renderAlbum( $visitee );
                break;

            case Gallery::class:
                $this->renderGallery( $visitee );
                break;

            case Image::class:
                $this->renderImage( $visitee );
                break;

            default:
                break;

        }

    }

    /**
     * @since 0.1.0
     *
     * @param Album $album
     */
    private function renderAlbum( Album $album ) {

        if ( $this->widgetSettings['galleries_rand'] ) {
            $album->shuffle();
        }

        $galleryListTemplate = new Template( dirname( __DIR__ ) . '/partials/better-widget-image-gallery.php', [
            'modal' => $this->inModal() ? '-modal' : '',
            'album' => $album,
            'renderer' => $this,
        ] );
        $galleryListTemplate->render();

    }

    /**
     * @since 0.1.0
     *
     * @param Gallery $gallery
     */
    private function renderGallery( Gallery $gallery ) {

        if ( !empty( $this->widgetSettings['gallery_rand'] ) ) {
            $gallery->shuffle();
        }

        $backLink = $this->getRenderedBackLink();

        $headerTitle = $this->getRenderedHeaderTitle( $gallery );

        $sedcard = $this->getRenderedSedcard( $gallery );

        $galleryTemplate = new Template( dirname( __DIR__ ) . '/partials/gallery.php', [
            'backLink' => $backLink,
            'headerTitle' => $headerTitle,
            'sedcard' => $sedcard,
            'settings' => $this->widgetSettings,
            'gallery' => $gallery,
            'renderer' => $this,
        ] );
        $galleryTemplate->render();

    }

    /**
     * @since 0.1.0
     *
     * @return string
     */
    private function getRenderedBackLink(): string {

        if ( !$this->inModal() ) {
            return '';
        }

        $uriParts = explode( '?', $_SERVER['REQUEST_URI'], 2 );
        $pageId = filter_input( INPUT_GET, 'page_id' );

        $galleryBackLinkTemplate = new Template( dirname( __DIR__ ) . '/partials/gallery-back-link.php', [
            'buttonSize' => $this->widgetSettings['button_size'],
            'buttonText' => $this->widgetSettings['button_text'],
            'buttonHoverAnimation' => $this->widgetSettings['button_hover_animation'],
            'buttonIcon' => $this->widgetSettings['button_icon'],
            'buttonIconAlign' => $this->widgetSettings['button_icon_align'],
            'url' => $uriParts[0] . $pageId,
        ] );
        return $galleryBackLinkTemplate->getRendered();

    }

    /**
     * @since 0.1.0
     *
     * @param Gallery $gallery
     * @return string
     */
    private function getRenderedHeaderTitle( Gallery $gallery ): string {

        if ( $this->widgetSettings['show_title'] != 'yes' ) {
            return '';
        }

        return $this->getRenderedTitle( $gallery->getTitle() );

    }

    /**
     * @since 0.1.0
     *
     * @param Gallery $gallery
     * @return string
     */
    public function getRenderedSedcard( Gallery $gallery ): string {

        $attributeList = $gallery->getAttributeList();

        $showSedcard = $this->widgetSettings['show_sedcard'] == 'yes';
        $showSedcard = $showSedcard && !empty( $attributeList );
        $showSedcard = $showSedcard && ( $this->inModal() || $this->widgetSettings['gallery_style'] != 'modal' );

        if ( !$showSedcard ) {

            return '';

        }

        $gallerySedcardTemplate = new Template( dirname( __DIR__ ) . '/partials/gallery-sedcard.php', [
            'attributeList' => $attributeList,
        ] );
        return $gallerySedcardTemplate->getRendered();

    }

    /**
     * @since 0.1.0
     *
     * @param string $content
     * @return string
     */
    private function getRenderedTitle( string $content ): string {

        $galleryTitleTemplate = new Template( get_stylesheet_directory() . '/vendor//public/partials/general/element-default.php', [
            'tag' => $this->widgetSettings['header_size'],
            'attributes' => Template::toHtmlAttributes( [
                'class' => 'gallery-title',
            ] ),
            'content' => $content,
        ] );
        return $galleryTitleTemplate->getRendered();

    }

    /**
     * @since 0.1.0
     *
     * @param Image $image
     * @return void
     */
    private function renderImage( Image $image ) {

        if ( $this->inModal() || $this->widgetSettings['first_image'] ) {
            $title = $image->getCaption();
        } else {
            $title = $image->getGallery()->getTitle();
        }

        $galleryImageContainerTemplate = new Template( dirname( __DIR__ ) . '/partials/gallery-image-container.php', [
            'galleryId' => $image->getGallery()->getId(),
            'thumbnail' => $this->getRenderedThumbnail( $image ),
            'href' => ( $this->widgetSettings['gallery_style'] == 'modal' )
                ? get_the_permalink() . '?modalId=' . $image->getGallery()->getId()
                : $image->getSrc(),
            'title' => $this->getRenderedTitle( $title ),
            'settings' => $this->widgetSettings,
        ] );
        $galleryImageContainerTemplate->render();

    }

    /**
     * @since 0.1.0
     *
     * @param Image $image
     * @return string
     */
    private function getRenderedThumbnail( Image $image ): string {

        $galleryImageTemplate = new Template( dirname( __DIR__ ) . '/partials/gallery-image.php', [
            'src' => $image->getSrc(),
        ] );
        return $galleryImageTemplate->getRendered();

    }

}