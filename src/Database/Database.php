<?php

namespace ElebeeCore\Database;

/**
 * Class Database
 *
 * @since   0.7.2
 * @package ElebeeCore\Elementor\Database
 */
class Database {
    public $categories;
    public $emails;

    /**
     * Database constructor.
     *
     * @since 0.7.2
     */
    public function __construct () {
        $this->categories = new Categories();
        $this->emails = new EMails();

        $this->categories->init();
        $this->emails->init();
    }
}