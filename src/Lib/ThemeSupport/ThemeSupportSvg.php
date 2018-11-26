<?php
/**
 * ThemeSupportSvg.php
 *
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 */

namespace ElebeeCore\Lib\ThemeSupport;


class ThemeSupportSvg extends ThemeSupportBase {

    /**
     * ThemeSupportSvg constructor.
     * @param string $hook
     */
    public function __construct( string $hook = 'admin_init' ) {

        parent::__construct( $hook );

        $this->getLoader()->addFilter( 'upload_mimes', $this, 'addSvgMediaSupport' );

    }

    /**
     *
     */
    public function addThemeSupport() {

        $this->displaySvgThumbs();

    }


    /**
     * Add SVG support to mimes.
     *
     * @param $mimes
     *
     * @return mixed
     */
    public function addSvgMediaSupport( $mimes ) {

        $mimes['svg'] = 'image/svg+xml';
        $mimes['svgz'] = 'image/svg+xml';
        return $mimes;

    }

    /**
     * Show SVG thumbnails.
     *
     * @return void
     */
    public function displaySvgThumbs() {

        ob_start();

        $this->getLoader()->addAction( 'shutdown', $this, 'svgThumbsFilter', 0 );

    }

    /**
     * Filter SVG thumbnails.
     *
     * @return void
     */
    public function svgThumbsFilter() {

        $final = '';
        $obLevels = count( ob_get_level() );

        for ( $i = 0; $i < $obLevels; $i++ ) {

            $final .= ob_get_clean();

        }

        echo $this->svgFinalOutput( $final );

    }

    /**
     * Get the final SVG output.
     *
     * @param $content
     *
     * @return mixed
     */
    public function svgFinalOutput( $content ) {

        $content = str_replace(
            [
                '<# } else if ( \'image\' === data.type && data.sizes && data.sizes.full ) { #>',
                '<# } else if ( \'image\' === data.type && data.sizes ) { #>',
            ],
            [
                '<# } else if ( \'svg+xml\' === data.subtype ) { #>
				<img class="details-image" src="{{ data.url }}" draggable="false" />
				<# } else if ( \'image\' === data.type && data.sizes && data.sizes.full ) { #>',
                '<# } else if ( \'svg+xml\' === data.subtype ) { #>
				<div class="centered">
					<img src="{{ data.url }}" class="thumbnail" draggable="false" />
				</div>
			    <# } else if ( \'image\' === data.type && data.sizes ) { #>',
            ],
            $content
        );

        return $content;

    }

}