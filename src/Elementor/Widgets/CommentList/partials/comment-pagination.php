<?php

/**
 * @var string $paginationFirst
 * @var string $paginationPrev
 * @var string $paginationPages
 * @var string $paginationNext
 * @var string $paginationLast
 */

?>

<ul class="pagination" role="menubar" aria-label="Pagination">
    <?php
    if ( $paginationFirst ) {
        echo $paginationFirst;
    }
    if ( $paginationPrev ) {
        echo '<li class="arrow">' . $paginationPrev . '</li>';
    }
    if ( $paginationPages ) {
        echo $paginationPages;
    }
    if ( $paginationNext ) {
        echo '<li class="arrow">' . $paginationNext . '</li>';
    }
    if ( $paginationLast ) {
        echo $paginationLast;
    }
    ?>
</ul>