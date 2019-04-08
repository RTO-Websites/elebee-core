<?php
/**
 * @since   0.3.0
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 */

namespace ElebeeCore\Admin\Editor;


use ElebeeCore\Lib\Elebee;
use ElebeeCore\Lib\MetaBox\MetaKeyButton;
use ElebeeCore\Lib\Util\Hooking;
use ElebeeCore\Lib\MetaBox\MetaBox;

\defined( 'ABSPATH' ) || exit;

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

    private $url = '';

    private $vendorUrl ='';

    private $version = '5.45.1';

    private $allowedExtensions = [ 'css', 'js' ];

    private $enqueueFiles = [];

    private $icons = [];

    /**
     * CodeMirror constructor.
     * @param bool $disableWysiwyg
     */
    public function __construct( bool $disableWysiwyg = self::WYSIWYG_DISABLE ) {

        $this->disableWysiwyg = $disableWysiwyg;
        parent::__construct();

        $this->setIcons();

        $this->url = trailingslashit( get_stylesheet_directory_uri() ) . 'vendor/rto-websites/elebee-core/src/Admin/Editor/';
        $this->vendorUrl = $this->url . 'js/vendor/';

        $this->initMetaBox();
    }

    /**
     *
     */
    public function defineAdminHooks() {

        $this->getLoader()->addAction( 'admin_enqueue_scripts', $this, 'enqueueAdminScripts' );

        if ( $this->disableWysiwyg ) {
            add_filter( 'user_can_richedit', '__return_false' );
        }

    }

    /**
     *
     */
    public function definePublicHooks() {
        // No public hooks required
    }

    /**
     *
     */
    public function enqueueAdminScripts() {

        # enqueue core files
        wp_enqueue_style( 'codemirror', $this->vendorUrl . 'codemirror.css', [], $this->version );
        wp_enqueue_script( 'codemirror', $this->vendorUrl . 'codemirror.js', [], $this->version, true );
        wp_enqueue_script( 'codemirror-scsslint', $this->vendorUrl . 'scsslint.js', [ 'addon-lint-css-lint' ], $this->version, true );

        # addons
        wp_enqueue_script( 'addon-comment-comment', $this->vendorUrl . 'addon/comment/comment.js', [ 'codemirror' ], $this->version, true );

        wp_enqueue_style( 'addon-dialog-dialog-css', $this->vendorUrl . 'addon/dialog/dialog.css', [], $this->version );
        wp_enqueue_script( 'addon-dialog-dialog', $this->vendorUrl . 'addon/dialog/dialog.js', [ 'codemirror' ], $this->version, true );

        wp_enqueue_script( 'addon-edit-closebrackets', $this->vendorUrl . 'addon/edit/closebrackets.js', [ 'codemirror' ], $this->version, true );
        wp_enqueue_script( 'addon-edit-matchbrackets', $this->vendorUrl . 'addon/edit/matchbrackets.js', [ 'codemirror' ], $this->version, true );
        wp_enqueue_script( 'addon-edit-trailingspace', $this->vendorUrl . 'addon/edit/trailingspace.js', [ 'codemirror' ], $this->version, true );

        wp_enqueue_style( 'addon-fold-foldgutter-css', $this->vendorUrl . 'addon/fold/foldgutter.css', [], $this->version );
        wp_enqueue_script( 'addon-fold-foldcode', $this->vendorUrl . 'addon/fold/foldcode.js', [ 'codemirror' ], $this->version, true );
        wp_enqueue_script( 'addon-fold-brace-fold', $this->vendorUrl . 'addon/fold/brace-fold.js', [ 'addon-fold-foldcode' ], $this->version, true );
        wp_enqueue_script( 'addon-fold-comment-fold', $this->vendorUrl . 'addon/fold/comment-fold.js', [ 'addon-fold-foldcode' ], $this->version, true );
        wp_enqueue_script( 'addon-fold-foldgutter', $this->vendorUrl . 'addon/fold/foldgutter.js', [ 'addon-fold-foldcode' ], $this->version, true );

        wp_enqueue_style( 'addon-hint-show-hint-css', $this->vendorUrl . 'addon/hint/show-hint.css', [], $this->version );
        wp_enqueue_script( 'addon-hint-css-hint', $this->vendorUrl . 'addon/hint/css-hint.js', [ 'codemirror' ], $this->version, true );
        wp_enqueue_script( 'addon-hint-show-hint', $this->vendorUrl . 'addon/hint/show-hint.js', [ 'codemirror' ], $this->version, true );

        wp_enqueue_style( 'addon-lint-lint-css', $this->vendorUrl . 'addon/lint/lint.css', [], $this->version );
        wp_enqueue_script( 'addon-lint-lint', $this->vendorUrl . 'addon/lint/lint.js', [ 'codemirror' ] , $this->version, true );
        wp_enqueue_script( 'addon-lint-css-lint', $this->vendorUrl . 'addon/lint/css-lint.js', [ 'addon-lint-lint' ], $this->version, true );
        wp_enqueue_script( 'addon-lint-scss-lint', $this->vendorUrl . 'addon/lint/scss-lint.js', [ 'addon-lint-lint' ], $this->version, true );

        wp_enqueue_script( 'addon-scroll-annotatescrollbar', $this->vendorUrl . 'addon/scroll/annotatescrollbar.js', [ 'codemirror' ], $this->version, true );

        wp_enqueue_style( 'addon-search-matchesonscrollbar-css', $this->vendorUrl . 'addon/search/matchesonscrollbar.css', [], $this->version );
        wp_enqueue_script( 'addon-search-jump-to-line', $this->vendorUrl . 'addon/search/jump-to-line.js', [ 'codemirror' ], $this->version, true );
        wp_enqueue_script( 'addon-search-match-highlighter', $this->vendorUrl . 'addon/search/match-highlighter.js', [ 'codemirror' ], $this->version, true );
        wp_enqueue_script( 'addon-search-matchesonscrollbar', $this->vendorUrl . 'addon/search/matchesonscrollbar.js', [ 'codemirror', 'addon-scroll-annotatescrollbar' ], $this->version, true );
        wp_enqueue_script( 'addon-search-search', $this->vendorUrl . 'addon/search/search.js', [ 'codemirror' ], $this->version, true );
        wp_enqueue_script( 'addon-search-searchcursor', $this->vendorUrl . 'addon/search/searchcursor.js', [ 'codemirror' ], $this->version, true );

        wp_enqueue_script( 'addon-selection-active-line', $this->vendorUrl . 'addon/selection/active-line.js', [ 'codemirror' ], $this->version, true );
        wp_enqueue_script( 'addon-selection-mark-selection', $this->vendorUrl . 'addon/selection/mark-selection.js', [ 'codemirror' ], $this->version, true );

        #mode
        wp_enqueue_style( 'mode-sass-sass-css', $this->vendorUrl . 'mode/sass/sass.js', [ 'mode-css-css' ], $this->version );
        wp_enqueue_script( 'mode-css-css', $this->vendorUrl . 'mode/css/css.js', [ 'codemirror' ], $this->version, true );

        #theme
        wp_enqueue_style( 'mdn-like', $this->vendorUrl . 'theme/mdn-like.css', [ 'codemirror' ], $this->version);

        $deps = [
            'codemirror',
            'addon-comment-comment',
            'addon-dialog-dialog',
            'addon-edit-closebrackets',
            'addon-edit-matchbrackets',
            'addon-edit-trailingspace',
            'addon-fold-brace-fold',
            'addon-fold-comment-fold',
            'addon-fold-foldgutter',
            'addon-hint-css-hint',
            'addon-hint-show-hint',
            'addon-lint-css-lint',
            'addon-lint-scss-lint',
            'addon-scroll-annotatescrollbar',
            'addon-search-jump-to-line',
            'addon-search-match-highlighter',
            'addon-search-matchesonscrollbar',
            'addon-search-search',
            'addon-search-searchcursor',
            'addon-selection-active-line',
            'addon-selection-mark-selection',
            'mode-css-css',
        ];

        wp_enqueue_script( 'config-codemirror', $this->url . 'js/main.js', $deps, Elebee::VERSION, true );
    }

    private function initMetaBox() {
        # ToDo: translate shortcut labels

        $customCssButtons = [
            # edit
            'auto-indent' => [
                'label' => __( 'Auto indent', 'elebee'),
                'callback' => 'autoIndent',
                'shortcut' => $this->renderDescription( 'Ctrl-Alt-L', 'Cmd-Alt-L' ),
                'cssClass' => 'custom-css'
            ],
            'hint' => [
                'label' => __( 'Hint', 'elebee' ),
                'callback' => 'hint',
                'shortcut' => $this->renderDescription( 'Ctrl-Space', 'Cmd-Space' ),
                'cssClass' => 'custom-css'
            ],
            'block-comment' => [
                'label' => __( 'Block comment', 'elebee' ),
                'callback' => 'commentBlock',
                'shortcut' => $this->renderDescription( 'Ctrl-/', 'Cmd-/' ),
                'cssClass' => 'custom-css'
            ],
            'uncomment' => [
                'label' => __( 'Uncomment', 'elebee' ),
                'callback' => 'uncommentBlock',
                'shortcut' => $this->renderDescription( 'Ctrl-Alt-/', 'Cmd-Alt/' ),
                'cssClass' => 'custom-css'
            ],
            'delete-line' => [
                'label' => __( 'Delete line', 'elebee' ),
                'callback' => 'deleteLine',
                'shortcut' => $this->renderDescription( 'Ctrl-D', 'Cmd-D' ),
                'cssClass' => 'custom-css'
            ],
            'undo' => [
                'label' => __( 'Undo', 'elebee' ),
                'callback' => 'undo',
                'shortcut' => $this->renderDescription( 'Ctrl-Z', 'Cmd-Z' ),
                'cssClass' => 'custom-css'
            ],
            'redo' => [
                'label' => __( 'Redo', 'elebee' ),
                'callback' => 'redo',
                'shortcut' => $this->renderDescription( 'Ctrl-Y, Shift-Ctrl-Z', 'Cmd-Y, Shift-Cmd-Z' ),
                'cssClass' => 'custom-css'
            ],
            # search, replace
            'find' => [
                'label' => __( 'Find', 'elebee' ),
                'callback' => 'find',
                'shortcut' => $this->renderDescription( 'Ctrl-F', 'Cmd-F' ),
                'cssClass' => 'custom-css'
            ],
            'find-next' => [
                'label' => __( 'Find next', 'elebee' ),
                'callback' => 'findNext',
                'shortcut' => $this->renderDescription( 'Ctrl-G', 'Cmd-G' ),
                'cssClass' => 'custom-css'
            ],
            'find-prev' => [
                'label' => __( 'Find previous', 'elebee' ),
                'callback' => 'findPrev',
                'shortcut' => $this->renderDescription( 'Shift-Ctrl-G', 'Shift-Cmd-G' ),
                'cssClass' => 'custom-css'
            ],
            'replace' => [
                'label' => __( 'Replace', 'elebee' ),
                'callback' => 'replace',
                'shortcut' => $this->renderDescription( 'Shift-Ctrl-F', 'Cmd-Alt-F' ),
                'cssClass' => 'custom-css'
            ],
            'replace-all' => [
                'label' => __( 'Replace all', 'elebee' ),
                'callback' => 'replaceAll',
                'shortcut' => $this->renderDescription( 'Shift-Ctrl-R', 'Shift-Cmd-Alt-F' ),
                'cssClass' => 'custom-css'
            ],
        ];

        $metaBox = new MetaBox( 'custom-css-meta-box', __( 'Shortcuts', 'elebee' ), 'side' );
        $metaBox->addPostTypeSupport( 'elebee-global-css' );

        foreach ( $customCssButtons as $key => $button ) {
            $metaButton = new MetaKeyButton( $key, $button[ 'label' ] );
            $metaButton->getTemplate()->setVar( 'key', $key );
            $metaButton->getTemplate()->setVar( 'button', $button );
            $metaBox->addMetaKey( $metaButton );
        }

        $metaBox->register();
    }

    private function setIcons() {

        $this->icons = [
            'apple' => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjwhRE9DVFlQRSBzdmcgIFBVQkxJQyAnLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4nICAnaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkJz48c3ZnIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDU2LjY5MyA1Ni42OTMiIGhlaWdodD0iNTYuNjkzcHgiIGlkPSJMYXllcl8xIiB2ZXJzaW9uPSIxLjEiIHZpZXdCb3g9IjAgMCA1Ni42OTMgNTYuNjkzIiB3aWR0aD0iNTYuNjkzcHgiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPjxnPjxwYXRoIGQ9Ik00MS43NzcsMzAuNTE3Yy0wLjA2Mi02LjIzMiw1LjA4Mi05LjIyMSw1LjMxMi05LjM3MmMtMi44OTEtNC4yMjctNy4zOTUtNC44MDctOC45OTgtNC44NzMgICBjLTMuODMtMC4zODktNy40NzcsMi4yNTYtOS40MiwyLjI1NmMtMS45MzksMC00Ljk0MS0yLjE5OS04LjExNy0yLjE0M2MtNC4xNzgsMC4wNjItOC4wMjksMi40My0xMC4xNzksNi4xNyAgIGMtNC4zMzksNy41MjctMS4xMSwxOC42ODIsMy4xMTgsMjQuNzkxYzIuMDY3LDIuOTg2LDQuNTMyLDYuMzQ2LDcuNzY2LDYuMjIzYzMuMTE3LTAuMTIzLDQuMjkzLTIuMDE2LDguMDYxLTIuMDE2ICAgczQuODI2LDIuMDE2LDguMTIzLDEuOTUzYzMuMzUyLTAuMDYxLDUuNDc3LTMuMDQzLDcuNTI3LTYuMDQxYzIuMzczLTMuNDY5LDMuMzUtNi44MjgsMy40MDgtNi45OTggICBDNDguMzA1LDQwLjQzMyw0MS44NDQsMzcuOTU4LDQxLjc3NywzMC41MTd6Ii8+PHBhdGggZD0iTTM1LjU4MiwxMi4yMjljMS43MTUtMi4wODIsMi44NzctNC45NzUsMi41NjEtNy44NTVjLTIuNDc1LDAuMS01LjQ3MSwxLjY0NS03LjI0OCwzLjcyNSAgIGMtMS41OTIsMS44NDYtMi45ODQsNC43ODUtMi42MTEsNy42MTNDMzEuMDQ1LDE1LjkyNiwzMy44NjEsMTQuMzA3LDM1LjU4MiwxMi4yMjl6Ii8+PC9nPjwvc3ZnPg==',
            'windows' => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjwhRE9DVFlQRSBzdmcgIFBVQkxJQyAnLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4nICAnaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkJz48c3ZnIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDI0IDI0IiBoZWlnaHQ9IjI0cHgiIGlkPSJMYXllcl8xIiB2ZXJzaW9uPSIxLjEiIHZpZXdCb3g9IjAgMCAyNCAyNCIgd2lkdGg9IjI0cHgiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPjxnPjxwb2x5Z29uIHBvaW50cz0iMS4zLDE5LjUgOS4zLDIwLjcgOS4zLDEzLjEgMS4zLDEzLjEgICIvPjxwb2x5Z29uIHBvaW50cz0iMTEuNiwyMSAyMi43LDIyLjYgMjIuNywxMy4xIDExLjYsMTMuMSAgIi8+PHBvbHlnb24gcG9pbnRzPSIxMS42LDMgMTEuNiwxMC45IDIyLjcsMTAuOSAyMi43LDEuNCAgIi8+PHBvbHlnb24gcG9pbnRzPSIxLjMsMTAuOSA5LjMsMTAuOSA5LjMsMy4zIDEuMyw0LjUgICIvPjwvZz48L3N2Zz4=',
        ];

    }

    private function getIcon( $name, $as = 'img' ) {
        $output = '';

        if ( isset( $this->icons[ $name ] ) ) {
            $output = $this->icons[ $name ];

            if ( $as === 'img' ) {
                $output = '<img src="' . $output . '" class="custom-css-' . $name . '" />';
            }
        }

        return $output;
    }

    private function renderDescription( $scWindows = '', $scApple = '' ) {
        $description = [];

        if ( !empty( $scWindows ) ) {
            $description[] = $this->getIcon( 'windows' ) . $scWindows;
        }

        if ( !empty( $scApple ) ) {
            $description[] = $this->getIcon( 'apple' ) . $scApple;
        }

        return join( '<br />' , $description );

    }
}
