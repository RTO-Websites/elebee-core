<?php
/**
 * @since   0.3.0
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 */

namespace ElebeeCore\Extensions\GlobalSettings;


use ElebeeCore\Admin\Editor\CodeMirror;
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
     * @var string
     */
    private $postTypeName;

    /**
     * CustomCss constructor.
     */
    public function __construct() {

        $file = 'css/custom-global.css';

        $this->customGlobalCssFile = trailingslashit( get_stylesheet_directory() ) . $file;
        $this->customGlobalCssFileUrl = trailingslashit( get_stylesheet_directory_uri() ) . $file;
        $this->postTypeName = 'elebee-global-css';

        parent::__construct( 'elebee_custom_global_css', 'elementor/element/global-settings/style/before_section_end', 10, 2 );

    }

    /**
     *
     */
    public function defineAdminHooks() {

        parent::defineAdminHooks();
        $this->getLoader()->addAction( 'current_screen', $this, 'initCodeMirror' );
        $this->getLoader()->addAction( 'admin_enqueue_scripts', $this, 'enqueueAdminStyles' );
        $this->getLoader()->addAction( 'admin_enqueue_scripts', $this, 'enqueueAdminScripts' );
        $this->getLoader()->addAction( 'transition_post_status', $this, 'buildCssFile', 10, 2 );
        $this->getLoader()->addAction( 'elementor/editor/before_enqueue_scripts', $this, 'enqueueEditorScripts' );

        $this->getLoader()->addFilter( 'admin_body_class', $this, 'collapseAdminMenu' );

    }

    /**
     *
     */
    public function definePublicHooks() {

        parent::definePublicHooks();
        $this->getLoader()->addAction( 'init', $this, 'registerPostType' );
        $this->getLoader()->addAction( 'elementor/frontend/after_register_scripts', $this, 'enqueuePublicStyles' );

    }

    /**
     * @param Controls_Stack $controls
     * @return Controls_Stack
     */
    public function extend( Controls_Stack $controls ) {

        return $controls;

    }

    /**
     * @param $successResponseData
     * @param $id
     * @param $data
     * @return mixed
     */
    public function save( $successResponseData, $id, $data ) {

        return $successResponseData;

    }

    /**
     *
     */
    public function enqueueAdminStyles() {

        $stylesUrl = trailingslashit( get_stylesheet_directory_uri() ) . 'vendor/rto-websites/elebee-core/src/Admin/Editor/css/';
        wp_enqueue_style( 'elebee-editor', $stylesUrl . 'editor.css', [ 'codemirror' ] );

    }

    public function enqueueAdminScripts() {

        global $post;

        if ( !$post || $post->post_type != $this->postTypeName ) {
            return;
        }

        $scriptsUrl = trailingslashit( get_stylesheet_directory_uri() ) . 'vendor/rto-websites/elebee-core/src/Extensions/GlobalSettings/CustomCss/';
        wp_enqueue_script( 'elebee-capture-editor-input', $scriptsUrl . 'capture-editor-input.js', [ 'config-codemirror' ], '1.0.0', true );

    }

    public function enqueueEditorScripts() {

        $scriptsUrl = trailingslashit( get_stylesheet_directory_uri() ) . 'vendor/rto-websites/elebee-core/src/Extensions/GlobalSettings/CustomCss/';
        wp_enqueue_script( 'custom-css', $scriptsUrl . 'main.js', [ 'jquery' ] );

    }

    /**
     *
     */
    public function enqueuePublicStyles() {

        wp_enqueue_style( 'custom-global', $this->customGlobalCssFileUrl, [ 'main', 'elementor-frontend' ] );

    }

    /**
     *
     */
    public function initCodeMirror() {

        $screen = get_current_screen();

        if ( is_object( $screen ) && $this->postTypeName == $screen->post_type ) {

            $codeMirror = new CodeMirror( CodeMirror::WYSIWYG_DISABLE );
            $codeMirror->getLoader()->run();

        }

    }

    /**
     * @param $newStatus
     * @param $oldStatus
     * @param $post
     */
    public function applyChanges( $newStatus, $oldStatus, $post ) {

        if ( $post->post_type != $this->postTypeName ) {
            return;
        }

        if ( $newStatus == 'publish' || $oldStatus == 'publish' && $newStatus != $oldStatus ) {

            $result = $this->buildCssFile();

        }

    }

    /**
     * @return array
     */
    public function buildCssFile(): array {

        try {

            $scss = '';
            $query = new \WP_Query( [
                'post_type' => $this->postTypeName,
                'post_status' => 'publish',
                'posts_per_page' => -1,
            ] );

            while ( $query->have_posts() ) {
                $query->the_post();
                $scss .= get_the_content();
            }

            wp_reset_query();

            $output = $this->compileScss( $scss );
            file_put_contents( $this->customGlobalCssFile, $output );
            $success = true;

        } catch ( \Exception $e ) {

            $output = $e->getMessage();
            $success = false;

        }

        return [ 'css' => [
            'output' => $output,
            'success' => $success,
        ] ];

    }

    /**
     * @param $scss
     * @return string
     * @throws \Exception
     */
    public function compileScss( $scss ) {

        $scssCompiler = new Compiler();
        $scssCompiler->setFormatter( Crunched::class );

        if ( WP_DEBUG ) {
            $scssCompiler->setLineNumberStyle( Compiler::LINE_COMMENTS );
        }

        return $scssCompiler->compile( $scss );

    }

    /**
     *
     */
    public function registerPostType() {

        $editable = current_user_can( 'publish_pages' );

        register_post_type( $this->postTypeName, [
            'labels' => [
                'name' => __( 'Global CSS', 'elebee' ),
                'singular_name' => __( 'Global CSS', 'elebee' ),
            ],
            'public' => false,
            'show_in_menu' => $editable,
            'show_ui' => $editable,
            'supports' => [
                'title',
                'editor',
                'revisions',
            ],
        ] );

    }

    public function collapseAdminMenu( $classes ) {

        global $post;

        if ( !$post || $post->post_type != $this->postTypeName ) {
            return $classes;
        }

        return $classes . ' folded';

    }

}