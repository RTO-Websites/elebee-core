<?php

namespace ElebeeCore\Database;

/**
 * Class Categories
 *
 * @since   0.8.0
 * @package ElebeeCore\Elementor\Database
 */
class EMails {

    /**
     * Initialize the tables for the emails
     *
     * @since 0.8.0
     */
    public function init () {
        global $wpdb;

        $wpdb->query(
            '
            CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'elebee_comments_emails
            ( 
                id BIGINT NOT NULL AUTO_INCREMENT , 
                widgetID VARCHAR(255) NOT NULL ,
                email VARCHAR(255) NOT NULL ,
                archived BOOLEAN NOT NULL ,
                
                PRIMARY KEY (id)
            );
            '
        );
    }

    /**
     * Add a email
     *
     * @since 0.8.0
     * @param $email
     * @param $widgetID
     */
    public function add ( $widgetID, $email ) {
        global $wpdb;

        $wpdb->query(
            $wpdb->prepare(
                '
                    INSERT INTO ' . $wpdb->prefix . 'elebee_comments_emails
                    (
                        widgetID ,
                        email ,
                        archived
                    )
                    VALUES
                    (
                        %s ,
                        %s ,
                        0
                    )
                ',

                $widgetID,
                $email
            )
        );
    }

    /**
     * Archive a email by it's widgetID
     *
     * @since 0.8.0
     * @param $widgetID
     */
    public function archive ( $widgetID, $email ) {
        global $wpdb;

        $wpdb->query(
            $wpdb->prepare(
                'UPDATE ' . $wpdb->prefix . 'elebee_comments_emails SET archived=1 WHERE widgetID=%d AND email=%s',

                $widgetID,
                $email
            )
        );
    }

    /**
     * Get all emails by their widgetID
     *
     * @since 0.8.0
     * @param $widgetID
     * @return array|object|null
     */
    public function getByWidgetID ( $widgetID ) {
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare(
                'SELECT * FROM ' . $wpdb->prefix . 'elebee_comments_emails WHERE widgetID=%d AND archived=0',

                $widgetID
            )
        );
    }
}