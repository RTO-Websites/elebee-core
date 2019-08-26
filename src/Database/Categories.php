<?php

namespace ElebeeCore\Database;

/**
 * Class Categories
 *
 * @since   0.7.2
 * @package ElebeeCore\Elementor\Database
 */
class Categories {

    /**
     * Initialize the tables for the categories
     *
     * @since 0.7.2
     */
    public function init () {
        global $wpdb;

        $wpdb->query(
            '
            CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'elebee_ratings_categories 
            ( 
                id BIGINT NOT NULL AUTO_INCREMENT , 
                postID BIGINT NOT NULL , 
                widgetID VARCHAR(255) NOT NULL,
                targetPostID BIGINT NOT NULL , 
                categoryID VARCHAR(255) NOT NULL, 
                name VARCHAR(255) NOT NULL , 
                icon VARCHAR(255) NOT NULL , 
                color VARCHAR(255) NOT NULL , 
                colorHover VARCHAR(255) NOT NULL , 
                colorSelected VARCHAR(255) NOT NULL , 
                required BOOLEAN NOT NULL , 
                archived BOOLEAN NOT NULL ,
                
                PRIMARY KEY (id)
            );
            '
        );
    }

    /**
     * Add a category
     *
     * @since 0.7.2
     * @param $postID
     * @param $widgetID
     * @param $targetPostID
     * @param $categoryID
     * @param $name
     * @param $icon
     * @param $color
     * @param $colorHover
     * @param $colorSelected
     * @param $required
     */
    public function add ( $postID, $widgetID, $targetPostID, $categoryID, $name, $icon, $color, $colorHover, $colorSelected, $required ) {
        global $wpdb;

        $wpdb->query(
            $wpdb->prepare(
                '
                INSERT INTO ' . $wpdb->prefix . 'elebee_ratings_categories
                (
                    postID,
                    widgetID, 
                    targetPostID, 
                    categoryID, 
                    name, 
                    icon, 
                    color, 
                    colorHover, 
                    colorSelected, 
                    required,
                    archived
                ) 
                VALUES 
                (
                    %d,
                    %s,
                    %d,
                    %s,
                    %s,
                    %s,
                    %s,
                    %s,
                    %s,
                    %d,
                    0
                )
                ',

                $postID,
                $widgetID,
                $targetPostID,
                $categoryID,
                $name,
                $icon,
                $color,
                $colorHover,
                $colorSelected,
                $required
            )
        );
    }

    /**
     * Update a category by the category's categoryID
     *
     * @since 0.7.2
     * @param $categoryID
     * @param $postID
     * @param $widgetID
     * @param $targetPostID
     * @param $name
     * @param $icon
     * @param $color
     * @param $colorHover
     * @param $colorSelected
     * @param $required
     */
    public function updateByCategoryID ( $categoryID, $postID, $widgetID, $targetPostID, $name, $icon, $color, $colorHover, $colorSelected, $required ) {
        global $wpdb;

        $wpdb->query(
            $wpdb->prepare(
                '
                UPDATE ' . $wpdb->prefix . 'elebee_ratings_categories SET
                
                postID=%d,
                widgetID=%s, 
                targetPostID=%d,
                name=%s, 
                icon=%s, 
                color=%s, 
                colorHover=%s, 
                colorSelected=%s, 
                required=%d
                
                WHERE categoryID=%s
                ',

                $postID,
                $widgetID,
                $targetPostID,
                $name,
                $icon,
                $color,
                $colorHover,
                $colorSelected,
                $required,
                $categoryID
            )
        );
    }

    /**
     * Get all categories
     *
     * @since 0.7.2
     * @return array|object|null
     */
    public function getAll () {
        global $wpdb;

        return $wpdb->get_results(
            'SELECT * FROM ' . $wpdb->prefix . 'elebee_ratings_categories WHERE archived=0'
        );
    }

    /**
     * Get all categories by their targetPostID
     *
     * @since 0.7.2
     * @param $postID
     * @return array|object|null
     */
    public function getByTargetPostID ( $postID ) {
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare(
                'SELECT * FROM ' . $wpdb->prefix . 'elebee_ratings_categories WHERE targetPostID=%d AND archived=0',

                $postID
            )
        );
    }

    /**
     * Get all categories by their widgetID
     *
     * @since 0.7.2
     * @param $widgetID
     * @return array|object|null
     */
    public function getByWidgetID ( $widgetID ) {
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare(
                'SELECT * FROM ' . $wpdb->prefix . 'elebee_ratings_categories WHERE widgetID=%d AND archived=0',

                $widgetID
            )
        );
    }

    /**
     * Get all categories by their categoryID
     *
     * @since 0.7.2
     * @param $categoryID
     * @return array|object|null
     */
    public function getByCategoryID ( $categoryID ) {
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare(
                'SELECT * FROM ' . $wpdb->prefix . 'elebee_ratings_categories WHERE categoryID=%d AND archived=0',

                $categoryID
            )
        );
    }

    /**
     * Archive a category by it's categoryID
     *
     * @since 0.7.2
     * @param $categoryID
     */
    public function archiveByCategoryID ( $categoryID ) {
        global $wpdb;

        $wpdb->query(
            $wpdb->prepare(
                'UPDATE ' . $wpdb->prefix . 'elebee_ratings_categories SET archived=1 WHERE categoryID=%d',

                $categoryID
            )
        );
    }

}