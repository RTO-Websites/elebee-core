<?php
/**
 * MetaKeyTaxonomy.php
 */

namespace ElebeeCore\Lib\MetaBox;


use ElebeeCore\Lib\Template;

/**
 * Class MetaKeyTaxonomy
 *
 * @package ElebeeCore
 * @author RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @since 0.2.0
 */
class MetaKeyTaxonomy extends MetaKey {

    /**
     * @var array
     *
     * @since 0.2.0
     * @ignore
     */
    private $taxonomy;

    /**
     * @var Template
     *
     * @since 0.2.0
     * @ignore
     */
    private $termTemplate;

    /**
     * MetaKeyCheckbox constructor.
     *
     * @param $key
     * @param $label
     * @param int $type
     *
     * @since 0.2.0
     */
    public function __construct( $key, $label, $type = self::TYPE_ARRAY ) {

        $this->taxonomy = [];

        $this->setTermTemplate( new Template( __DIR__ . '/partials/meta-key-term-default.php' ) );

        $defaultTemplate = new Template( __DIR__ . '/partials/meta-key-taxonomy-default.php' );
        $this->setTemplate( $defaultTemplate );

        parent::__construct( $key, $label, $type );

    }

    /**
     * Add a taxonomy.
     *
     * @param $taxonomy
     *
     * @return void
     *
     * @since 0.2.0
     */
    public function addTaxonomy( $taxonomy ) {

        $this->taxonomy[] = $taxonomy;

    }

    /**
     * Get the taxonomies.
     *
     * @return array
     *
     * @since 0.2.0
     */
    public function getTaxonomies() {

        return $this->taxonomy;

    }

    /**
     * Set the term template.
     *
     * @param Template $template
     *
     * @return void
     *
     * @since 0.2.0
     */
    public function setTermTemplate( Template $template ) {

        $template->setVar( 'metaKey', $this );
        $this->termTemplate = $template;

    }

    /**
     * {@inheritdoc}
     *
     * @param $postId
     *
     * @return array
     */
    public function getValue( $postId = null ) {

        $value = parent::getValue( $postId );
        return empty( $value ) ? [] : $value;

    }

}