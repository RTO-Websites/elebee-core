<?php

/**
 * MetaKeyTextarea.php
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\MetaBox
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/MetaBox/MetaKeyTextarea.html
 */

namespace ElebeeCore\Lib\MetaBox;


use ElebeeCore\Lib\Util\Template;

defined( 'ABSPATH' ) || exit;

/**
 * Class MetaKeyTextarea
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\MetaBox
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/MetaBox/MetaKeyTextarea.html
 */
class MetaKeyTextarea extends MetaKeyBase {

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

        $defaultTemplate = new Template( __DIR__ . '/partials/meta-key-textarea-default.php' );
        $this->setTemplate( $defaultTemplate );

        parent::__construct( $key, $label, $type );

    }

}