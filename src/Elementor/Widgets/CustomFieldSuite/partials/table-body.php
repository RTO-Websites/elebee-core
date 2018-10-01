<?php $class = isset( $tags[ 'class' ]) ? $tags[ 'class' ] : ''; ?>

<<?php echo $tags[ 'tr' ]; ?> class="cfs-table-tr <?php echo $class; ?>">
<?php foreach ( $values as $valueName => $value ) : ?>
	<?php if ( empty( $settings[ $cfsName . '_' . $valueName ] ) ) : continue; endif; ?>
	<<?php echo $tags[ 'td' ]; ?> class="cfs-table-td"><?php echo $value; ?></<?php echo $tags[ 'td' ]; ?>>
<?php endforeach; ?>
</<?php echo $tags[ 'tr' ]; ?>>
