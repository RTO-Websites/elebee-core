<?php
/**
 * MetaKeyButton.php
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\MetaBox
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/MetaBox/MetaKeyButton.html
 */

namespace ElebeeCore\Lib\MetaBox;

use ElebeeCore\Lib\Util\Template;

\defined( 'ABSPATH' ) || exit;

class MetaKeyButton extends MetaKeyBase {

    /**
     * MetaKeyButton constructor.
     *
     * @since 0.7.0
     *
     * @param string $key
     * @param string $label
     * @param int    $type
     */
    public function __construct( string $key, string $label, int $type = self::TYPE_DEFAULT ) {

        $defaultTemplate = new Template( __DIR__ . '/partials/meta-key-button-default.php' );
        $this->setTemplate( $defaultTemplate );

        parent::__construct( $key, $label, $type );

    }
}
