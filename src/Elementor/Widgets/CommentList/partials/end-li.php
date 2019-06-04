<?php
/**
 * @var $url string
 * @var $text string
 * @var $buttonClass string
 * @var $iconClass string
 * @var $iconAlign string
 */
?>

<li class="arrow">
    <a href="<?php echo $url; ?>" class="page-numbers">
        <span class="elebee-button-content-wrapper <?php echo $buttonClass; ?>">
            <span class="elebee-icon elebee-button-icon elebee-align-icon-<?php echo $iconAlign; ?>">
                <i class="<?php echo $iconClass; ?>"></i>
            </span>
            <span class="elebee-button-text">
                <?php echo $text; ?>
            </span>
        </span>
    </a>
</li>