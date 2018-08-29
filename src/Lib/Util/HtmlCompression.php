<?php
/**
 * HtmlCompression.php
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/Util/HtmlCompression.html
 */

namespace ElebeeCore\Lib\Util;


defined( 'ABSPATH' ) || exit;

/**
 * Class HtmlCompression
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/Util/HtmlCompression.html
 */
class HtmlCompression {

    /**
     * @since 0.2.0
     * @var bool
     */
    const COMPRESS_CSS = true;

    /**
     * @since 0.2.0
     * @var bool
     */
    const COMPRESS_JS = false;

    /**
     * @since 0.2.0
     * @var bool
     */
    const INFO_COMMENT = true;

    /**
     * @since 0.2.0
     * @var bool
     */
    const REMOVE_COMMENTS = true;

    /**
     * @since 0.2.0
     * @var string
     */
    private $html;

    /**
     * @since 0.2.0
     *
     * @return string
     */
    public function __toString() {

        return $this->html;

    }

    /**
     * @since 0.2.0
     *
     * @param string $raw
     * @param string $compressed
     * @return string
     */
    private function bottomComment( string $raw, string $compressed ): string {

        $raw = strlen( $raw );
        $compressed = strlen( $compressed );
        $savings = ( $raw - $compressed ) / $raw * 100;
        $savings = round( $savings, 2 );
        return '<!-- HTML Minify | https://fastwp.de/2044/ | Größe reduziert um ' . $savings . '% | Von ' . $raw . ' Bytes, auf ' . $compressed . ' Bytes -->';

    }

    /**
     * @since 0.2.0
     *
     * @param string $html
     * @return string
     */
    private function minifyHTML( string $html ): string {

        $pattern = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';
        preg_match_all( $pattern, $html, $matches, PREG_SET_ORDER );
        $overriding = false;
        $raw_tag = false;
        $html = '';
        foreach ( $matches as $token ) {
            $tag = ( isset( $token['tag'] ) ) ? strtolower( $token['tag'] ) : null;
            $content = $token[0];
            if ( is_null( $tag ) ) {
                if ( !empty( $token['script'] ) ) {
                    $strip = self::COMPRESS_JS;
                } else if ( !empty( $token['style'] ) ) {
                    $strip = self::COMPRESS_CSS;
                } else if ( $content == '<!--wp-html-compression no compression-->' ) {
                    $overriding = !$overriding;
                    continue;
                } else if ( self::REMOVE_COMMENTS ) {
                    if ( !$overriding && $raw_tag != 'textarea' ) {
                        $content = preg_replace( '/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content );
                    }
                }
            } else {
                if ( $tag == 'pre' || $tag == 'textarea' ) {
                    $raw_tag = $tag;
                } else if ( $tag == '/pre' || $tag == '/textarea' ) {
                    $raw_tag = false;
                } else {
                    if ( $raw_tag || $overriding ) {
                        $strip = false;
                    } else {
                        $strip = true;
                        $content = preg_replace( '/(\s+)(\w++(?<!\baction|\balt|\bcontent|\bsrc)="")/', '$1', $content );
                        $content = str_replace( ' />', '/>', $content );
                    }
                }
            }
            if ( $strip ) {
                $content = $this->removeWhiteSpace( $content );
            }
            $html .= $content;
        }
        return $html;

    }

    /**
     * @since 0.2.0
     *
     * @param string $html
     * @return string
     */
    public function parseHTML( string $html ): string {

        $this->html = $this->minifyHTML( $html );
        if ( self::INFO_COMMENT ) {
            $this->html .= "\n" . $this->bottomComment( $html, $this->html );
        }

        return $this->html;

    }

    /**
     * @since 0.2.0
     *
     * @param string $str
     * @return string
     */
    private function removeWhiteSpace( string $str ): string {

        $str = str_replace( "\t", ' ', $str );
        $str = str_replace( "\n", '', $str );
        $str = str_replace( "\r", '', $str );
        while ( stristr( $str, '  ' ) ) {
            $str = str_replace( '  ', ' ', $str );
        }
        return $str;

    }

    /**
     * @since 0.2.0
     *
     * @return void
     */
    public function start() {

        ob_start( [ $this, 'parseHTML' ] );

    }

}