<?php
/**
 * @since   1.0.0
 * @author  hterhoeven
 * @licence MIT
 */

namespace ElebeeCore\Extensions\GlobalSettings;


use ElebeeCore\Extensions\ExtensionBase;

/**
 * Class GlobalSettingBase
 * @package ElebeeCore\Extensions\GlobalSettings
 */
abstract class GlobalSettingBase extends ExtensionBase {

    /**
     * @var string
     */
    private $settingName;

    /**
     * GlobalSettingBase constructor.
     * @param string $settingName
     * @param string $hook
     * @param int    $priority  (optional) (default: 10)
     * @param int    $argsCount (optional) (default: 1)
     */
    public function __construct( string $settingName, string $hook, int $priority = 10, int $argsCount = 1 ) {

        $this->settingName = $settingName;
        parent::__construct( $hook, $priority, $argsCount );

    }

    /**
     * @return string
     */
    public function getSettingName(): string {

        return $this->settingName;

    }

    /**
     *
     */
    public function defineAdminHooks() {

        parent::defineAdminHooks();
        $this->getLoader()->addFilter( 'elementor/general/settings/success_response_data', $this, 'save', 10, 3 );
        $this->getLoader()->addFilter( 'elementor/editor/localize_settings', $this, 'setValue', 10, 2 );

    }

    /**
     * @param $successResponseData
     * @param $id
     * @param $data
     * @return mixed
     */
    public function save( $successResponseData, $id, $data ) {

        $value = $data[$this->settingName] ?? null;
        update_option( $this->settingName, $value );

        return $successResponseData;

    }

    /**
     * @param $settings
     * @param $postId
     * @return mixed
     */
    public function setValue( $settings, $postId ) {

        $settings['settings']['general']['settings'][$this->getSettingName()] = get_option( $this->getSettingName() );
        return $settings;

    }

}