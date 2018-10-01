<select name="<?php echo $name ?>" id="<?php echo $name ?>">

    <?php foreach ( $choices as $value => $label ) : ?>
        <option value="<?php echo $value ?>"<?php selected( $value, $option, true ) ?>><?php echo $label ?></option>
    <?php endforeach; ?>

</select>