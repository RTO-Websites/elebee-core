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

class SettingBinary extends SettingBase {

    public function render( array $args ) {

        $template = new Template( dirname( __DIR__ ) . '/partials/checkbox.php', [
            'name' => $this->getName(),
            'value' => 1,
            'option' => $this->getOption(),
        ] );
        $template->render();

    }

}