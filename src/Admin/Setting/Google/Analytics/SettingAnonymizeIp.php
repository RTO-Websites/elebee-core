<?php
/**
 * JQuery.php
 *
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 */

namespace ElebeeCore\Admin\Setting\Google\Analytics;


use ElebeeCore\Admin\Setting\Lib\SettingBinary;

\defined( 'ABSPATH' ) || exit;

class SettingAnonymizeIp extends SettingBinary {

    /**
     * SettingAnonymizeIp constructor.
     */
    public function __construct() {

        parent::__construct( 'elebee_google_analytics_anonymize_ip', __( 'Anonymize IP', 'elebee' ), true );

    }

}