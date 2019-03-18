<?php
/**
 * IsExclusiv.php
 *
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 */

namespace ElebeeCore\Admin\Setting\IsExclusiv;


use ElebeeCore\Admin\Setting\SettingBase;
use ElebeeCore\Lib\Util\Template;

\defined( 'ABSPATH' ) || exit;

class SettingIsExclusiv extends SettingBase {

    /**
     * IsExclusiv constructor.
     */
    public function __construct() {

        parent::__construct( 'is_exclusive', __( 'Is Exclusive', 'elebee' ), false );

    }

    public function render() {

        $template = new Template( dirname( __DIR__ ) . '/partials/checkbox.php', [
            'name' => $this->getName(),
            'value' => 1,
            'option' => $this->getOption(),
        ] );
        $template->render();

    }

}