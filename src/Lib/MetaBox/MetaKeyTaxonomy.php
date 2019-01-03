<?php
/**
 * MetaKeyTaxonomy.php
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\MetaBox
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/MetaBox/MetaKeyTaxonomy.html
 */

namespace ElebeeCore\Lib\MetaBox;


use ElebeeCore\Lib\Util\Template;

\defined( 'ABSPATH' ) || exit;

/**
 * Class MetaKeyTaxonomy
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\MetaBox
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/MetaBox/MetaKeyTaxonomy.html
 */
class MetaKeyTaxonomy extends MetaKeyBase {

    /**
     * @since 0.2.0
     * @var array
     */
    private $taxonomy;

    /**
     * @since 0.2.0
     * @var Template
     */
    private $termTemplate;

    /**
     * MetaKeyCheckbox constructor.
     *
     * @since 0.2.0
     *
     * @param string $key
     * @param string $label
     * @param int    $type
     */
    public function __construct( string $key, string $label, int $type = self::TYPE_ARRAY ) {

        $this->taxonomy = [];

        $this->setTermTemplate( new Template( __DIR__ . '/partials/meta-key-term-default.php' ) );

        $defaultTemplate = new Template( __DIR__ . '/partials/meta-key-taxonomy-default.php' );
        $this->setTemplate( $defaultTemplate );

        parent::__construct( $key, $label, $type );

    }

    /**
     * Add a taxonomy.
     *
     * @since 0.2.0
     *
     * @param $taxonomy
     * @return void
     */
    public function addTaxonomy( $taxonomy ) {

        $this->taxonomy[] = $taxonomy;

    }

    /**
     * Get the taxonomies.
     *
     * @since 0.2.0
     *
     * @return array
     */
    public function getTaxonomies(): array {

        return $this->taxonomy;

    }

    /**
     * Set the term template.
     *
     * @since 0.2.0
     *
     * @param Template $template
     * @return void
     */
    public function setTermTemplate( Template $template ) {

        $template->setVar( 'metaKey', $this );
        $this->termTemplate = $template;

    }

    /**
     *
     * @param int $postId
     * @return array
     */
    public function getValue( int $postId ): array {

        $value = parent::getValue( $postId );
        return empty( $value ) ? [] : $value;

    }

}