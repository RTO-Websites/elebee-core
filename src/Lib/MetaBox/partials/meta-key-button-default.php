<?php
    $config = $metaKey->getConfiguration();
?>
<p class="post-attributes-label-wrapper <?php echo $config[ 'class' ]; ?>">
    <input type="button"
           class="button-<?php echo $metaKey->getKey(); ?>"
           name="<?php echo $metaKey->getKey(); ?>"
           value="<?php echo $metaKey->getLabel(); ?>"
           <?php if( !empty( $config[ 'callback' ] ) ) {
               echo 'onclick="' .$config[ 'callback' ] . '(); return false;"';
           }
           ?>
    />
    <span class="button-description">
        <?php echo $config[ 'description' ]; ?>
    </span>
</p>
