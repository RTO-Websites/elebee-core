<?php
/**
 * MetaKeyChoice.php
 */

namespace ElebeeCore\Lib\MetaBox;


use ElebeeCore\Lib\Template;

/**
 * Class MetaKeyChoice
 *
 * @package ElebeeCore
 * @author RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @since 0.2.0
 */
class MetaKeyChoice extends MetaKey {

    /**
     * @var array
     *
     * @ignore
     */
    private $choices;

    /**
     * MetaKeyTextfield constructor.
     *
     * @param $key
     * @param $label
     * @param int $type
     */
    public function __construct( $key, $label, $type = self::TYPE_DEFAULT ) {

        $this->choices = [];

        $defaultTemplate = new Template( __DIR__ . '/partials/meta-key-select-default.php' );
        $this->setTemplate( $defaultTemplate );

        parent::__construct( $key, $label, $type );

    }

    /**
     * Get the choices.
     *
     * @return array
     */
    public function getChoices() {

        return $this->choices;

    }

    /**
     * Add a choice.
     *
     * @param $value
     * @param $label
     *
     * @return void
     */
    public function addChoice( $value, $label ) {

        $this->choices[] = [
            'value' => $value,
            'label' => $label,
        ];

    }

}