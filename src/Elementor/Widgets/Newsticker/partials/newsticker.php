<div class="elebee-newsticker-wrapper">
    <div class="elebee-newsticker-container">
        <?php if ( 'right' === $newstickerStartPosition ):
            printf( '
                <%1$s %2$s>
                    <span class="elebee-newsticker-first elebee-newsticker-right">%3$s</span>
                </%1$s>',
                $newstickerTag,
                $newstickerAttributes,
                $newstickerText );
        else:
            printf( '
                <%1$s %2$s>
                    <span class="elebee-newsticker-first elebee-newsticker-left">%3$s</span>
                    <span class="elebee-newsticker-secound elebee-newsticker-left">%3$s</span>
                </%1$s>',
                $newstickerTag,
                $newstickerAttributes,
                $newstickerText );
        endif; ?>
    </div>
</div>