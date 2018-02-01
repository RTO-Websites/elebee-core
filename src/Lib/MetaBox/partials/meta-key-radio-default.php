<p class="post-attributes-label-wrapper">
    <label class="post-attributes-label" for="<?php echo $metaKey->getKey(); ?>">
        <?php echo $metaKey->getLabel(); ?>
    </label>

</p>

<?php foreach ( $metaKey->getChoices() as $choice ) : ?>
    <label>
        <input type="radio"
                name="<?php echo $metaKey->getKey(); ?>"
                value="<?php echo $choice['value']; ?>"<?php checked( $choice['value'], $metaKey->getValue() ); ?> />
        <?php echo $choice['label']; ?>
    </label>
    <br>
<?php endforeach; ?>