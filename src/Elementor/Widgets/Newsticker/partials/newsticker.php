<div class="elebee-newsticker-wrapper">
    <div class="elebee-newsticker-container">
        <?php if ( 'right' === $newstickerStartPosition ):
            printf( '
                <%1$s %2$s>
                    <span class="elebee-newsticker-right"><span>%3$s</span></span><span class="elebee-newsticker-right"><span>%3$s</span></span>
                </%1$s>',
                $newstickerTag,
                $newstickerAttributes,
                $newstickerText );
        else:
            printf( '
                <%1$s %2$s>
                    <span class="elebee-newsticker-left"><span>%3$s</span></span><span class="elebee-newsticker-left"><span>%3$s</span></span>
                </%1$s>',
                $newstickerTag,
                $newstickerAttributes,
                $newstickerText );
        endif; ?>
    </div>
</div>