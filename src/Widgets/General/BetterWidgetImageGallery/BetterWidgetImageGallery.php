<?php
namespace ElebeeCore\Widgets\General\BetterWidgetImageGallery;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use ElebeeCore\Lib\Elebee;
use ElebeeCore\Lib\ElebeeWidget;
use ElebeeCore\Widgets\BetterWidgetImageGallery\Lib\Album;
use ElebeeCore\Widgets\BetterWidgetImageGallery\Lib\Renderer;
use ElebeeCore\Widgets\BetterWidgetImageGallery\Lib\Gallery;
use ElebeeCore\Widgets\BetterWidgetImageGallery\Lib\Image;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Image Gallery Widget
 */
class BetterWidgetImageGallery extends ElebeeWidget {

    /**
     * @var Album
     */
    private $album;

    public function __construct( $data = [], $args = null ) {

        parent::__construct( $data, $args );
        $this->album = null;

    }

    public function enqueueStyles() {

        wp_enqueue_style( $this->get_name(), get_stylesheet_directory_uri() . '/vendor/rto-websites/elebee-core/src/Widgets/General/BetterWidgetImageGallery/css/better-widget-image-gallery.css', [], Elebee::VERSION, 'all' );

    }

    public function enqueueScripts() {
        // TODO: Implement enqueueScripts() method.
    }

	/**
	 * Retrieve image gallery widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'better-image-gallery';
	}

	/**
	 * Retrieve image gallery widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Better Image Gallery', TEXTDOMAIN );
	}

	/**
	 * Retrieve image gallery widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-gallery-grid';
	}

    /**
     * Retrieve button sizes.
     *
     * @access public
     * @static
     *
     * @return array An array containing button sizes.
     */
    public static function get_button_sizes() {
        return [
            'xs' => __( 'Extra Small', TEXTDOMAIN ),
            'sm' => __( 'Small', TEXTDOMAIN ),
            'md' => __( 'Medium', TEXTDOMAIN ),
            'lg' => __( 'Large', TEXTDOMAIN ),
            'xl' => __( 'Extra Large', TEXTDOMAIN ),
        ];
    }


    /**
	 * Add lightbox data to image link.
	 *
	 * Used to add lightbox data attributes to image link HTML.
	 *
	 * @access public
	 *
	 * @param string $link_html Image link HTML.
	 *
	 * @return string Image link HTML with lightbox data attributes.
	 */
	public function add_lightbox_data_to_image_link( $link_html ) {
		return preg_replace( '/^<a/', '<a ' . $this->get_render_attribute_string( 'link' ), $link_html );
	}

	/**
	 * Register image gallery widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_gallery',
			[
				'label' => __( 'Galleries', TEXTDOMAIN ),
			]
		);

        $this->add_control(
            'gallery_type',
            [
                'label'       => __( 'Type of Gallery', TEXTDOMAIN ),
                'type' => Controls_Manager::SELECT,
                'default' => 'wp',
                'options' => [
                    'wp'  => __( 'Wordpress', TEXTDOMAIN ),
                    'gesamt' => __( 'Gesamt', TEXTDOMAIN ),
                    'both' => __( 'Both', TEXTDOMAIN ),
                ],
            ]
        );
        $this->add_control(
            'gallery_list',
            [
                'label' => 'Gallery List',
                'type' => Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'wp_gallery',
                        'label' => __( 'Add Images', TEXTDOMAIN ),
                        'type' => Controls_Manager::GALLERY,
                    ],
                    [
                        'name'        => 'gallery_title',
                        'label'       => __( 'Gallery Title', TEXTDOMAIN ),
                        'type'        => Controls_Manager::TEXT,
                        'placeholder' => __( 'Gallery Title', TEXTDOMAIN ),

                    ],
                    [
                        'name' => 'custom_caption',
                        'label' => __( 'Custom Caption', TEXTDOMAIN ),
                        'type' => Controls_Manager::SWITCHER,
                        'default' => 'No',
                        'label_on' => __( 'Yes', 'your-plugin' ),
                        'label_off' => __( 'No', 'your-plugin' ),
                        'return_value' => 'yes',
                    ],
                    [
                        'name'        => 'custom_caption_text',
                        'label'       => __( 'Custom Caption', TEXTDOMAIN ),
                        'type'        => Controls_Manager::TEXT,
                        'default'     => __( 'Default caption', TEXTDOMAIN ),
                        'placeholder' => __( 'Type your caption text here', TEXTDOMAIN ),
                    ],
                ],
                'condition' => [
                    'gallery_type!' => 'gesamt',
                ],
            ]
        );

        $this->add_control(
            'gesamt_rubid',
            [
                'label'       => __( 'Rubric ID', TEXTDOMAIN ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => __( 'Type the Rubric ID here', TEXTDOMAIN ),
                'condition' => [
                    'gallery_type!' => 'wp',
                ],
            ]
        );

        $this->add_control(
            'gesamt_kid',
            [
                'label'       => __( 'Customer ID', TEXTDOMAIN ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => __( 'Type the Customer ID here', TEXTDOMAIN ),
                'condition' => [
                    'gallery_type!' => 'wp',
                ],
            ]
        );

        $this->add_control(
            'gesamt_pid',
            [
                'label'       => __( 'Publication ID', TEXTDOMAIN ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => __( 'Type the Publication ID here', TEXTDOMAIN ),
                'condition' => [
                    'gallery_type!' => 'wp',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_title',
            [
                'label' => __( 'Titles', TEXTDOMAIN ),
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label' => __( 'Show Title', TEXTDOMAIN ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __( 'Show', TEXTDOMAIN ),
                'label_off' => __( 'Hide', TEXTDOMAIN ),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'header_size',
            [
                'label' => __( 'HTML Tag', TEXTDOMAIN ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h2' => __( 'H2', TEXTDOMAIN ),
                    'h3' => __( 'H3', TEXTDOMAIN ),
                    'h4' => __( 'H4', TEXTDOMAIN ),
                    'h5' => __( 'H5', TEXTDOMAIN ),
                    'h6' => __( 'H6', TEXTDOMAIN ),
                    'div' => __( 'div', TEXTDOMAIN ),
                    'span' => __( 'span', TEXTDOMAIN ),
                    'p' => __( 'p', TEXTDOMAIN ),
                ],
                'default' => 'h3',
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => __( 'Title Alignment', TEXTDOMAIN ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', TEXTDOMAIN ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', TEXTDOMAIN ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', TEXTDOMAIN ),
                        'icon' => 'fa fa-align-right',
                    ],
                    'justify' => [
                        'title' => __( 'Justified', TEXTDOMAIN ),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .gallery-title' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'h_tag_color',
            [
                'label' => __( 'Title Color', TEXTDOMAIN ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gallery-title' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .gallery-title',
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'title_margin',
            [
                'label' => __( 'Title Margin', TEXTDOMAIN ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .gallery-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'title_padding',
            [
                'label' => __( 'Title Padding', TEXTDOMAIN ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .gallery-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_galleries_options',
            [
                'label' => __( 'Galleries Options', TEXTDOMAIN ),
            ]
        );

        $this->add_control(
            'gallery_sort',
            [
                'label'       => __( 'First Gallery', TEXTDOMAIN ),
                'type' => Controls_Manager::SELECT,
                'default' => 'wp',
                'options' => [
                    'wp'  => __( 'Wordpress', TEXTDOMAIN ),
                    'gesamt' => __( 'Gesamt', TEXTDOMAIN ),
                ],
                'condition' => [
                    'gallery_type' => 'both',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail',
				'exclude' => [ 'custom' ],
			]
		);

        $this->add_control(
            'first_image',
            [
                'label' => __( 'Show only first Image', TEXTDOMAIN ),
                'type' => Controls_Manager::SELECT,
                'default' => 'no',
                'options' => [
                    'yes'  => __( 'Yes', TEXTDOMAIN ),
                    'no' => __( 'No', TEXTDOMAIN ),
                ],
            ]
        );

        $this->add_control(
            'gallery_style',
            [
                'label' => __( 'Gallery Style', TEXTDOMAIN ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    'modal'  => __( 'Modal', TEXTDOMAIN ),
                    '' => __( 'Default', TEXTDOMAIN ),
                ],
                'condition' => [
                    'first_image' => 'yes',
                    'gallery_link' => 'file',
                ],
            ]
        );

		$gallery_columns = range( 1, 5 );
		$gallery_columns = array_combine( $gallery_columns, $gallery_columns );

        $this->add_responsive_control(
            'gallery_columns',
            [
                'label' => __( 'Gallery Columns', TEXTDOMAIN ),
                'type' => Controls_Manager::SELECT,
                'default' => 2,
                'options' => $gallery_columns,
                'selectors' => [
                    '{{WRAPPER}} .rto-image-columns .rto-gallery-container' => 'width: calc(100%/{{UNIT}});',
					'{{WRAPPER}} .rto-image-columns-modal .rto-gallery-container' => 'width: 100%;'
                ],
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => __( 'Columns', TEXTDOMAIN ),
                'type' => Controls_Manager::SELECT,
                'default' => 1,
                'options' => $gallery_columns,
                'condition' => [
                    'first_image' => 'no',
                ],
                'selectors' => [
                    '{{WRAPPER}} .rto-image-columns .rto-gallery-image-container' => 'width: calc(100%/{{UNIT}});',
                ],
            ]
        );

        $this->add_responsive_control(
            'sub_gallery_columns',
            [
                'label' => __( 'Sub Gallery Columns', TEXTDOMAIN ),
                'type' => Controls_Manager::SELECT,
                'default' => 2,
                'options' => $gallery_columns,
                'condition' => [
                    'gallery_style' => 'modal',
                    'gallery_link' => 'file',
                ],
                'selectors' => [
                    '{{WRAPPER}} .rto-image-columns-modal .rto-gallery-image-container' => 'width: calc(100%/{{UNIT}});',
                ],
            ]
        );

        $this->add_control(
            'gallery_link',
            [
                'label' => __( 'Link to', TEXTDOMAIN ),
                'type' => Controls_Manager::SELECT,
                'default' => 'file',
                'options' => [
                    'file' => __( 'Media File', TEXTDOMAIN ),
                    'none' => __( 'None', TEXTDOMAIN ),
                ],
            ]
        );

		$this->add_control(
			'open_lightbox',
			[
				'label' => __( 'Lightbox', TEXTDOMAIN ),
				'type' => Controls_Manager::SELECT,
				'default' => 'no',
				'options' => [
					'yes' => __( 'Yes', TEXTDOMAIN ),
					'no' => __( 'No', TEXTDOMAIN ),
				],
				'condition' => [
					'gallery_link' => 'file',
				],
			]
		);

        $this->add_control(
            'galleries_rand',
            [
                'label' => __( 'Galleries Ordering', TEXTDOMAIN ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => __( 'Default', TEXTDOMAIN ),
                    'rand' => __( 'Random', TEXTDOMAIN ),
                ],
                'default' => '',
            ]
        );

		$this->add_control(
			'gallery_rand',
			[
				'label' => __( 'Image Ordering', TEXTDOMAIN ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Default', TEXTDOMAIN ),
					'rand' => __( 'Random', TEXTDOMAIN ),
				],
				'default' => '',
			]
		);

		$this->add_control(
			'view',
			[
				'label' => __( 'View', TEXTDOMAIN ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_gallery_images',
			[
				'label' => __( 'Images', TEXTDOMAIN ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'aspect-ratio',
			[
				'label' => __( 'Image Aspect Ratio', TEXTDOMAIN ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 25.00,
						'max' => 400.00,
					],
				],
				'tablet_range' => [
					'%' => [
						'min' => 25.00,
						'max' => 400.00,
					],
				],
				'mobile_range' => [
					'%' => [
						'min' => 25.00,
						'max' => 400.00,
					],
				],
				'default' => [
					'size' => 56.25,
					'unit' => '%',
				],
				'tablet_default' => [
					'size' => 56.25,
					'unit' => '%',
				],
				'mobile_default' => [
					'size' => 56.25,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .rto-image-ratio-container' => 'padding-top: {{SIZE}}%;',
				],
			]
		);

		$this->add_responsive_control(
			'image_spacing',
			[
				'label' => __( 'Spacing', TEXTDOMAIN ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'custom' => __( 'Custom', TEXTDOMAIN ),
				],
				'prefix_class' => 'gallery-spacing-',
				'default' => 'custom',
			]
		);

		$this->add_responsive_control(
			'image_spacing_custom',
			[
				'label' => __( 'Image Spacing', TEXTDOMAIN ),
				'type' => Controls_Manager::SLIDER,
				'show_label' => false,
				'range' => [
                    'px' => [
                        'max' => 100,
                    ],
				],
				'default' => [
					'size' => 10,
				],
				'selectors' => [
                    '{{WRAPPER}} .gallery-image' => 'top: {{SIZE}}{{UNIT}}; bottom: {{SIZE}}{{UNIT}}; left: {{SIZE}}{{UNIT}}; right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .rto-gallery-item-overlay' => 'top: {{SIZE}}{{UNIT}}; bottom: {{SIZE}}{{UNIT}}; left: {{SIZE}}{{UNIT}}; right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'image_spacing' => 'custom',
				],
			]
		);

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow',
                'selector' => '{{WRAPPER}} .rto-gallery-image-ratio-container .gallery-image',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'label' => __( 'Image Border', TEXTDOMAIN ),
                'selector' => '{{WRAPPER}} .gallery-item img',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __( 'Border Radius', TEXTDOMAIN ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .gallery-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .gallery-item .rto-gallery-item-overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_sedcard',
            [
                'label' => __( 'Sedcard', TEXTDOMAIN ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'gallery_type!' => 'wp',
                ],
            ]
        );

        $this->add_control(
            'show_sedcard',
            [
                'label' => __( 'Show Sedcard', TEXTDOMAIN ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __( 'Show', TEXTDOMAIN ),
                'label_off' => __( 'Hide', TEXTDOMAIN ),
                'return_value' => 'yes',
            ]
        );

		$this->add_control(
			'sedcard_key_color',
			[
				'label' => __( 'Key Color', TEXTDOMAIN ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .rto-gallery-sedcard-key' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_sedcard' => 'yes',
				],
			]
		);

		$this->add_control(
			'sedcard_value_color',
			[
				'label' => __( 'Value Color', TEXTDOMAIN ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .rto-gallery-sedcard-value' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_sedcard' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sedcard_key_typography',
				'label' => __( 'Typography Key', TEXTDOMAIN ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .rto-gallery-sedcard-key',
				'condition' => [
					'show_sedcard' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sedcard_value_typography',
				'label' => __( 'Typography Value', TEXTDOMAIN ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .rto-gallery-sedcard-value',
				'condition' => [
					'show_sedcard' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'sedcard-key-align',
			[
				'label'       => __( 'Sedcard Key Align', TEXTDOMAIN ),
				'type' => Controls_Manager::SELECT,
				'default' => 'center',
				'options' => [
					'left'  => __( 'Left', TEXTDOMAIN ),
					'center' => __( 'Center', TEXTDOMAIN ),
					'right' => __( 'Right', TEXTDOMAIN ),
				],
				'selectors' => [ // You can use the selected value in an auto-generated css rule.
					'{{WRAPPER}} .rto-gallery-sedcard-key' => 'text-align: {{VALUE}}',
				],
				'condition' => [
					'show_sedcard' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'sedcard-value-align',
			[
				'label'       => __( 'Sedcard Value Align', TEXTDOMAIN ),
				'type' => Controls_Manager::SELECT,
				'default' => 'center',
				'options' => [
					'left'  => __( 'Left', TEXTDOMAIN ),
					'center' => __( 'Center', TEXTDOMAIN ),
					'right' => __( 'Right', TEXTDOMAIN ),
				],
				'selectors' => [ // You can use the selected value in an auto-generated css rule.
					'{{WRAPPER}} .rto-gallery-sedcard-value' => 'text-align: {{VALUE}}',
				],
				'condition' => [
					'show_sedcard' => 'yes',
				],
			]
		);

		$this->add_control(
			'sedcard_table_color',
			[
				'label' => __( 'Table Color', TEXTDOMAIN ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .rto-gallery-sedcard table' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'show_sedcard' => 'yes',
				],
			]
		);

		$this->add_control(
			'sedcard_odd_color',
			[
				'label' => __( 'Odd Color', TEXTDOMAIN ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .rto-gallery-sedcard table tr:nth-child(odd)' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'show_sedcard' => 'yes',
				],
			]
		);

		$this->add_control(
			'sedcard_even_color',
			[
				'label' => __( 'Even Color', TEXTDOMAIN ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .rto-gallery-sedcard table tr:nth-child(even)' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'show_sedcard' => 'yes',
				],
			]
		);

		$this->add_control(
			'sedcard_margin',
			[
				'label' => __( 'Sedcard Margin', TEXTDOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} table' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'table_border',
				'label' => __( 'Table Border', TEXTDOMAIN ),
				'selector' => '{{WRAPPER}} .rto-gallery-sedcard table, {{WRAPPER}} .rto-gallery-sedcard td, {{WRAPPER}} .rto-gallery-sedcard th',
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'section_gallery_icon',
            [
                'label' => __( 'Icon', TEXTDOMAIN ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

		$this->add_control(
			'icon',
			[
				'label' => __( 'Icon', TEXTDOMAIN ),
				'type' => Controls_Manager::ICON,
				'label_block' => true,
				'default' => 'fa fa-image',
			]
		);

        $this->add_responsive_control(
            'icon-size',
            [
                'label' => __( 'Icon Size', TEXTDOMAIN ),
                'type' => Controls_Manager::SLIDER,
                'show_label' => false,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                    'em' => [
                        'max' => 12,
                    ],
                    'rem' => [
                        'max' => 12,
                    ],
                ],
                'default' => [
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __( 'Icon Color', TEXTDOMAIN ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon-align',
            [
                'label'       => __( 'Icon Align', TEXTDOMAIN ),
                'type' => Controls_Manager::SELECT,
                'default' => 'center',
                'options' => [
                    'left'  => __( 'Left', TEXTDOMAIN ),
                    'center' => __( 'Center', TEXTDOMAIN ),
                    'right' => __( 'Right', TEXTDOMAIN ),
                ],
                'selectors' => [ // You can use the selected value in an auto-generated css rule.
                    '{{WRAPPER}} .rto-gallery-icon-wrapper' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon-justify',
            [
                'label'       => __( 'Icon Justify', TEXTDOMAIN ),
                'type' => Controls_Manager::SELECT,
                'default' => 'center',
                'options' => [
                    'flex-start'  => __( 'Top', TEXTDOMAIN ),
                    'center' => __( 'Center', TEXTDOMAIN ),
                    'flex-end' => __( 'Bottom', TEXTDOMAIN ),
                ],
                'selectors' => [ // You can use the selected value in an auto-generated css rule.
                    '{{WRAPPER}} .rto-gallery-icon-wrapper' => 'justify-content: {{VALUE}}; -webkit-box-pack: {{VALUE}}; -webkit-justify-content: {{VALUE}}; -ms-flex-pack: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon-padding',
            [
                'label' => __( 'Icon Padding' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default' => [
                    'top' => 15,
                    'right' => 15,
                    'bottom' => 15,
                    'left' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .rto-gallery-icon-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->end_controls_section();

        $this->start_controls_section(
            'section_back_button',
            [
                'label' => __( 'Back button', TEXTDOMAIN ),
                'condition' => [
                    'gallery_style' => 'modal',
                ],
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __( 'Button Text', TEXTDOMAIN ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Back to Overview', TEXTDOMAIN ),
                'placeholder' => __( 'Back to Overview', TEXTDOMAIN ),
            ]
        );

        $this->add_control(
            'button_size',
            [
                'label' => __( 'Size', TEXTDOMAIN ),
                'type' => Controls_Manager::SELECT,
                'default' => 'sm',
                'options' => self::get_button_sizes(),
            ]
        );

        $this->add_control(
            'button_icon',
            [
                'label' => __( 'Icon', TEXTDOMAIN ),
                'type' => Controls_Manager::ICON,
                'label_block' => true,
                'default' => '',
            ]
        );

        $this->add_control(
            'button_icon_align',
            [
                'label' => __( 'Icon Position', TEXTDOMAIN ),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __( 'Before', TEXTDOMAIN ),
                    'right' => __( 'After', TEXTDOMAIN ),
                ],
                'condition' => [
                    'icon!' => '',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => __( 'Normal', TEXTDOMAIN ),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => __( 'Text Color', TEXTDOMAIN ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_color',
            [
                'label' => __( 'Background Color', TEXTDOMAIN ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button, 
                    {{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .elementor-button',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => __( 'Hover', TEXTDOMAIN ),
            ]
        );

        $this->add_control(
            'button_hover_color',
            [
                'label' => __( 'Text Color', TEXTDOMAIN ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_hover_color',
            [
                'label' => __( 'Background Color', TEXTDOMAIN ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_animation',
            [
                'label' => __( 'Animation', TEXTDOMAIN ),
                'type' => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->add_control(
            'button_transition_duration',
            [
                'label' => __( 'Button Transition Duration', TEXTDOMAIN ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'ms' ],
                'range' => [
                    'ms' => [
                        'min' => 100,
                        'max' => 2000,
                    ],
                ],
                'default' => [
                    'size' => 600,
                    'unit' => 'ms',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button,
                    {{WRAPPER}} a.elementor-button' => 'transition: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_hover_box_shadow',
                'selector' => '{{WRAPPER}} .elementor-button:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'border_radius',
            [
                'label' => __( 'Border Radius', TEXTDOMAIN ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_margin',
            [
                'label' => __( 'Button Margin', TEXTDOMAIN ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

		$this->start_controls_section(
			'section_hover',
			[
				'label' => __( 'Hover', TEXTDOMAIN ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
            'hover_color',
            [
                'label' => __( 'Hover Color', TEXTDOMAIN ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .gallery-item .rto-gallery-item-overlay' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'transition_duration',
            [
                'label' => __( 'Transition Duration', TEXTDOMAIN ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'ms' ],
                'range' => [
                    'ms' => [
                        'min' => 100,
                        'max' => 2000,
                    ],
                ],
                'default' => [
                    'size' => 600,
                    'unit' => 'ms',
                ],
                'selectors' => [
                    '{{WRAPPER}} .gallery-image,
                    {{WRAPPER}} .rto-gallery-item-overlay,
                    {{WRAPPER}} .rto-gallery-icon-overlay'=> 'transition: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_hover_shadow',
                'selector' => '{{WRAPPER}} .rto-gallery-image-ratio-container:hover .gallery-image',
            ]
        );

		$this->add_control(
			'gallery_display_caption',
			[
				'label' => __( 'Display', TEXTDOMAIN ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => __( 'Show', TEXTDOMAIN ),
					'none' => __( 'Hide', TEXTDOMAIN ),
				],
				'selectors' => [
					'{{WRAPPER}} .gallery-item .caption' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', TEXTDOMAIN ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .caption' => 'color: {{VALUE}};',
				],
				'condition' => [
					'gallery_display_caption' => '',
				],
			]
		);

        $this->add_responsive_control(
            'caption-align',
            [
                'label'       => __( 'Caption Align', TEXTDOMAIN ),
                'type' => Controls_Manager::SELECT,
                'default' => 'center',
                'options' => [
                    'left'  => __( 'Left', TEXTDOMAIN ),
                    'center' => __( 'Center', TEXTDOMAIN ),
                    'right' => __( 'Right', TEXTDOMAIN ),
                ],
                'selectors' => [ // You can use the selected value in an auto-generated css rule.
                    '{{WRAPPER}} .rto-gallery-item-overlay' => 'text-align: {{VALUE}}',
                ],
                'condition' => [
                    'gallery_display_caption' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'caption-justify',
            [
                'label'       => __( 'Caption Justify', TEXTDOMAIN ),
                'type' => Controls_Manager::SELECT,
                'default' => 'center',
                'options' => [
                    'flex-start'  => __( 'Top', TEXTDOMAIN ),
                    'center' => __( 'Center', TEXTDOMAIN ),
                    'flex-end' => __( 'Bottom', TEXTDOMAIN ),
                ],
                'selectors' => [ // You can use the selected value in an auto-generated css rule.
                    '{{WRAPPER}} .rto-gallery-item-overlay' => 'justify-content: {{VALUE}}; -webkit-box-pack: {{VALUE}}; -webkit-justify-content: {{VALUE}}; -ms-flex-pack: {{VALUE}};',
                ],
                'condition' => [
                    'gallery_display_caption' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'caption-padding',
            [
                'label' => __( 'Caption Padding' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default' => [
                    'top' => 15,
                    'right' => 15,
                    'bottom' => 15,
                    'left' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .rto-gallery-item-overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'gallery_display_caption' => '',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'label' => __( 'Typography', TEXTDOMAIN ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .caption',
				'condition' => [
					'gallery_display_caption' => '',
				],
			]
		);

		$this->end_controls_section();
	}

    /**
     * Init galleries.
     */
    private function initGalleries() {

        $settings = $this->get_settings();

        switch( $settings['gallery_type'] ) {

            case 'wp':
                $this->initWordpressGalleries();
                break;

            case 'gesamt':
                $this->initGesamtGalleries();
                break;

            case 'both':
                if ( $settings['gallery_sort'] == 'wp' ) {
                    $this->initWordpressGalleries();
                    $this->initGesamtGalleries();
                }
                else {
                    $this->initGesamtGalleries();
                    $this->initWordpressGalleries();
                }
                break;

            default:
                break;

        }

    }

    /**
     * Init Wordpress galleries.
     */
    private function initWordpressGalleries() {

        $settings = $this->get_settings();

        // TODO: implement 'thumbnail_size' setting usage

        foreach ( $settings['gallery_list'] as $galleryItem ) {

            $gallery = new Gallery( $galleryItem['gallery_title'] );
            $this->album->addGallery( $gallery );

            // Sort images by settings
            foreach ( $galleryItem['wp_gallery'] as $image ) {

            	$caption = ($galleryItem['custom_caption'] == 'yes' && $settings['first_image'] == 'yes')
					? $galleryItem['custom_caption_text']
					: wp_get_attachment_caption( $image['id'] );

            	$gallery->addImage( new Image( $image['url'], $caption, $settings['gallery_link'] ) );

            }

        }

    }

    /**
     * Init Gesamt galleries.
     */
    function initGesamtGalleries(){

        require_once( __GESAMT__ . '/get-functions.php' );
        require_once( __GESAMT__ . '/extensions/quotesmart.inc.php' );
        require_once( __GESAMT__ . '/class/profil.class.php' );
        require_once( __GESAMT__ . '/class/auftrag.class.php' );
        require_once( __GESAMT__ . '/class/profiles.class.php' );
        require_once( __GESAMT__ . '/init-pdo.php' );

        $settings = $this->get_settings();

        $gesGalleries = getGesamtGallery( [
            'rubid' => $settings['gesamt_rubid'],
            'kid' => $settings['gesamt_kid'],
            'pid' => $settings['gesamt_pid'],
        ] );

        foreach ( $gesGalleries as $item ) {

            $thumbSrc = $item['path'] . $item['thumb'];
            $thumb = new Image( $thumbSrc, '', '' );

            $gallery = new Gallery( $item['name'], $thumb, $item['profildata']['Merkmale'] );
            $this->album->addGallery( $gallery );

            // Sort images by settings
            foreach ( $item['pics'] as $image ) {

                $caption = (!empty($item['zusatztext']))? $item['zusatztext'] : $item['name'];
                $src = $item['path'] . $image['big'];
                $gallery->addImage( new Image( $src, $caption, $settings['gallery_link'] ) );

            }

        }

    }

	/**
	 * Render image gallery widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {

	    $settings = $this->get_settings();

        $this->album = new Album( $this->get_id() );
        $this->initGalleries();

	    $renderer = new Renderer( $settings );
	    $this->album->accept( $renderer );

	}

}
