<?php
/**
 * @since   0.3.0
 *
 * @package ElebeeCore\Lib\CustomCss
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/CustomCss/CustomCss.html
 */

namespace ElebeeCore\Lib\CustomPostType\CustomCss;


use ElebeeCore\Admin\Editor\CodeMirror;
use ElebeeCore\Lib\CustomPostType\CustomPostTypeBase;
use ElebeeCore\Lib\Elebee;
use Leafo\ScssPhp\Compiler;
use Leafo\ScssPhp\Formatter\Crunched;

defined( 'ABSPATH' ) || exit;

/**
 * Class CustomCss
 *
 * @since   0.3.0
 *
 * @package ElebeeCore\Lib\CustomCss
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/CustomCss/CustomCss.html
 */
class CustomCss extends CustomPostTypeBase {

    /**
     * The absolute physical path to the compiled CSS file.
     *
     * @since 0.3.0
     * @var string
     */
    private $compiledFilePath;

    /**
     * The absolute Url to the compiled CSS file.
     *
     * @since 0.3.0
     * @var string
     */
    private $compiledFileUrl;

    /**
     * The absolute Url to the JS library directory.
     * @since 0.3.0
     * @var string
     */
    private $jsLibUrl;

    /**
     * CustomCss constructor.
     *
     * @since 0.3.0
     */
    public function __construct() {

        $file = 'css/custom-global.css';

        $stylesheetDirectoryUri = trailingslashit( get_stylesheet_directory_uri() );
        $this->compiledFilePath = trailingslashit( get_stylesheet_directory() ) . $file;
        $this->compiledFileUrl = $stylesheetDirectoryUri . $file;
        $this->jsLibUrl = $stylesheetDirectoryUri . 'vendor/rto-websites/elebee-core/src/Lib/CustomPostType/CustomCss/js/';

        $editable = current_user_can( 'publish_pages' );

        $args = [
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
        ];

        parent::__construct( 'elebee-global-css', $args );

    }

    /**
     * @since 0.3.0
     */
    public function defineAdminHooks() {

        parent::defineAdminHooks();

        $this->getLoader()->addAction( 'current_screen', $this, 'initCodeMirror' );
        $this->getLoader()->addAction( 'wp_ajax_autoUpdate', $this, 'autoUpdate' );
        $this->getLoader()->addAction( 'admin_enqueue_scripts', $this, 'enqueueAdminStyles' );
        $this->getLoader()->addAction( 'admin_enqueue_scripts', $this, 'enqueueAdminScripts' );
        $this->getLoader()->addAction( 'transition_post_status', $this, 'save', 10, 3 );
        $this->getLoader()->addAction( 'elementor/editor/before_enqueue_scripts', $this, 'enqueueEditorScripts' );

        $this->getLoader()->addFilter( 'admin_body_class', $this, 'collapseAdminMenu' );

    }

    /**
     * @since 0.3.0
     */
    public function definePublicHooks() {

        parent::definePublicHooks();

        $this->getLoader()->addAction( 'elementor/frontend/after_register_scripts', $this, 'enqueuePublicStyles' );

    }

    /**
     * @since 0.3.0
     *
     * @return void
     */
    public function enqueueAdminStyles() {

        $stylesUrl = trailingslashit( get_stylesheet_directory_uri() ) . 'vendor/rto-websites/elebee-core/src/Admin/Editor/css/';
        wp_enqueue_style( 'elebee-editor', $stylesUrl . 'editor.css', [ 'codemirror' ], Elebee::VERSION );

    }

    /**
     * @since 0.3.0
     *
     * @return void
     */
    public function enqueueAdminScripts() {

        global $post;

        if ( !$post || $post->post_type != $this->getName() ) {
            return;
        }

        $src = $this->jsLibUrl . 'capture-input.js';
        wp_enqueue_script( 'elebee-capture-input', $src, [ 'config-codemirror' ], Elebee::VERSION, true );
        wp_localize_script( 'elebee-capture-input', 'postData', [ 'id' => get_the_ID() ] );

    }

    /**
     * @since 0.3.0
     *
     * @return void
     */
    public function enqueueEditorScripts() {

        $src = $this->jsLibUrl . 'inject-css.js';
        wp_enqueue_script( 'elebee-inject-css', $src, [ 'jquery' ], Elebee::VERSION );

    }

    /**
     * @since 0.3.0
     *
     * @return void
     */
    public function enqueuePublicStyles() {

        wp_enqueue_style( 'elebee-global', $this->compiledFileUrl, [ 'main', 'elementor-frontend' ], Elebee::VERSION );

    }

    /**
     * @since 0.3.0
     *
     * @return void
     */
    public function initCodeMirror() {

        $screen = get_current_screen();

        if ( !is_object( $screen ) || $this->getName() != $screen->post_type ) {

            return;

        }

        $codeMirror = new CodeMirror( CodeMirror::WYSIWYG_DISABLE );
        $codeMirror->getLoader()->run();

    }

    /**
     * @since 0.3.0
     *
     * @return void
     */
    public function autoUpdate() {

        $postId = filter_input( INPUT_POST, 'postId' );
        $scss = filter_input( INPUT_POST, 'scss' );

        try {

            $validation = $this->compile( $scss );
            wp_send_json_success( $this->buildCss( $postId, $scss ) );

        } catch ( \Exception $e ) {

            wp_send_json_error( $e->getMessage() );

        }

        die();

    }

    /**
     * @since 0.3.0
     *
     * @param string   $newStatus
     * @param string   $oldStatus
     * @param \WP_Post $post
     * @return void
     */
    public function save( string $newStatus, string $oldStatus, \WP_Post $post ) {

        if ( $post->post_type != $this->getName() ) {
            return;
        }

        if ( $newStatus == 'publish' || $oldStatus == 'publish' && $newStatus != $oldStatus ) {

            try {

                $validation = $this->compile( $post->post_content );

                file_put_contents( $this->compiledFilePath, $this->buildCss() );

            } catch ( \Exception $e ) {

                wp_die( $e->getMessage() );

            }

        }

    }

    /**
     * @since 0.3.0
     *
     * @param null   $postId
     * @param string $postScss
     * @return string
     * @throws \Exception
     */
    public function buildCss( $postId = null, $postScss = '' ): string {

        $scss = '';

        $query = new \WP_Query( [
            'post_type' => $this->getName(),
            'post_status' => 'publish',
            'posts_per_page' => -1,
        ] );

        while ( $query->have_posts() ) {
            $query->the_post();

            if ( get_the_ID() != $postId ) {
                $scss .= get_the_content();
            } else {
                $scss .= $postScss;
                $postId = null;
            }

        }

        wp_reset_query();

        if ( $postId !== null ) {
            $scss .= $postScss;
        }

        return $this->compile( $scss );

    }

    /**
     * @since 0.3.0
     *
     * @param string $scss
     * @return string
     * @throws \Exception
     */
    public function compile( string $scss ): string {

        $scssCompiler = new Compiler();
        $scssCompiler->setFormatter( Crunched::class );

        if ( WP_DEBUG ) {
            $scssCompiler->setLineNumberStyle( Compiler::LINE_COMMENTS );
        }

        return $scssCompiler->compile( $scss );

    }

    /**
     * @since 0.3.0
     *
     * @param string $classes
     * @return string
     */
    public function collapseAdminMenu( string $classes ): string {

        global $post;

        if ( $post && $post->post_type == $this->getName() ) {
            $classes .= ' folded';
        }

        return $classes;

    }

    /**
     * @since 0.3.0
     */
    public function addPostUpdatedMessages( array $messages ): array {

        $messages[$this->getName()] = [
            0 => '', // Unused. Messages start at index 1.
            1 => __( 'Partial updated.', 'elebee' ),
            2 => __( 'Custom field updated.', 'elebee' ),
            3 => __( 'Custom field deleted.', 'elebee' ),
            4 => __( 'Partial updated.', 'elebee' ),
            /* translators: %s: date and time of the revision */
            5 => isset( $_GET['revision'] ) ? sprintf( __( 'Partial restored to revision from %s', 'elebee' ), wp_post_revision_title( (int)$_GET['revision'], false ) ) : false,
            6 => __( 'Partial published.', 'elebee' ),
            7 => __( 'Partial saved.', 'elebee' ),
            8 => __( 'Partial submitted.', 'elebee' ),
            9 => sprintf(
                __( 'Partial scheduled for: <strong>%1$s</strong>.', 'elebee' ),
                // translators: Publish box date format, see http://php.net/date
                date_i18n( __( 'M j, Y @ G:i', 'elebee' ), strtotime( get_post()->post_date ) )
            ),
            10 => __( 'Partial draft updated.', 'elebee' ),
        ];
        return $messages;

    }

    /**
     * @since 0.3.0
     */
    public function addBulkPostUpdatedMessages( array $bulkMessages, array $bulkCounts ): array {

        $bulkMessages[$this->getName()] = [
            'updated' => _n( '%s partial updated.', '%s partials updated.', $bulkCounts['updated'], 'elebee' ),
            'locked' => _n( '%s partial not updated, somebody is editing it.', '%s partials not updated, somebody is editing them.', $bulkCounts['locked'], 'elebee' ),
            'deleted' => _n( '%s partial permanently deleted.', '%s partials permanently deleted.', $bulkCounts['deleted'], 'elebee' ),
            'trashed' => _n( '%s partial moved to the Trash.', '%s partials moved to the Trash.', $bulkCounts['trashed'], 'elebee' ),
            'untrashed' => _n( '%s partial restored from the Trash.', '%s partials restored from the Trash.', $bulkCounts['untrashed'], 'elebee' ),
        ];

        return $bulkMessages;

    }

}