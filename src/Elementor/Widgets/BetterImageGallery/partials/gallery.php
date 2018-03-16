<?php

/**
 * @var string $backLink
 * @var string $headerTitle
 * @var string $sedcard
 * @var array $settings
 * @var \ElebeeCore\Widgets\BetterWidgetImageGallery\Gallery $gallery
 * @var \ElebeeCore\Widgets\BetterWidgetImageGallery\Renderer $renderer
 */

?>

<div class="rto-gallery-container clearfix">

    <?php echo $headerTitle; ?>

    <?php if ( $renderer->inModal() ) : ?>

        <?php echo $backLink; ?>

    <?php endif; ?>

    <?php if ( $settings['first_image'] == 'no' || $renderer->inModal() ) : ?>

        <?php foreach ( $gallery as $image ) : ?>

            <?php $image->accept( $renderer ); ?>

        <?php endforeach; ?>

    <?php else : ?>

        <?php $gallery->getThumb()->accept( $renderer ); ?>

    <?php endif; ?>

    <?php echo $sedcard; ?>

</div>
