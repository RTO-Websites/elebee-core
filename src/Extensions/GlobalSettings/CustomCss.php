<?php
/**
 * @since   1.0.0
 * @author  hterhoeven
 * @licence MIT
 */

namespace ElebeeCore\Extensions\GlobalSettings;


use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Leafo\ScssPhp\Compiler;
use Leafo\ScssPhp\Formatter\Crunched;

defined( 'ABSPATH' ) || exit;

/**
 * Class CustomCss
 * @package ElebeeCore\Extensions\GlobalSettings
 */
class CustomCss extends GlobalSettingBase {

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

        $file = 'css/custom-global.css';

        $this->customGlobalCssFile = trailingslashit( get_stylesheet_directory() ) . $file;
        $this->customGlobalCssFileUrl = trailingslashit( get_stylesheet_directory_uri() ) . $file;

//        parent::__construct( 'elementor/editor/global-settings' );
        parent::__construct( 'elebee_custom_global_css', 'elementor/element/global-settings/style/before_section_end', 10, 2 );

    }

    /**
     *
     */
    public function defineAdminHooks() {

        parent::defineAdminHooks();
        $this->getLoader()->addAction( 'update_option_' . $this->getSettingName(), $this, 'buildCssFile', 10, 2 );

    }

    /**
     *
     */
    public function definePublicHooks() {

        parent::definePublicHooks();
        $this->getLoader()->addAction( 'elementor/frontend/after_register_scripts', $this, 'enqueueStyles' );

    }

    /**
     * @param array $controls
     * @return array
     */
    public function extend( Controls_Stack $controls ) {

        $controls->add_control( $this->getSettingName(), [
            'label' => __( 'Global CSS', 'elebee' ),
            'type' => Controls_Manager::CODE,
            'language' => 'scss',
            'render_type' => 'ui',
            'description' => sprintf( __( 'Use SCSS syntax (%ssee%s)', 'elementor' ), '<a href="https://sass-lang.com/guide" target="_blank">', '</a>' ),
        ] );

        return $controls;

    }

    public function save( $successResponseData, $id, $data ) {

        $successResponseData = array_merge( $successResponseData, $this->buildCssFile( $data[$this->getSettingName()] ) );
        return parent::save( $successResponseData, $id, $data );

    }


    /**
     *
     */
    public function enqueueStyles() {

        wp_enqueue_style( 'custom-global', $this->customGlobalCssFileUrl, [ 'main', 'elementor-frontend' ] );

    }

    /**
     *
     */
    public function buildCssFile( $scss ): array {

        $scssCompiler = new Compiler();
        $scssCompiler->setFormatter( Crunched::class );

        if ( WP_DEBUG ) {
            $scssCompiler->setLineNumberStyle( Compiler::LINE_COMMENTS );
        }

        try {

            $output = $scssCompiler->compile( $scss );
            $success = true;

            file_put_contents( $this->customGlobalCssFile, $output );

        } catch ( \Exception $e ) {

            $output = $e->getMessage();
            $success = false;

        }

        return [ 'css' => [
            'output' => $output,
            'success' => $success,
        ] ];

    }

}