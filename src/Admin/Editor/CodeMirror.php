<?php
/**
 * @since   0.3.0
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 */

namespace ElebeeCore\Admin\Editor;


use ElebeeCore\Lib\Elebee;
use ElebeeCore\Lib\Util\Hooking;

defined( 'ABSPATH' ) || exit;

/**
 * Class CodeMirror
 * @package ElebeeCore\Admin\Editor
 */
class CodeMirror extends Hooking {

    /**
     *
     */
    const WYSIWYG_ENABLE = false;

    /**
     *
     */
    const WYSIWYG_DISABLE = true;

    /**
     * @var bool
     */
    private $disableWysiwyg;

    /**
     * CodeMirror constructor.
     * @param bool $disableWysiwyg
     */
    public function __construct( bool $disableWysiwyg = self::WYSIWYG_DISABLE ) {

        $this->disableWysiwyg = $disableWysiwyg;
        parent::__construct();

    }

    /**
     *
     */
    public function defineAdminHooks() {

        $this->getLoader()->addAction( 'admin_enqueue_scripts', $this, 'enqueueAdminStyles' );

        if ( $this->disableWysiwyg ) {
            add_filter( 'user_can_richedit', '__return_false' );
        }

    }

    /**
     *
     */
    public function definePublicHooks() {
        // TODO: Implement definePublicHooks() method.
    }

    /**
     *
     */
    public function enqueueAdminStyles() {

        $codemirrorUri = trailingslashit( get_stylesheet_directory_uri() ) . 'vendor/rto-websites/elebee-core/src/Admin/Editor/js/';
        $codemirrorVendorUri = $codemirrorUri . 'vendor/';
        $codemirrorVersion = '3.34.0';

        wp_enqueue_style( 'codemirror', $codemirrorVendorUri . 'codemirror.css', $codemirrorVersion );
        wp_enqueue_style( 'codemirror-mdn-liket', $codemirrorVendorUri . 'theme/mdn-like.css', $codemirrorVersion );

        wp_enqueue_script( 'codemirror', $codemirrorVendorUri . 'codemirror.js', [], $codemirrorVersion, true );

        wp_enqueue_script( 'codemirror-css', $codemirrorVendorUri . 'mode/css/css.js', [ 'codemirror' ], $codemirrorVersion, true );
        wp_enqueue_script( 'codemirror-sass', $codemirrorVendorUri . 'mode/sass/sass.js', [ 'codemirror-css' ], $codemirrorVersion, true );

        wp_enqueue_script( 'codemirror-closebrackets', $codemirrorVendorUri . 'addon/edit/closebrackets.js', [ 'codemirror' ], $codemirrorVersion, true );
        wp_enqueue_script( 'codemirror-matchBrackets', $codemirrorVendorUri . 'addon/edit/matchBrackets.js', [ 'codemirror' ], $codemirrorVersion, true );

        wp_enqueue_script( 'codemirror-active-line', $codemirrorVendorUri . 'addon/selection/active-line.js', [ 'codemirror' ], $codemirrorVersion, true );
//        wp_enqueue_script( 'codemirror-mark-selection', $codemirrorUri . 'addon/selection/mark-selection.js', [ 'codemirror' ], $codemirrorVersion, true );
        wp_enqueue_script( 'codemirror-selection-pointer', $codemirrorVendorUri . 'addon/selection/selection-pointer.js', [ 'codemirror' ], $codemirrorVersion, true );

        wp_enqueue_script( 'codemirror-comment', $codemirrorVendorUri . 'addon/comment/comment.js', [ 'codemirror' ], $codemirrorVersion, true );
        wp_enqueue_script( 'codemirror-continuecomment', $codemirrorVendorUri . 'addon/comment/continuecomment.js', [ 'codemirror' ], $codemirrorVersion, true );

        wp_enqueue_style( 'codemirror-show-hint', $codemirrorVendorUri . 'addon/hint/show-hint.css' );
        wp_enqueue_script( 'codemirror-show-hint', $codemirrorVendorUri . 'addon/hint/show-hint.js', [ 'codemirror' ], $codemirrorVersion, true );
        wp_enqueue_script( 'codemirror-css-hint', $codemirrorVendorUri . 'addon/hint/css-hint.js', [ 'codemirror-show-hint' ], $codemirrorVersion, true );

        wp_enqueue_style( 'codemirror-lint', $codemirrorVendorUri . 'addon/lint/lint.css' );
        wp_enqueue_script( 'codemirror-lint', $codemirrorVendorUri . 'addon/lint/lint.js', [ 'codemirror' ], $codemirrorVersion, true );
        wp_enqueue_script( 'codemirror-css-lint', $codemirrorVendorUri . 'addon/lint/css-lint.js', [ 'codemirror-lint' ], $codemirrorVersion, true );
        wp_enqueue_script( 'codemirror-scsslint', $codemirrorVendorUri . 'scsslint.js', [ 'codemirror-css-lint' ], $codemirrorVersion, true );
        wp_enqueue_script( 'codemirror-scss-lint', $codemirrorVendorUri . 'addon/lint/scss-lint.js', [ 'codemirror-scsslint' ], $codemirrorVersion, true );

        wp_enqueue_style( 'codemirror-foldgutter', $codemirrorVendorUri . 'addon/fold/foldgutter.css' );
        wp_enqueue_script( 'codemirror-foldcode', $codemirrorVendorUri . 'addon/fold/foldcode.js', [ 'codemirror' ], $codemirrorVersion, true );
        wp_enqueue_script( 'codemirror-foldgutter', $codemirrorVendorUri . 'addon/fold/foldgutter.js', [ 'codemirror-foldcode' ], $codemirrorVersion, true );
        wp_enqueue_script( 'codemirror-brace-fold', $codemirrorVendorUri . 'addon/fold/brace-fold.js', [ 'codemirror-foldcode' ], $codemirrorVersion, true );
        wp_enqueue_script( 'codemirror-comment-fold', $codemirrorVendorUri . 'addon/fold/comment-fold.js', [ 'codemirror-foldcode' ], $codemirrorVersion, true );

        $deps = [
            'codemirror-sass',
            'codemirror-closebrackets',
            'codemirror-matchBrackets',
            'codemirror-active-line',
            'codemirror-selection-pointer',
            'codemirror-comment',
            'codemirror-continuecomment',
            'codemirror-css-hint',
            'codemirror-scss-lint',
            'codemirror-foldgutter',
            'codemirror-brace-fold',
            'codemirror-comment-fold',
        ];
        wp_enqueue_script( 'config-codemirror', $codemirrorUri . 'main.js', $deps, Elebee::VERSION, true );

    }

}