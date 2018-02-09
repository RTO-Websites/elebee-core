<?php
/**
 * GlobalSettingBase.php
 *
 * @since   0.3.0
 *
 * @package ElebeeCore\Extensions\GlobalSettings
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Extensions/GlobalSettings/GlobalSettingBase.html
 */

namespace ElebeeCore\Extensions\GlobalSettings;


use ElebeeCore\Extensions\ExtensionBase;

defined( 'ABSPATH' ) || exit;

/**
 * Class GlobalSettingBase
 *
 * @since   0.3.0
 *
 * @package ElebeeCore\Extensions\GlobalSettings
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Extensions/GlobalSettings/GlobalSettingBase.html
 */
abstract class GlobalSettingBase extends ExtensionBase {

    /**
     * @since 0.3.0
     * @var string
     */
    private $settingName;

    /**
     * GlobalSettingBase constructor.
     *
     * @since 0.3.0
     *
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
     * @since 0.3.0
     *
     * @return string
     */
    public function getSettingName(): string {

        return $this->settingName;

    }

    /**
     * @since 0.3.0
     */
    public function defineAdminHooks() {

        parent::defineAdminHooks();
        $this->getLoader()->addFilter( 'elementor/general/settings/success_response_data', $this, 'save', 10, 3 );
        $this->getLoader()->addFilter( 'elementor/editor/localize_settings', $this, 'setValue', 10, 2 );

    }

    /**
     * @since 0.3.0
     *
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
     * @since 0.3.0
     *
     * @param $settings
     * @param $postId
     * @return mixed
     */
    public function setValue( $settings, $postId ) {

        $settings['settings']['general']['settings'][$this->getSettingName()] = get_option( $this->getSettingName() );
        return $settings;

    }

}