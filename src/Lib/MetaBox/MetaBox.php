<?php

/**
 * @since   0.3.0
 *
 * @package ElebeeCore\Admin
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/MetaBox/MetaBox.html
 */

namespace ElebeeCore\Lib\MetaBox;


use ElebeeCore\Lib\Util\Template;

\defined( 'ABSPATH' ) || exit;

/**
 * Class MetaBox
 *
 * @since   0.3.0
 *
 * @package ElebeeCore\Admin
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/MetaBox/MetaBox.html
 */
class MetaBox {

    /**
     * @since 0.2.0
     * @var string
     */
    private $id;

    /**
     * @since 0.2.0
     * @var string
     */
    private $title;

    /**
     * @since 0.2.0
     * @var string
     */
    private $context;

    /**
     * @since 0.2.0
     * @var int
     */
    private $priority;

    /**
     * @since 0.2.0
     * @var Template
     */
    private $template;

    /**
     * @since 0.2.0
     * @var array
     */
    private $metaKeyList;

    /**
     * @since 0.2.0
     * @var array
     */
    private $postTypeList;

    /**
     * @since 0.2.0
     * @var string
     */
    private $nonceName;

    /**
     * @since 0.2.0
     * @var string
     */
    private $nonceAction;

    /**
     * MetaBox constructor.
     *
     * @since 0.2.0
     *
     * @param string   $id
     * @param string   $title
     * @param string   $context
     * @param int      $priority (optional)
     * @param Template $template (optional)
     */
    public function __construct( string $id, string $title, string $context = 'advanced', int $priority = 10, Template $template = null ) {

        $this->id = $id;
        $this->title = $title;
        $this->context = $context;
        $this->priority = $priority;

        if ( null === $template ) {
            $template = new Template( __DIR__ . '/partials/meta-box-default.php' );
        }

        $this->template = $template;

        $this->metaKeyList = [];

        $this->postTypeList = [];

        $this->nonceName = get_class( $this );
        $this->nonceAction = 'save-' . $this->nonceName;

    }

    /**
     * Get the meta key list.
     *
     * @since 0.2.0
     *
     * @return array
     */
    public function getMetaKeyList(): array {

        return $this->metaKeyList;

    }

    /**
     * Add a meta key
     *
     * @since 0.2.0
     *
     * @param MetaKeyBase $metaKey
     * @return void
     */
    public function addMetaKey( MetaKeyBase $metaKey ) {

        $metaKey->getTemplate()->setVar( 'metaBox', $this );
        $this->metaKeyList[] = $metaKey;

    }

    /**
     * Add support for a specific post type.
     *
     * Note: By default the meta box will be added to every post type.
     * If you add supported post types, the meta box will only appear on these post types.
     *
     * @since 0.2.0
     *
     * @param string $postType
     * @param int    $priority (optional)
     * @return void
     */
    public function addPostTypeSupport( string $postType, int $priority = 10 ) {

        $this->postTypeList[] = $postType;

        remove_action( 'add_meta_boxes', [ $this, 'actionAddMetaBox' ] );
        add_action( 'add_meta_boxes_' . $postType, [ $this, 'actionAddMetaBox' ], $priority, 1 );

    }

    /**
     * 'add_meta_boxes' action hook callback.
     *
     * @since 0.2.0
     *
     * @param \WP_Post|string $post
     * @return void
     */
    public function actionAddMetaBox( $post ) {

        $postType = $post instanceof \WP_Post ? $post->post_type : $post;
        add_meta_box( $this->id, $this->title, [ $this, 'renderMetaBox' ], $postType, $this->context );

    }

    /**
     * Renders the meta box.
     *
     * @since 0.2.0
     *
     * @param $post
     * @return void
     */
    public function renderMetaBox( $post ) {

        wp_nonce_field( $this->nonceAction, $this->nonceName );
        $this->template->setVar( 'metaKeyList', $this->getMetaKeyList() );
        $this->template->render();

    }

    /**
     * 'save_post' action hook callback.
     *
     * @param int      $postId
     * @param \WP_Post $post
     * @param          $update
     *
     * @return void
     */
    public function actionSavePost( int $postId, \WP_Post $post, $update ) {

        $postType = get_post_type_object( $post->post_type );
        $currentUserCanEditPostType = current_user_can( $postType->cap->edit_post, $postId );

        if ( wp_is_post_autosave( $postId ) || !$currentUserCanEditPostType || wp_is_post_revision( $postId ) ) {
            return;
        }

        $nonce = filter_input( INPUT_POST, $this->nonceName );
        if ( $nonce === null ) {
            $nonce = filter_input( INPUT_GET, $this->nonceName );
        }

        if ( !wp_verify_nonce( $nonce, $this->nonceAction ) ) {
            return;
        }

        foreach ( $this->metaKeyList as $metaKey ) {
            $metaKey->save( $postId );
        }

    }

    /**
     * Get the post meta.
     *
     * @since 0.2.0
     *
     * @param string   $metaKey
     * @param int|null $postId (optional)
     * @return mixed
     */
    public function getPostMeta( string $metaKey, int $postId = null ) {

        if ( $postId === null ) {
            $postId = get_the_ID();
        }

        return get_post_meta( $postId, $metaKey, true );

    }

    /**
     * Get all post metas.
     *
     * @since 0.2.0
     *
     * @param int|null $postId (optional)
     * @return array
     */
    public function getPostMetas( $postId = null ) {

        $postMetas = [];

        foreach ( $this->metaKeyList as $metaKey => $type ) {
            $postMetas[$metaKey] = $this->getPostMeta( $metaKey, $postId );
        }

        return $postMetas;

    }

    /**
     * @since 0.2.0
     *
     * @return void
     */
    public function register() {

        add_action( 'add_meta_boxes', [ $this, 'actionAddMetaBox' ], $this->priority, 1 );
        add_action( 'save_post', [ $this, 'actionSavePost' ], 10, 3 );

    }

}