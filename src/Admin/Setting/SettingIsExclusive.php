<?php
/**
 * IsExclusive.php
 *
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 */

namespace ElebeeCore\Admin\Setting;


use ElebeeCore\Admin\Setting\Lib\SettingBinary;

\defined( 'ABSPATH' ) || exit;

class SettingIsExclusive extends SettingBinary {

    /**
     * IsExclusive constructor.
     */
    public function __construct() {

        parent::__construct( 'is_exclusive', __( 'Is Exclusive', 'elebee' ), false );

    }

}