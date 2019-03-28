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

        $this->url = trailingslashit(  str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__) );
        $this->vendorUrl = $this->url . 'js/vendor/';

        $this->addMetaBox();
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
        // No public hooks required
    }

    /**
     *
     */
    public function enqueueAdminStyles() {

        # enqueue core files
        wp_enqueue_style( 'codemirror', $this->vendorUrl . 'codemirror.css', [], $this->version );
        #theme
        wp_enqueue_style( 'mdn-like', $this->vendorUrl . 'theme/mdn-like.css', [ 'codemirror' ], $this->version);

        wp_enqueue_script( 'codemirror', $this->vendorUrl . 'codemirror.js', [], $this->version, true );
        wp_enqueue_script( 'codemirror-scsslint', $this->vendorUrl . 'scsslint.js', [ 'addon-lint-css-lint' ], $this->version, true );

        # enqueue addon, mode and theme. Always insert in footer.
        # 'name', 'file path', ['dependency1', ... ]

        # addon
        $this->addFile( 'addon-comment-comment', 'addon/comment/comment.js', [ 'codemirror' ] );

        $this->addFile( 'addon-dialog-dialog', 'addon/dialog/dialog.css' );
        $this->addFile( 'addon-dialog-dialog', 'addon/dialog/dialog.js', [ 'codemirror' ] );

        $this->addFile( 'addon-edit-closebrackets', 'addon/edit/closebrackets.js', [ 'codemirror' ] );
        $this->addFile( 'addon-edit-matchbrackets', 'addon/edit/matchbrackets.js', [ 'codemirror' ] );
        $this->addFile( 'addon-edit-trailingspace', 'addon/edit/trailingspace.js', [ 'codemirror' ] );

        $this->addFile( 'addon-fold-foldgutter', 'addon/fold/foldgutter.css');
        $this->addFile( 'addon-fold-foldcode', 'addon/fold/foldcode.js', [ 'codemirror' ] );
        $this->addFile( 'addon-fold-brace-fold', 'addon/fold/brace-fold.js', [ 'addon-fold-foldcode' ] );
        $this->addFile( 'addon-fold-comment-fold', 'addon/fold/comment-fold.js', [ 'addon-fold-foldcode' ] );
        $this->addFile( 'addon-fold-foldgutter', 'addon/fold/foldgutter.js', [ 'addon-fold-foldcode' ] );

        $this->addFile( 'addon-hint-show-hint', 'addon/hint/show-hint.css' );
        $this->addFile( 'addon-hint-css-hint', 'addon/hint/css-hint.js', [ 'codemirror' ] );
        $this->addFile( 'addon-hint-show-hint', 'addon/hint/show-hint.js', [ 'codemirror' ] );

        $this->addFile( 'addon-lint-lint', 'addon/lint/lint.css' );
        $this->addFile( 'addon-lint-lint', 'addon/lint/lint.js', [ 'codemirror' ] );
        $this->addFile( 'addon-lint-css-lint', 'addon/lint/css-lint.js', [ 'addon-lint-lint' ] );
        $this->addFile( 'addon-lint-scss-lint', 'addon/lint/scss-lint.js', [ 'addon-lint-lint' ] );

        $this->addFile( 'addon-scroll-annotatescrollbar', 'addon/scroll/annotatescrollbar.js', [ 'codemirror' ] );

        $this->addFile( 'addon-search-matchesonscrollbar', 'addon/search/matchesonscrollbar.css' );
        $this->addFile( 'addon-search-jump-to-line', 'addon/search/jump-to-line.js', [ 'codemirror' ] );
        $this->addFile( 'addon-search-match-highlighter','addon/search/match-highlighter.js', [ 'codemirror' ] );
        $this->addFile( 'addon-search-matchesonscrollbar', 'addon/search/matchesonscrollbar.js', [ 'codemirror', 'addon-scroll-annotatescrollbar' ] );
        $this->addFile( 'addon-search-search', 'addon/search/search.js', [ 'codemirror' ] );
        $this->addFile( 'addon-search-searchcursor', 'addon/search/searchcursor.js', [ 'codemirror' ] );

        $this->addFile( 'addon-selection-active-line', 'addon/selection/active-line.js', [ 'codemirror' ] );
        $this->addFile( 'addon-selection-mark-selection','addon/selection/mark-selection.js', [ 'codemirror' ] );

        #mode
        $this->addFile( 'mode-css-css', 'mode/css/css.js', [ 'codemirror' ] );

        $this->addFile( 'mode-sass-sass', 'mode/sass/sass.js', [ 'mode-css-css' ]);

        $deps = $this->enqueueFiles();

        wp_enqueue_script( 'config-codemirror', $this->url . 'js/main.js', $deps, Elebee::VERSION, true );

    }

    public function enqueueFiles() {

        $enqueuedFiles = [];

        foreach ( $this->enqueueFiles as $key => $fileMeta ) {
            if ( $this->getFileType( $fileMeta[ 'path' ] ) === 'css' ) {
                wp_enqueue_style( $fileMeta[ 'name' ] . '-css', $this->vendorUrl . $fileMeta[ 'path' ], $fileMeta[ 'deps' ], $this->version );
            }
            else if ( $this->getFileType( $fileMeta[ 'path' ] ) === 'js' ) {
                wp_enqueue_script( $fileMeta[ 'name' ], $this->vendorUrl . $fileMeta[ 'path' ], $fileMeta[ 'deps' ], $this->version, true );

                $enqueuedFiles[] = $fileMeta[ 'name' ];
            }
        }

        return array_values( array_unique( $enqueuedFiles ) );
    }

    private function getFileType( string $file ) {

        if( !is_string( $file ) ) {
            return '';
        }

        $fileParts = explode( '.', $file );
        $extension = end( $fileParts );

        return in_array( $extension, $this->allowedExtensions ) ? $extension : '';

    }

    private function addFile( string $name, string $path, array $dependencies = [] ) {

        $this->enqueueFiles[] = [
            'name' => $name,
            'path' => $path,
            'deps' => $dependencies
        ];
    }

    private function addMetaBox() {
        global $post;

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
                'callback' => 'autoComplete',
                'shortcut' => $this->renderDescription( 'Ctrl-Space', 'Cmd-Space' ),
                'cssClass' => 'custom-css'
            ],
            'block-comment' => [
                'label' => __( 'Block comment', 'elebee' ),
                'callback' => 'blockComment',
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
                'callback' => 'undoStep',
                'shortcut' => $this->renderDescription( 'Ctrl-Z', 'Cmd-Z' ),
                'cssClass' => 'custom-css'
            ],
            'redo' => [
                'label' => __( 'Redo', 'elebee' ),
                'callback' => 'redoStep',
                'shortcut' => $this->renderDescription( 'Ctrl-Y, Shift-Ctrl-Z', 'Cmd-Y, Shift-Cmd-Z' ),
                'cssClass' => 'custom-css'
            ],
            # search, replace
            'find' => [
                'label' => __( 'Find', 'elebee' ),
                'callback' => 'findChars',
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
                'callback' => 'replaceChars',
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
            $metaButton->setConfiguration( $button[ 'callback' ], $button[ 'shortcut' ], $button[ 'cssClass' ] );
            $metaBox->addMetaKey( $metaButton );
        }

        $metaBox->actionAddMetaBox( $post );
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
