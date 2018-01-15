<?php

/**
 * @var $link array|string|false An array/string containing the link URL, or false if no link.
 * @var $linkAttributes string
 * @var $backgroundImage string
 */

?>

<div class="rto-image-ratio-container">

    <?php if ( $link ) : ?>

        <a <?php echo $linkAttributes; ?>>

    <?php endif; ?>

    <span class="image" style="background-image: url('<?php echo $backgroundImage; ?>');"></span>

    <?php if ( $link ) : ?>

        </a>

    <?php endif; ?>

</div>