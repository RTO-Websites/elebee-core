<?php
/**
 * @since   1.0.0
 * @author  hterhoeven
 * @licence MIT
 */

namespace ElebeeCore\Extensions\GlobalSettings;


use ElebeeCore\Lib\Hooking;
use Elementor\Controls_Manager;
use Leafo\ScssPhp\Compiler;
use Leafo\ScssPhp\Formatter\Crunched;

defined( 'ABSPATH' ) || exit;

/**
 * Class CustomCss
 * @package ElebeeCore\Extensions\GlobalSettings
 */
class CustomCss extends Hooking {

    /**
     * @var string
     */
    private $settingName;

    /**
     * @var string
     */
    private $customGlobalCssFile;

    /**
     * @var string
     */
    private $customGlobalCssFileUrl;

    /**
     * CustomCss constructor.
     */
    public function __construct() {

        $this->settingName = 'elebee_custom_global_css';

        $file = 'css/custom-global.css';

        $this->customGlobalCssFile = trailingslashit( get_stylesheet_directory() ) . $file;
        $this->customGlobalCssFileUrl = trailingslashit( get_stylesheet_directory_uri() ) . $file;

        parent::__construct();

    }

    /**
     *
     */
    public function defineAdminHooks() {

        $this->getLoader()->addAction( 'elementor/editor/global-settings', $this, 'extend' );
        $this->getLoader()->addAction( 'update_option_' . $this->settingName, $this, 'buildCssFile', 10, 2 );

    }

    /**
     *
     */
    public function definePublicHooks() {

        $this->getLoader()->addAction( 'elementor/frontend/after_register_scripts', $this, 'enqueueStyles' );

    }

    /**
     * @param array $controls
     * @return array
     */
    public function extend( array $controls ) {

        $controls['style']['style']['controls'][$this->settingName] = [
            'label' => __( 'Global CSS', 'elebee' ),
            'type' => Controls_Manager::CODE,
            'language' => 'scss',
            'render_type' => 'ui',
            'description' => sprintf( __( 'Use SCSS syntax (%ssee%s)', 'elementor' ), '<a href="https://sass-lang.com/guide" target="_blank">', '</a>' ),
        ];

        return $controls;

    }

    /**
     *
     */
    public function enqueueStyles() {

        wp_enqueue_style( 'custom-global', $this->customGlobalCssFileUrl, [ 'main', 'elementor-global', 'elementor-frontend' ] );

    }

    /**
     *
     */
    public function buildCssFile( $oldValue, $value ) {

        $scss = $value;

        $scssCompiler = new Compiler();
        $scssCompiler->setFormatter( Crunched::class );
        if ( WP_DEBUG ) {
            $scssCompiler->setLineNumberStyle( Compiler::LINE_COMMENTS );
        }
        $css = $scssCompiler->compile( $scss );

        file_put_contents( $this->customGlobalCssFile, $css );

    }

}