<?php

/**
 * @var array $attributeList
 */

?>

<div class="rto-gallery-sedcard">
    <table>
        <?php foreach ( $attributeList as $key => $value ) : ?>
            <tr>
                <td class="rto-gallery-sedcard-key">
                    <?php echo $key; ?>
                </td>
                <td class="rto-gallery-sedcard-value">
                    <?php echo implode( ',', $value ); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
