<?php

/**
 * MetaKey.php
 */

namespace ElebeeCore\Lib\MetaBox;


use ElebeeCore\Lib\Template;

/**
 * Class MetaKey
 *
 * @package ElebeeCore
 * @author RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @since 0.2.0
 */
abstract class MetaKey {

    /**
     * Equal to the FILTER_DEFAULT constant.
     *
     * @see http://php.net/manual/en/filter.constants.php
     * @since 0.2.0
     */
    const TYPE_DEFAULT = FILTER_DEFAULT;

    /**
     * Equal to the FILTER_REQUIRE_ARRAY constant.
     *
     * @see http://php.net/manual/en/filter.constants.php
     * @since 0.2.0
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
     * @param $key
     * @param $label
     * @param $type
     *
     * @since 0.2.0
     */
    public function __construct( $key, $label, $type ) {

        $this->key = $key;
        $this->type = $type;
        $this->label = $label;

    }

    /**
     * Get the key that identifies the meta key.
     *
     * @return mixed
     *
     * @since 0.2.0
     */
    public function getKey() {

        return $this->key;

    }

    /**
     * Get the meta key's label.
     *
     * @return mixed
     *
     * @since 0.2.0
     */
    public function getLabel() {

        return $this->label;

    }

    /**
     * Get the meta key's type.
     *
     * @return mixed
     *
     * @since 0.2.0
     */
    public function getType() {

        return $this->type;

    }

    /**
     * Get the Template that renders the meta key.
     *
     * @return Template
     *
     * @since 0.2.0
     */
    public function getTemplate() {

        return $this->template;

    }

    /**
     * Set the template that renders the meta key.
     *
     * @param Template $template
     *
     * @return void
     *
     * @since 0.2.0
     */
    public function setTemplate( Template $template ) {

        $template->setVar( 'metaKey', $this );
        $this->template = $template;

    }

    /**
     * Get the meta value of the meta key.
     *
     * @param null|mixed $postId (optional) The id of the post to receive the meta value from. If no id or null is passed the id of the current post in the loop will be used.
     *
     * @return mixed
     *
     * @since 0.2.0
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
     * @param mixed $postId (required) The id of the post to save the post meta for.
     *
     * @return void
     *
     * @since 0.2.0
     */
    public function save( $postId ) {

        if ( $this->getType() === FILTER_DEFAULT ) {
            $value = filter_input( INPUT_POST, $this->getKey() );
        } else {
            $value = filter_input( INPUT_POST, $this->getKey(), FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
        }

        update_post_meta( $postId, $this->getKey(), $value );
    }

}