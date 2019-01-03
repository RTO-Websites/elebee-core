<?php
/**
 * JQuery.php
 *
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 */

namespace ElebeeCore\Admin\Setting\Google\Analytics;


use ElebeeCore\Admin\Setting\Lib\SettingText;

\defined( 'ABSPATH' ) || exit;

class SettingTrackingId extends SettingText {

    /**
     * SettingTrackingId constructor.
     */
    public function __construct() {

        parent::__construct( 'elebee_google_analytics_tracking_id', __( 'Trackting-ID', 'elebee' ), '' );

    }

}