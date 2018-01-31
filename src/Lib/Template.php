<?php namespace ElebeeCore\Lib;

/**
 * @since 0.1.0
 * @author RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 */
class Template {

    /**
     * The variables passed to the template file.
     *
     * @var array
     *
     * @since 0.1.0
     */
    protected $vars;

    /**
     * Filename of the view.
     *
     * @var string
     *
     * @since 0.1.0
     */
    protected $file;

    /**
     * Template constructor.
     *
     * @param string $file
     * @param array $vars (optional)
     *
     * @since 0.1.0
     */
    public function __construct( $file, $vars = [] ) {

        $this->file = $file;
        $this->vars = $vars;

    }

    /**
     * Set a variable.
     *
     * @param $var
     * @param $value
     *
     * @return void
     */
    public function setVar( $var, $value ) {

        $this->vars[$var] = $value;

    }

    /**
     * Get the rendered template.
     *
     * @return string
     *
     * @since 0.1.0
     */
    public function getRendered() {

        return getContents( $this->file, $this->vars );

    }

    /**
     * Renders the template.
     *
     * @return void
     *
     * @since 1.7.0
     */
    public function render() {

        echo $this->getRendered();

    }

    /**
     * @param array $attributes
     * @return string
     */
    public static function toHtmlAttributes( array $attributes ): string {

        $htmlAttributes = '';

        foreach ( $attributes as $name => $value ) {

            $htmlAttributes .= empty( $htmlAttributes ) ? '': ' ';
            $htmlAttributes .= $name . '="' . htmlspecialchars( $value ) . '"';

        }

        return $htmlAttributes;

    }

}

/**
 * Get contents of a template file in a neutral context.
 *
 * @param $file
 * @param $vars
 * @return string
 */
function getContents( $file, $vars ) {

    extract( $vars );
    ob_start();
    if ( file_exists( $file ) ) {
        include $file;
    }
    $templatePart = ob_get_clean();

    return $templatePart;

}