<?php
/**
 * JQuery.php
 *
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 */

namespace ElebeeCore\Admin\Setting\CoreData;


use ElebeeCore\Admin\Setting\Lib\SettingText;

\defined( 'ABSPATH' ) || exit;

class SettingEmail extends SettingText {

    /**
     * SettingEmail constructor.
     */
    public function __construct() {

        parent::__construct( 'elebee_core_data_email', __( 'E-Mail address', 'elebee' ), '' );

    }

}