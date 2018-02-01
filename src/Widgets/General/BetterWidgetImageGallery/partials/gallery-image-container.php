<?php

/**
 * @var string $galleryId
 * @var string $thumbnail
 * @var string $href
 * @var string $title
 * @var array $settings
 */

?>

<div class="rto-gallery-image-container">

    <div class="rto-gallery-image-ratio-container">

        <div class="gallery-item">

            <a class="elementor-clickable <?php echo $settings['gallery_style']; ?>"
                    data-elementor-open-lightbox="<?php echo $settings['open_lightbox']; ?>"
                    data-elementor-lightbox-slideshow="<?php echo $galleryId; ?>"
                    href="<?php echo $href; ?>"
            >
                <?php echo $thumbnail; ?>

                <?php if ( !empty( $settings['icon'] ) ) : ?>

                    <div class="rto-gallery-icon-wrapper">
                        <i class="icon <?php echo $settings['icon']; ?>"></i>
                    </div>

                <?php endif; ?>

                <div class="rto-gallery-item-overlay">
                    <?php echo $title; ?>
                </div>

            </a>

        </div>

    </div>

</div>
