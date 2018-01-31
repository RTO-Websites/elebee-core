<?php

/**
 * MetaKeyTextfield.php
 */

namespace ElebeeCore\Lib\MetaBox;


use ElebeeCore\Lib\Template;

/**
 * Class MetaKeyTextfield
 *
 * @package ElebeeCore
 * @author RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @since 0.2.0
 */
class MetaKeyTextfield extends MetaKey {

    /**
     * MetaKeyTextfield constructor.
     *
     * @param $key
     * @param $label
     * @param int $type (optional)
     */
    public function __construct( $key, $label, $type = self::TYPE_DEFAULT ) {

        $defaultTemplate = new Template( __DIR__ . '/partials/meta-key-textfield-default.php' );
        $this->setTemplate( $defaultTemplate );

        parent::__construct( $key, $label, $type );

    }

}