<div class="elebee-newsticker-wrapper">
    <div class="elebee-newsticker-container">
        <?php printf( '<%1$s %2$s>%3$s</%1$s>',
            $newstickerTag,
            $newstickerAttributes,
            $newstickerText );
        ?>
    </div>
    <?php if ( $newstickerRepeat ): ?>
        <div class="elebee-newsticker-container hide">
            <?php printf( '<%1$s %2$s>%3$s</%1$s>',
                $newstickerTag,
                $newstickerAttributes,
                $newstickerText );
            ?>
        </div>
        <?php endif ?>
</div>