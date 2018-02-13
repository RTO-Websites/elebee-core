<?php
/**
 * @since   1.0.0
 * @author  hterhoeven
 * @licence MIT
 */

namespace ElebeeCore\Extensions\GlobalSettings;


use ElebeeCore\Extensions\ExtensionBase;

abstract class GlobalSettingBase extends ExtensionBase {

    private $settingName;

    public function __construct( string $settingName, string $hook, int $priority = 10, int $argsCount = 1 ) {

        parent::__construct( $hook, $priority, $argsCount );
        $this->settingName = $settingName;

    }

    public function getSettingName() {

        return $this->settingName;

    }

    public function defineAdminHooks() {

        parent::defineAdminHooks();
        $this->getLoader()->addFilter( 'elementor/general/settings/success_response_data', $this, 'save', 10, 3 );
        $this->getLoader()->addFilter( 'elementor/editor/localize_settings', $this, 'setValue', 10, 2 );

    }

    public function save( $successResponseData, $id, $data ) {

        $value = $data[$this->settingName] ?? null;
        update_option( $this->settingName, $value );

        return $successResponseData;

    }

    public function setValue( $settings, $postId ) {

        $settings['settings']['general']['settings'][$this->getSettingName()] = get_option( $this->getSettingName() );
        return $settings;

    }

}