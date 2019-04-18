<?php
/**
 * JQuery.php
 *
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 */

namespace ElebeeCore\Admin\Setting;


use ElebeeCore\Admin\Setting\Lib\SettingChoice;

\defined( 'ABSPATH' ) || exit;

class SettingJQuery extends SettingChoice {

    /**
     * JQuery constructor.
     */
    public function __construct() {

        parent::__construct( 'jquery', __( 'jQuery', 'elebee' ), 'default' );
        $this->addChoice( 'default', __( 'Default', 'elebee' ) );
        $this->addChoice( 'latest-cdn', __( '3.3.1 (CDN)', 'elebee' ) );
        $this->addChoice( 'latest-local', __( '3.3.1 (local)', 'elebee' ) );

    }

}