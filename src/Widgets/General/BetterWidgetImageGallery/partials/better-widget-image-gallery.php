<?php

/**
 * @var array $settings
 * @var string $inModal
 * @var \ElebeeCore\Widgets\BetterWidgetImageGallery\Album $galleryList
 * @var \ElebeeCore\Widgets\BetterWidgetImageGallery\BetterWidgetImageGalleryRenderVisitor $renderer
 */

?>

<div class="elementor-image-gallery">

    <div class="gallery rto-image-columns<?php echo $modal; ?> clearfix">

        <?php foreach ( $album as $gallery ) : ?>

            <?php if ( $renderer->inModal() && $gallery->getId() === $renderer->getModalId() || !$renderer->inModal() ) : ?>
                <?php $gallery->accept( $renderer ); ?>
            <?php endif; ?>

        <?php endforeach; ?>

    </div>

</div>
