<<?php echo $tags[ 'tr' ]; ?> class="cfs-table-tr">
<?php if ( $showHeading ) : ?>
	<<?php echo $tags[ 'th' ]; ?> class="cfs-table-th <?php echo $headingAlign; ?>">
	<?php echo $heading; ?>
	</<?php echo $tags[ 'th' ]; ?>>
<?php endif; ?>
<<?php echo $tags[ 'td' ]; ?> class="cfs-table-td"><?php echo $content; ?></<?php echo $tags[ 'td' ]; ?>>
</<?php echo $tags[ 'tr' ]; ?>>
