<figure class="post-type-archive-figure">
    <div class="post-type-archive-inner-wrap">
        <a href="<?php the_permalink(); ?>">
            <?php
                the_post_thumbnail(
                    $size,
                    [
                            'class' => 'post-type-archive-thumb'
                    ]
                );
            ?>
            <?php echo $figureContent; ?>
        </a>
    </div>
</figure>