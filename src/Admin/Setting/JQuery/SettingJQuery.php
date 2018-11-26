<?php
/**
 * JQuery.php
 *
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 */

namespace ElebeeCore\Admin\Setting\JQuery;


use ElebeeCore\Admin\Setting\SettingBase;
use ElebeeCore\Lib\Util\Template;

class SettingJQuery extends SettingBase {

    private $choices;

    /**
     * JQuery constructor.
     */
    public function __construct() {

        parent::__construct( 'jquery', __( 'jQuery', 'elebee' ), 'default' );
        $this->choices = [
            'default' => __( 'Default', 'elebee' ),
            'latest-cdn' => __( '3.3.1 (CDN)', 'elebee' ),
            'latest-local' => __( '3.3.1 (local)', 'elebee' ),
        ];

    }

    public function render() {

        $template = new Template( dirname( __DIR__ ) . '/partials/choice-select.php', [
            'name' => $this->getName(),
            'choices' => $this->choices,
            'option' => $this->getOption(),
        ] );
        $template->render();

    }

}