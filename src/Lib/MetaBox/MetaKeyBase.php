<?php
/**
 * MetaKey.php
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\MetaBox
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/MetaBox/MetaKey.html
 */

namespace ElebeeCore\Lib\MetaBox;


use ElebeeCore\Lib\Util\Template;

\defined( 'ABSPATH' ) || exit;

/**
 * Class MetaKey
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\MetaBox
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/MetaBox/MetaKey.html
 */
abstract class MetaKeyBase {

    /**
     * Equal to the FILTER_DEFAULT constant.
     *
     * @since 0.2.0
     *
     * @see   http://php.net/manual/en/filter.constants.php
     */
    const TYPE_DEFAULT = FILTER_DEFAULT;

    /**
     * Equal to the FILTER_REQUIRE_ARRAY constant.
     *
     * @since 0.2.0
     *
     * @see   http://php.net/manual/en/filter.constants.php
     */
    const TYPE_ARRAY = FILTER_REQUIRE_ARRAY;

    /**
     * A unique key.
     *
     * @var
     *
     * @since 0.2.0
     * @ignore
     */
    private $key;

    /**
     * @var
     *
     * @since 0.2.0
     * @ignore
     */
    private $label;

    /**
     * @var
     *
     * @since 0.2.0
     * @ignore
     */
    private $type;

    /**
     * @var Template
     *
     * @since 0.2.0
     * @ignore
     */
    private $template;

    /**
     * MetaKey constructor.
     *
     * @since 0.2.0
     *
     * @param string $key
     * @param string $label
     * @param int    $type
     */
    public function __construct( string $key, string $label, int $type ) {

        $this->key = $key;
        $this->type = $type;
        $this->label = $label;

    }

    /**
     * Get the key that identifies the meta key.
     *
     * @since 0.2.0
     *
     * @return string
     */
    public function getKey(): string {

        return $this->key;

    }

    /**
     * Get the meta key's label.
     *
     * @since 0.2.0
     *
     * @return string
     */
    public function getLabel(): string {

        return $this->label;

    }

    /**
     * Get the meta key's type.
     *
     * @since 0.2.0
     *
     * @return string
     */
    public function getType(): string {

        return $this->type;

    }

    /**
     * Get the Template that renders the meta key.
     *
     * @since 0.2.0
     *
     * @return Template
     */
    public function getTemplate(): Template {

        return $this->template;

    }

    /**
     * Set the template that renders the meta key.
     *
     * @since 0.2.0
     *
     * @param Template $template
     * @return void
     */
    public function setTemplate( Template $template ) {

        $template->setVar( 'metaKey', $this );
        $this->template = $template;

    }

    /**
     * Get the meta value of the meta key.
     *
     * @since 0.2.0
     *
     * @param null|int $postId (optional) The id of the post to receive the meta value from. If no id or null is passed the id of the current post in the loop will be used.
     * @return mixed
     */
    public function getValue( $postId = null ) {

        if ( null === $postId ) {
            $postId = get_the_ID();
        }

        return get_post_meta( $postId, $this->key, true );

    }

    /**
     * Save the post meta.
     *
     * @since 0.2.0
     *
     * @param int $postId (required) The id of the post to save the post meta for.
     * @return void
     */
    public function save( int $postId ) {

        if ( $this->getType() === FILTER_DEFAULT ) {
            $value = filter_input( INPUT_POST, $this->getKey() );
        } else {
            $value = filter_input( INPUT_POST, $this->getKey(), FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
        }

        update_post_meta( $postId, $this->getKey(), $value );
    }

}