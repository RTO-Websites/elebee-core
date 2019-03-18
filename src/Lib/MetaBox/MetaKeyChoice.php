<?php
/**
 * MetaKeyChoice.php
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\MetaBox
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/MetaBox/MetaKeyChoice.html
 */

namespace ElebeeCore\Lib\MetaBox;


use ElebeeCore\Lib\Util\Template;

\defined( 'ABSPATH' ) || exit;

/**
 * Class MetaKeyChoice
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\MetaBox
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/MetaBox/MetaKeyChoice.html
 */
class MetaKeyChoice extends MetaKeyBase {

    /**
     * @since 0.2.0
     * @var array
     */
    private $choices;

    /**
     * MetaKeyTextfield constructor.
     *
     * @since   0.2.0
     *
     * @param string $key
     * @param string $label
     * @param int    $type
     */
    public function __construct( string $key, string $label, int $type = self::TYPE_DEFAULT ) {

        $this->choices = [];

        $defaultTemplate = new Template( __DIR__ . '/partials/meta-key-select-default.php' );
        $this->setTemplate( $defaultTemplate );

        parent::__construct( $key, $label, $type );

    }

    /**
     * Get the choices.
     *
     * @since   0.2.0
     *
     * @return array
     */
    public function getChoices(): array {

        return $this->choices;

    }

    /**
     * Add a choice.
     *
     * @since   0.2.0
     *
     * @param string $value
     * @param string $label
     *
     * @return void
     */
    public function addChoice( string $value, string $label ) {

        $this->choices[] = [
            'value' => $value,
            'label' => $label,
        ];

    }

}