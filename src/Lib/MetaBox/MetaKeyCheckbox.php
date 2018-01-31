<?php

/**
 * Singleton.php
 */

namespace ElebeeCore\Lib\MetaBox;


use ElebeeCore\Lib\Template;

/**
 * Class MetaKeyCheckbox
 *
 * @package ElebeeCore
 * @author RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @since 0.2.0
 */
class MetaKeyCheckbox extends MetaKey {

    /**
     * {@inheritdoc}
     */
    public function __construct( $key, $label, $type = self::TYPE_DEFAULT ) {

        $defaultTemplate = new Template( __DIR__ . '/partials/meta-key-checkbox-default.php' );
        $this->setTemplate( $defaultTemplate );

        parent::__construct( $key, $label, $type );

    }

}