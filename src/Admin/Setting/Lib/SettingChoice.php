<?php
/**
 * SettingCoice.php
 *
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 */

namespace ElebeeCore\Admin\Setting\Lib;


use ElebeeCore\Lib\Util\Template;

\defined( 'ABSPATH' ) || exit;

class SettingChoice extends SettingBase {

    private $choices;

    public function __construct( string $name, string $title, $default = false ) {

        parent::__construct( $name, $title, $default );
        $this->choices = [];

    }

    public function addChoice( $value, $label ) {

        $this->choices[$value] = $label;

    }

    public function render( array $args ) {

        $template = new Template( dirname( __DIR__ ) . '/partials/choice-select.php', [
            'name' => $this->getName(),
            'choices' => $this->choices,
            'option' => $this->getOption(),
        ] );
        $template->render();

    }

}