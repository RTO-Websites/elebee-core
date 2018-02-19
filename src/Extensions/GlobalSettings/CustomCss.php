<?php
/**
 * @since   0.3.0
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
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
        $this->getLoader()->addAction( 'update_option_' . $this->getSettingName(), $this, 'buildCssFile', 10, 2 );
        $this->getLoader()->addAction( 'save_post_' . $this->postTypeName, $this, 'buildCssFileFromPost', 10, 3 );
        $this->getLoader()->addAction( 'admin_enqueue_scripts', $this, 'adminEnqueueScripts' );

    }

    /**
     *
     */
    public function definePublicHooks() {

        parent::definePublicHooks();
        $this->getLoader()->addAction( 'init', $this, 'registerPostType' );
        $this->getLoader()->addAction( 'elementor/frontend/after_register_scripts', $this, 'enqueueStyles' );

    }

    /**
     * @param Controls_Stack $controls
     * @return Controls_Stack
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

    public function adminEnqueueScripts( $hook ) {

        if ( !in_array( $hook, [ 'post.php', 'post-new.php' ] ) ) {
            return;
        }

        $screen = get_current_screen();

        if ( !is_object( $screen ) || $this->postTypeName != $screen->post_type ) {
            return;
        }

        $adminJSUri = trailingslashit( get_stylesheet_directory_uri() ) . 'vendor/rto-websites/elebee-core/src/Admin/js/';
        $codemirrorUri = $adminJSUri . 'codemirror/';

        wp_enqueue_style( 'codemirror', $codemirrorUri . 'codemirror.css' );
        wp_enqueue_style( 'codemirror-mdn-liket', $codemirrorUri . 'theme/mdn-like.css' );


        wp_enqueue_script( 'codemirror', $codemirrorUri . 'codemirror.js', [], '1.0.0', true );

        wp_enqueue_script( 'codemirror-css', $codemirrorUri . 'mode/css/css.js', [ 'codemirror' ], '1.0.0', true );
        wp_enqueue_script( 'codemirror-sass', $codemirrorUri . 'mode/sass/sass.js', [ 'codemirror-css' ], '1.0.0', true );

        wp_enqueue_script( 'codemirror-closebrackets', $codemirrorUri . 'addon/edit/closebrackets.js', [ 'codemirror' ], '1.0.0', true );
        wp_enqueue_script( 'codemirror-matchBrackets', $codemirrorUri . 'addon/edit/matchBrackets.js', [ 'codemirror' ], '1.0.0', true );

        wp_enqueue_script( 'codemirror-active-line', $codemirrorUri . 'addon/selection/active-line.js', [ 'codemirror' ], '1.0.0', true );
//        wp_enqueue_script( 'codemirror-mark-selection', $codemirrorUri . 'addon/selection/mark-selection.js', [ 'codemirror' ], '1.0.0', true );
        wp_enqueue_script( 'codemirror-selection-pointer', $codemirrorUri . 'addon/selection/selection-pointer.js', [ 'codemirror' ], '1.0.0', true );

        wp_enqueue_script( 'codemirror-comment', $codemirrorUri . 'addon/comment/comment.js', [ 'codemirror' ], '1.0.0', true );
        wp_enqueue_script( 'codemirror-continuecomment', $codemirrorUri . 'addon/comment/continuecomment.js', [ 'codemirror' ], '1.0.0', true );

        wp_enqueue_style( 'codemirror-show-hint', $codemirrorUri . 'addon/hint/show-hint.css' );
        wp_enqueue_script( 'codemirror-show-hint', $codemirrorUri . 'addon/hint/show-hint.js', [ 'codemirror' ], '1.0.0', true );
        wp_enqueue_script( 'codemirror-css-hint', $codemirrorUri . 'addon/hint/css-hint.js', [ 'codemirror-show-hint' ], '1.0.0', true );

        wp_enqueue_style( 'codemirror-lint', $codemirrorUri . 'addon/lint/lint.css' );
        wp_enqueue_script( 'codemirror-lint', $codemirrorUri . 'addon/lint/lint.js', [ 'codemirror' ], '1.0.0', true );
        wp_enqueue_script( 'codemirror-css-lint', $codemirrorUri . 'addon/lint/css-lint.js', [ 'codemirror-lint' ], '1.0.0', true );
        wp_enqueue_script( 'codemirror-scsslint', $codemirrorUri . 'scsslint.js', [ 'codemirror-css-lint' ], '1.0.0', true );
        wp_enqueue_script( 'codemirror-scss-lint', $codemirrorUri . 'addon/lint/scss-lint.js', [ 'codemirror-scsslint' ], '1.0.0', true );

        wp_enqueue_style( 'codemirror-foldgutter', $codemirrorUri . 'addon/fold/foldgutter.css' );
        wp_enqueue_script( 'codemirror-foldcode', $codemirrorUri . 'addon/fold/foldcode.js', [ 'codemirror' ], '1.0.0', true );
        wp_enqueue_script( 'codemirror-foldgutter', $codemirrorUri . 'addon/fold/foldgutter.js', [ 'codemirror-foldcode' ], '1.0.0', true );
        wp_enqueue_script( 'codemirror-brace-fold', $codemirrorUri . 'addon/fold/brace-fold.js', [ 'codemirror-foldcode' ], '1.0.0', true );
        wp_enqueue_script( 'codemirror-comment-fold', $codemirrorUri . 'addon/fold/comment-fold.js', [ 'codemirror-foldcode' ], '1.0.0', true );

        wp_enqueue_script( 'config-codemirror', $adminJSUri . 'main.js', [ 'codemirror-sass' ], '1.0.0', true );

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

    public function buildCssFileFromPost( $postId, $post, $update ) {

        var_dump( $update );
        var_dump( $this->buildCssFile( $post->post_content ) );
//        die();

    }

    /**
     *
     */
    public function registerPostType() {

        register_post_type( $this->postTypeName, [
            'labels' => [
                'name' => __( 'Global CSS', 'elebee' ),
                'singular_name' => __( 'Global CSS', 'elebee' ),
            ],
            'public' => false,
            'show_in_menu' => false,
            'show_ui' => true,
            'supports' => [
                'title',
                'editor',
                'revisions',
            ],
        ] );

    }

}