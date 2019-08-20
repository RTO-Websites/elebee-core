<div class="elementor-field-group elementor-column elementor-col-<?php echo $fieldWidth; ?>">
    <table class="elebee-ratings">
        <input type="hidden" name="widgetID" value="<?php echo $widgetID; ?>">
        <?php foreach ( $settings[ 'list_categories' ] as $key => $rating ) : ?>
            <tr class="elebee-rating elementor-repeater-item-<?php echo esc_attr( $rating[ '_id' ] ); ?>">
                <td class="elebee-rating-name">
                    <?php echo $rating[ 'category_label' ]; ?>
                    <?php echo $rating[ 'category_required' ] ? $required : ''; ?>
                </td>

                <td class="elebee-rating-stars">
                    <?php for ( $i = 1; $i <= 5; $i++ ): ?>
                        <div class="elebee-rating-star">
                            <input type="radio"
                                   id="elebee-rating-star-<?php echo $key; ?>-<?php echo $i; ?>"
                                <?php $rating[ 'category_required' ] ? 'required="required"' : '' ?>
                                   name="elebee-ratings[<?php echo esc_attr( $rating[ '_id' ] ); ?>] )"
                                   value="<?php echo $i; ?>"
                                   title="<?php echo $i . ' ' . ( $i == 1 ? 'Star' : 'Stars' ); ?>"/>

                            <i class="<?php echo $rating[ 'category_icon' ]; ?>"></i>

                            <label for="elebee-rating-star-<?php echo $key; ?>-<?php echo $i; ?>"><?php echo $i . ' ' . ( $i == 1 ? 'Star' : 'Stars' ); ?></label>
                        </div>
                    <?php endfor; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
