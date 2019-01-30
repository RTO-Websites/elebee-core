<div class="elebee-newsticker-wrapper">
    <div class="elebee-newsticker-container">
        <?php echo sprintf( '<%1$s %2$s>%3$s</%1$s>',
            $newstickerSize,
            $newstickerAttributes,
            $newstickerText );
        ?>
    </div>
    <?php if ( $newstickerRepeat ) {
        ?>
        <div class="elebee-newsticker-container hide">
            <?php echo sprintf( '<%1$s %2$s>%3$s</%1$s>',
                $newstickerSize,
                $newstickerAttributes,
                $newstickerText );
            ?>
        </div>
        <?php
    }
    ?>
</div>