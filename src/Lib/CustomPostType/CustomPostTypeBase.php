<?php
/**
 * @since   0.3.0
 *
 * @package ElebeeCore\Lib\CustomCss
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/CustomPostType.html
 */

namespace ElebeeCore\Lib\CustomPostType;


use ElebeeCore\Lib\Util\Hooking;

\defined( 'ABSPATH' ) || exit;

/**
 * @since   0.3.0
 *
 * @package ElebeeCore\Lib\CustomCss
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/CustomPostType.html
 */
abstract class CustomPostTypeBase extends Hooking {

    /**
     * @since 0.3.0
     * @var string
     */
    private $name;

    /**
     * @since 0.3.0
     * @var array
     */
    private $args;

    /**
     * CustomPostType constructor.
     *
     * @since 0.3.0
     *
     * @param       $name
     * @param array $args
     */
    public function __construct( string $name, array $args ) {

        $this->name = $name;
        $this->args = $args;

        parent::__construct();


    }

    /**
     * @since 0.3.0
     *
     * @return string
     */
    public function getName(): string {

        return $this->name;

    }

    /**
     * @since 0.3.0
     *
     * @return void
     */
    public function defineAdminHooks() {

        $this->getLoader()->addFilter( 'post_updated_messages', $this, 'addPostUpdatedMessages' );
        $this->getLoader()->addFilter( 'bulk_post_updated_messages', $this, 'addBulkPostUpdatedMessages', 10, 2 );

    }

    /**
     * @since 0.3.0
     *
     * @return void
     */
    public function definePublicHooks() {

        $this->getLoader()->addAction( 'init', $this, 'register' );

    }

    /**
     * @since 0.3.0
     *
     * @return void
     */
    public function register() {

        register_post_type( $this->name, $this->args );

    }

    /**
     * @since 0.3.0
     *
     * @see   https://developer.wordpress.org/reference/hooks/post_updated_messages/
     *
     * @param array $messages
     * @return array
     */
    public abstract function addPostUpdatedMessages( array $messages ): array;

    /**
     * @since 0.3.0
     *
     * @see   https://codex.wordpress.org/Plugin_API/Filter_Reference/bulk_post_updated_messages
     *
     * @param array $bulkMessages
     * @param array $bulkCounts
     * @return array
     */
    public abstract function addBulkPostUpdatedMessages( array $bulkMessages, array $bulkCounts ): array;

}