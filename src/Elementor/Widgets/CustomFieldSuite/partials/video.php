<?php if ( !empty( $content ) ) : ?>
<<?php echo $tags[ 'tr' ]; ?> class="cfs-table-tr">
<?php if ( $showHeading ) : ?>
	<<?php echo $tags[ 'th' ]; ?> class="cfs-table-th <?php echo $headingAlign; ?>">
	<?php echo $heading; ?>
	</<?php echo $tags[ 'th' ]; ?>>
<?php endif; ?>
<<?php echo $tags[ 'td' ]; ?> class="cfs-table-td">
	<video width="100%" controls>
		<source src="<?php echo $content; ?>" type="video/mp4">
		Your browser does not support the video tag.
	</video>
</<?php echo $tags[ 'td' ]; ?>>
</<?php echo $tags[ 'tr' ]; ?>>
<?php endif; ?>