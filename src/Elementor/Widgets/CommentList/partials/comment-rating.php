<table class="elebee-ratings">
    <?php foreach ( $endResults as $key => $endResult ) : ?>
        <?php if ( $endResult[ 'name' ] != '' ) : ?>
            <tr class="elebee-rating">
                <td class="elebee-rating-name">
                    <?php echo $endResult[ 'name' ]; ?>
                </td>

                <td class="elebee-rating-stars">
                    <?php $average = $endResult[ 'points' ] / $endResult[ 'voters' ] ?>

                    <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                        <div class="elebee-rating-star">
                            <?php if ( $key == 'total' ) : ?>
                                <?php $checked = $i <= $average ? ' checked' : ''; ?>
                                <i class="<?php echo $endResult[ 'icon' ]; ?> total <?php echo $checked ?>"></i>
                            <?php else : ?>
                                <?php $selectedColor = ! empty( $endResult[ 'colorSelected' ] ) ? $endResult[ 'colorSelected' ] : '#FFA500'; ?>
                                <?php $defaultColor = ! empty( $endResult[ 'color' ] ) ? $endResult[ 'color' ] : '#000000'; ?>
                                <?php $color = $i <= $average ? $selectedColor : $defaultColor; ?>
                                <i class="<?php echo $endResult[ 'icon' ]; ?>" style="color: <?php echo $color; ?>"></i>
                            <?php endif; ?>
                        </div>
                    <?php endfor; ?>

                    <span><?php printf(
                            $settings[ 'ratings_format_text' ],

                            round( ( $endResult[ 'points' ] / $endResult[ 'voters' ] ) * 2 ) / 2,
                            5
                        ); ?></span>
                </td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>
</table>

<?php if ( $settings[ 'ratings_show_seo_rating' ] ) : ?>
    <script type="application/ld+json">{
            "@context": "http://schema.org",
            "@type": "Organization",
            "name": "<?php echo get_bloginfo( 'name' ); ?>",
            "aggregateRating": {
                "@type": "AggregateRating",
                "ratingValue": "<?php echo round( ( $endResults[ 'total' ][ 'points' ] / $endResults[ 'total' ][ 'voters' ] ) * 2 ) / 2; ?>",
                "reviewCount": "<?php echo $endResults[ 'total' ][ 'voters' ]; ?>"
            }
        }
















    </script>
<?php endif; ?>