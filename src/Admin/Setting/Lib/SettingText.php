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

class SettingText extends SettingBase {

    public function render( array $args ) {

        $template = new Template( dirname( __DIR__ ) . '/partials/textfield.php', [
            'name' => $this->getName(),
            'value' => $this->getOption(),
            'placeholder' => isset( $args['placeholder'] ) ? $args['placeholder'] : '',
        ] );
        $template->render();

    }

}