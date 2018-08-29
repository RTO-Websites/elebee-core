<?php
/**
 * ThemeSupportCustomLogo.php
 *
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 */

namespace ElebeeCore\Lib\ThemeSupport;

defined( 'ABSPATH' ) || exit;


/**
 * Class ThemeSupportCustomLogo
 *
 * @package ElebeeCore\Lib\ThemeSupport
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/ThemeSupport/ThemeSupportCustomLogo.html
 */
class ThemeSupportCustomLogo extends ThemeSupportBase {

    /**
     * @inheritdoc
     */
    public function addThemeSupport() {

        add_theme_support( 'custom-logo' );

    }

}