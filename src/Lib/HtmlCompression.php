<?php namespace ElebeeCore\Lib;

/**
 * @since 1.0.0
 * @author hterhoeven
 * @licence MIT
 */

class HtmlCompression {

    /**
     * @var bool
     */
    const COMPRESS_CSS = true;

    /**
     * @var bool
     */
    const COMPRESS_JS = false;

    /**
     * @var bool
     */
    const INFO_COMMENT = true;

    /**
     * @var bool
     */
    const REMOVE_COMMENTS = true;

    /**
     * @var
     */
    private $html;

    /**
     * @return mixed
     */
    public function __toString() {

        return $this->html;

    }

    /**
     * @param $raw
     * @param $compressed
     * @return string
     */
    private function bottomComment( $raw, $compressed ) {

        $raw = strlen( $raw );
        $compressed = strlen( $compressed );
        $savings = ( $raw - $compressed ) / $raw * 100;
        $savings = round( $savings, 2 );
        return '<!-- HTML Minify | https://fastwp.de/2044/ | Größe reduziert um ' . $savings . '% | Von ' . $raw . ' Bytes, auf ' . $compressed . ' Bytes -->';

    }

    /**
     * @param $html
     * @return string
     */
    private function minifyHTML( $html ) {

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
     * @param $html
     * @return string
     */
    public function parseHTML( $html ) {

        $this->html = $this->minifyHTML( $html );
        if ( self::INFO_COMMENT ) {
            $this->html .= "\n" . $this->bottomComment( $html, $this->html );
        }

        return $this->html;

    }

    /**
     * @param $str
     * @return mixed
     */
    private function removeWhiteSpace( $str ) {

        $str = str_replace( "\t", ' ', $str );
        $str = str_replace( "\n", '', $str );
        $str = str_replace( "\r", '', $str );
        while ( stristr( $str, '  ' ) ) {
            $str = str_replace( '  ', ' ', $str );
        }
        return $str;

    }

    /**
     *
     */
    public function start() {

        ob_start( [ $this, 'parseHTML' ] );

    }

}