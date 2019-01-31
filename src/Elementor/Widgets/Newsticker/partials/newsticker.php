<div class="elebee-newsticker-wrapper">
    <div class="elebee-newsticker-container">
        <?php if( $newstickerStartPosition === 'right' ): ?>
            <?php printf( '
                <%1$s %2$s>
                    <div class="elebee-newsticker-first">%3$s&nbsp;&nbsp;&nbsp;</div>
                    <div class="elebee-newsticker-secound">%3$s&nbsp;&nbsp;&nbsp;</div>
                </%1$s>',
                $newstickerTag,
                $newstickerAttributes,
                $newstickerText );
            ?>
        <?php else: ?>
        <?php printf( '
                <%1$s %2$s>
                    <div class="elebee-newsticker-first elebee-newsticker-left">%3$s&nbsp;&nbsp;&nbsp;</div>
                    <div class="elebee-newsticker-secound elebee-newsticker-left">%3$s&nbsp;&nbsp;&nbsp;</div>
                </%1$s>',
            $newstickerTag,
            $newstickerAttributes,
            $newstickerText );
        ?>
        <?php endif; ?>
    </div>
</div>