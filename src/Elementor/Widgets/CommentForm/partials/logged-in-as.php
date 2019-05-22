<?php

/**
 * @var $loggedInAs string
 * @var $logOut string
 * @var $adminUrl string Url
 * @var $userIdentity string
 * @var $logOutUrl string Url
 */

$userLink = sprintf( '<a href="%1$s">%2$s</a>', $adminUrl, $userIdentity );
$logOutLink = sprintf( '<a href="%1$s" title="Log out">%2$s</a>', $logOutUrl, $logOut );
?>

<div class="elementor-field-group elementor-column">
    <p class="logged-in-as">
        <?php echo sprintf( $loggedInAs . '%1$s. %2$s', $userLink, $logOutLink );
        ?>
    </p>
</div>