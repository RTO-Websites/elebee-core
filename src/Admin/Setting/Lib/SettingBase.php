<?php
/**
 * SettingBase.php
 *
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 */

namespace ElebeeCore\Admin\Setting\Lib;


use ElebeeCore\Lib\Util\Renderable;

\defined( 'ABSPATH' ) || exit;

abstract class SettingBase implements Renderable {

    private $name;

    private $title;

    private $default;

    /**
     * SettingBase constructor.
     * @param string $name
     * @param string $title
     * @param mixed  $default
     */
    public function __construct( string $name, string $title, $default = false ) {

        $this->name = $name;
        $this->title = $title;
        $this->default = $default;

    }

    /**
     * @return string
     */
    public function getName() {

        return $this->name;

    }

    /**
     * @return string
     */
    public function getTitle() {

        return $this->title;

    }

    /**
     * @return mixed
     */
    public function getDefault() {

        return $this->default;

    }

    public function register( string $page, string $section = 'default', array $args = [] ) {

        add_settings_field(
            $this->getName(),
            $this->getTitle(),
            [ $this, 'render' ],
            $page,
            $section,
            $args
        );

        register_setting( $page, $this->getName() );

    }

    /**
     * @return mixed
     */
    public function getOption() {

        if ( $this->getDefault() === true ) {
            $optionValue = get_option( $this->getName() );
            $option = $optionValue === '' ? false : true;
        }
        else {
            $option = get_option( $this->getName(), $this->getDefault() );
        }

        return $option;

    }

    abstract public function render( array $args );

}