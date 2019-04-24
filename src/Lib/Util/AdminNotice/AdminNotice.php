<?php
/**
 * AdminNotice.php
 *
 * Helper class to display admin notices.
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Lib
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/Util/AdminNotice.html
 */

namespace ElebeeCore\Lib\Util\AdminNotice;


class AdminNotice {

    /**
     * The ID of this utility.
     *
     * @since 0.1.0
     * @var   string $utilName The ID of this utility.
     */
    private $utilName = 'util-admin-notice';

    /**
     * @var string
     */
    private $utilUrl = '';

    /**
     * @var array
     */
    private $allowedNotices = [ 'success', 'error', 'warning', 'info' ];

    public function __construct() {
        $this->utilUrl = str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__);

    }

    /**
     * Check, if the user already dismissed the notice
     *
     * @param string $userMetaKey Unique notice name.
     *
     * @return bool
     */
    private function isNoticeDismissed( string $userMetaKey ) {
        $userId = get_current_user_id();
        $isDismissed = get_user_meta( $userId, $userMetaKey, true );

        return !empty( $isDismissed )  && $isDismissed === 'confirmed' ? true : false;
    }

    /**
     * Show notice.
     *
     * @since 0.1.0
     *
     * @param string $noticeName
     *      Unique name. Used as css container class and as meta key in database.
     * @param string $message
     *      The Message to be displayed.
     * @param string $type
     *      Optional. Type of the message. Default 'error'. Accepts 'success', 'error', 'warning', 'info'.
     * @param bool $isDismissible
     *      Optional. After closing the notice, this will be never shown again for the current user. Default 'true'.
     *
     * @return void
     */
    public function getNotice( string $noticeName, string $message, string $type = 'error', bool $isDismissible = true ) {

        if ( $this->isNoticeDismissed( $noticeName ) ) {
            return;
        }

        if ( !in_array( $type, $this->allowedNotices ) ) {
            $type = 'error';
        }

        $additionalClass = $isDismissible === true ? ' is-dismissible' : '';
        $class = $noticeName . ' notice notice-' . $type . $additionalClass;

        printf(
            '<div class="%1$s" data-name="%2$s"><p>%3$s</p></div>',
            esc_attr( $class ), esc_attr( $noticeName ), esc_html( $message )
        );
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function enqueueScripts() {

        wp_enqueue_script( $this->utilName, $this->utilUrl . '/js/admin.js', [ 'jquery' ] );

    }

    /**
     * Set parameters for ajax request.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function localizeScripts() {

        $params = array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'user_id' => get_current_user_id(),
            'nonce' => wp_create_nonce( $this->utilName ),
        );

        wp_localize_script( $this->utilName, 'admin_notice', $params );

    }

    /**
     * Mark notice as dismissed. Called by ajax request.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function dismissNotice() {

        check_ajax_referer( $this->utilName, 'nonce' );

        $userId = filter_input( INPUT_POST, 'user_id', FILTER_VALIDATE_INT );
        $userMetaKey = filter_input( INPUT_POST, 'user_meta_key' );

        if ( !empty( $userId ) && !empty( $userMetaKey )) {
            update_user_meta( $userId, $userMetaKey, 'confirmed' );
            echo 'confirmed';
        }
        else {
            echo json_encode( [ 'user_id' => $userId, 'user_meta_key' => $userMetaKey ] );
        }

        wp_die();

    }
}
