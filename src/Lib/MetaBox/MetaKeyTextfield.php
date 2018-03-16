<?php

/**
 * MetaKeyTextfield.php
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\MetaBox
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/MetaBox/MetaKeyTextfield.html
 */

namespace ElebeeCore\Lib\MetaBox;


use ElebeeCore\Lib\Util\Template;

defined( 'ABSPATH' ) || exit;

/**
 * Class MetaKeyTextfield
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\MetaBox
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/MetaBox/MetaKeyTextfield.html
 */
class MetaKeyTextfield extends MetaKeyBase {

    /**
     * MetaKeyTextfield constructor.
     *
     * @since 0.2.0
     *
     * @param string $key
     * @param string $label
     * @param int    $type (optional)
     */
    public function __construct( string $key, string $label, int $type = self::TYPE_DEFAULT ) {

        $defaultTemplate = new Template( __DIR__ . '/partials/meta-key-textfield-default.php' );
        $this->setTemplate( $defaultTemplate );

        parent::__construct( $key, $label, $type );

    }

}