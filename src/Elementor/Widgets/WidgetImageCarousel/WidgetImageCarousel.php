<?php

namespace ElebeeCore\Elementor\Widgets\WidgetImageCarousel;

use Elementor\Widget_Image_Carousel;

class WidgetImageCarousel extends Widget_Image_Carousel {

    private $newUniqueID;

    /**
     * Problem: Use of the widget in loop cause broken Lightbox.
     * The Id isn't anymore unique.
     *
     * Solution: Extend Id with unique id per instance.
     *
     * @since 1.7.2
     * @access public
     *
     * @return string Extended elementor id.
     */
    public function get_id() {
        if ( !isset( $this->newUniqueID ) ) {
            $this->newUniqueID = uniqid();
        }

        return parent::get_id() .' '. $this->newUniqueID;
    }

}