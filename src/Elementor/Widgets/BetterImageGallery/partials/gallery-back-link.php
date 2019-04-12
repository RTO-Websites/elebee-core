<?php
/**
 * @var string $buttonSize
 * @var string $buttonText
 * @var string $buttonHoverAnimation
 * @var string $buttonIcon
 * @var string $buttonIconAlign
 * @var string $url
 */

?>

<div class="elementor-button-wrapper">

    <a class="elementor-button elementor-size-<?php echo $buttonSize; ?> elementor-animation-<?php echo $buttonHoverAnimation; ?>"
            href="<?php echo $url; ?>">
        <span class="elementor-button-content-wrapper">
            <?php if ( !empty( $buttonIcon ) ) : ?>
                <span class="elementor-button-icon elementor-align-icon-<?php echo $buttonIconAlign; ?>">
                    <i class="<?php echo $buttonIcon; ?>"></i>
                </span>
            <?php endif; ?>
            <span class="elementor-button-text">
                <?php echo $buttonText; ?>
            </span>
        </span>
    </a>
</div>
