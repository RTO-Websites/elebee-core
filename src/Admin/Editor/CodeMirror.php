<?php
/**
 * @since   0.3.0
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 */

namespace ElebeeCore\Admin\Editor;


use ElebeeCore\Lib\Elebee;
use ElebeeCore\Lib\Util\Hooking;

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

    /**
     * CodeMirror constructor.
     * @param bool $disableWysiwyg
     */
    public function __construct( bool $disableWysiwyg = self::WYSIWYG_DISABLE ) {

        $this->disableWysiwyg = $disableWysiwyg;
        parent::__construct();

        $this->url = trailingslashit(  str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__) );
        $this->vendorUrl = $this->url . 'js/vendor/';

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
}
