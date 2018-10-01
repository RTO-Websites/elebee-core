<<?php echo $tag; ?> class="cfs-tab-title <?php echo $titleAlign; ?>">
<?php echo $title; ?>
</<?php echo $tag; ?>>
<?php
$isPDF = filter_input(INPUT_GET, 'pdf', FILTER_VALIDATE_INT);
if( $isPDF > 0 ) {
	echo '<div class="cf-tab-title-decoration"></div>';
}