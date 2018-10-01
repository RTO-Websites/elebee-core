<?php if ( !empty( $content ) ) : ?>
<<?php echo $tags[ 'tr' ]; ?> class="cfs-table-tr">
<?php if ( $showHeading ) : ?>
	<<?php echo $tags[ 'th' ]; ?> class="cfs-table-th <?php echo $headingAlign; ?>">
	<?php echo $heading; ?>
	</<?php echo $tags[ 'th' ]; ?>>
<?php endif; ?>
<<?php echo $tags[ 'td' ]; ?> class="cfs-table-td">
	<div class="youtube-container">
		<iframe class="youtube-video" src="https://www.youtube.com/embed/<?php echo $content; ?>?rel=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
	</div>
</<?php echo $tags[ 'td' ]; ?>>
</<?php echo $tags[ 'tr' ]; ?>>
<?php endif; ?>