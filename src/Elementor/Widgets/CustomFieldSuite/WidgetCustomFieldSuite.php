<?php
/**
 * WidgetCustomFieldSuite.php
 * Depends on Custom Field Suite Plugin
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Elementor\Widgets\CustomFieldSuite
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Elementor/Widgets/CustomFieldSuite/WidgetCustomFieldSuite.html
 */

/* ToDo: translation

*/
namespace ElebeeCore\Elementor\Widgets\CustomFieldSuite;

use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Button;
use Elementor\Scheme_Color;
use Elementor\Controls_Manager;
use ElebeeCore\Elementor\Widgets\WidgetBase;
use ElebeeCore\Lib\Util\Template;
use ElebeeCore\Lib\Elebee;

defined( 'ABSPATH' ) || exit;


/**
 * Class WidgetCustomFieldSuite
 *
 * @since   0.1.0
 *
 * @package ElebeeCoreElementor\Elementor\Widgets\CustomFieldSuite
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Elementor/Widgets/CustomFieldSuite/WidgetCustomFieldSuite.html
 */
class WidgetCustomFieldSuite extends WidgetBase {

	protected $tableTag = [
		'table' => 'table',
		'th' => 'th',
		'tr' => 'tr',
		'td' => 'td',
	];

	protected $divTag = [
		'table' => 'div',
		'th' => 'div',
		'tr' => 'div',
		'td' => 'div',
	];

	/**
	 * WidgetCustomFieldSuite constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param array $data
	 * @param array $args
	 * @throws
	 */
	public function __construct( array $data = [], array $args = null ) {

		parent::__construct( $data, $args );

	}

	/**
	 * @since 0.1.0
	 */
	public function enqueueStyles() {

		wp_enqueue_style( $this->get_name(), get_stylesheet_directory_uri() . '/vendor/rto-websites/elebee-core/src/Elementor/Widgets/CustomFieldSuite/assets/css/custom-field-suite.css', [], Elebee::VERSION, 'all' );

	}

	/**
	 * @since 0.1.0
	 */
	public function enqueueScripts() {
		// TODO: Implement enqueueScripts() method.
	}

	/**
	 * Retrieve image widget name.
	 *
	 * @since 0.1.0
	 *
	 * @return string Widget name.
	 */
	public function get_name(): string {

		return 'custom_field_suite';

	}

	/**
	 * Retrieve image widget title.
	 *
	 * @since 0.1.0
	 *
	 * @return string Widget title.
	 */
	public function get_title(): string {

		return __( 'Custom Field Suite', 'elebee' );

	}

	/**
	 * Retrieve image widget icon.
	 *
	 * @since 0.1.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon(): string {

		return 'fa fa-align-justify';

	}

	/**
	 * Retrieve the list of categories the image gallery widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @since 0.1.0
	 */
	public function get_categories() {

		return [ 'rto-elements' ];

	}

	/**
	 * Register "Custom Field Suite" widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'section_custom_field_suite',
			[
				'label' => __( 'Custom Field Suite', 'elebee' ),
			]
		);

		$this->add_control(
			'custom_field_suite',
			[
				'label' => __( 'Groups', 'elebee' ),
				'type'  => Controls_Manager::SELECT,
				'options' => $this->getGroups(),
			]
		);

		$this->add_control(
			'cfs_settings',
			[
				'label' => __( 'Settings', 'elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'cfs_title',
			[
				'label' => __( 'Title', 'elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Show', 'elementor' ),
				'label_off'    => __( 'Hide', 'elementor' ),
			]
		);

		$this->add_control(
			'cfs_title_size',
			[
				'label' => __( 'Title HTML Tag', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h3',
			]
		);

		$this->add_control(
			'title_align',
			[
				'label' => __( 'Alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'condition'    => [
					'cfs_title' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography_title',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .cfs-tab-title',
				'responsive' => 'yes',
			]
		);

		$this->add_control(
			'divider_title_heading',
			[
				'label' => __( 'Heading', 'elementor' ),
				'type'         => Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'cfs_heading',
			[
				'label' => __( 'Heading', 'elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Show', 'elementor' ),
				'label_off'    => __( 'Hide', 'elementor' ),
			]
		);

		$this->add_control(
			'heading_align',
			[
				'label' => __( 'Alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'condition'    => [
					'cfs_heading' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography_heading',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .cfs-table-th',
				'responsive' => 'yes',
			]
		);

		$this->add_control(
			'divider_heading_output',
			[
				'label' => __( 'Heading', 'elementor' ),
				'type'         => Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'output_format',
			[
				'label' => __( 'Output Format', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'table' => [
						'title' => __( 'Table', 'elementor' ),
						'icon' => 'fa fa-table',
					],
					'div' => [
						'title' => __( 'DIV', 'elementor' ),
						'icon' => 'fa fa-th-large',
					],
				],
				'default' => 'table'
			]
		);

		$this->add_control(
			'cfs_fields_options',
			[
				'label' => __( 'Fields', 'elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'cfs_empty_values',
			[
				'label' => __( 'Fields with empty values', 'elebee' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Show', 'elementor' ),
				'label_off'    => __( 'Hide', 'elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography_values',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .cfs-table-td',
				'responsive' => 'yes',
			]
		);
		
		$this->add_control(
			'cfs_max_entries',
			[
				'label' => __( 'Max Entries', 'elementor' ),
				'type' => Controls_Manager::NUMBER,
				'description' => __( 'Limit number of lines', 'elementor' ),
			]
		);
		
		$this->add_control(
			'cfs_more_button',
			[
				'label' => __( 'More button', 'elebee' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Show', 'elementor' ),
				'label_off'    => __( 'Hide', 'elementor' ),
			]
		);

		$this->add_control(
			'cfs_more_button_text',
			[
				'label' => __( 'Text', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'more', 'elementor' ),
				'placeholder' => __( 'more', 'elementor' ),
				'condition'    => [
					'cfs_more_button' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'cfs_more_button_align',
			[
				'label' => __( 'Alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'condition'    => [
					'cfs_more_button' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button-wrapper' => 'text-align: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'cfs_more_button_size',
			[
				'label' => __( 'Size', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => Widget_Button::get_button_sizes(),
				'style_transfer' => true,
				'condition'    => [
					'cfs_more_button' => 'yes',
				],
			]
		);

		$this->add_control(
			'cfs_more_button_icon',
			[
				'label' => __( 'Icon', 'elementor' ),
				'type' => Controls_Manager::ICON,
				'label_block' => true,
				'default' => '',
				'condition'    => [
					'cfs_more_button' => 'yes',
				],
			]
		);

		$this->add_control(
			'cfs_more_button_icon_align',
			[
				'label' => __( 'Icon Position', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => __( 'Before', 'elementor' ),
					'right' => __( 'After', 'elementor' ),
				],
				'condition' => [
					'cfs_more_button_icon!' => '',
				],
			]
		);

		$this->add_control(
			'cfs_more_button_icon_indent',
			[
				'label' => __( 'Icon Spacing', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'condition' => [
					'cfs_more_button_icon!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'divider_empty_values',
			[
				'label' => '',
				'type'         => Controls_Manager::DIVIDER,
			]
		);

		$fieldsArray = $this->getFields();
		foreach ( $fieldsArray as $tabName => $fields ) {
			foreach ( $fields as $key => $field ) {
				if ( 'loop' === $field[ 'type' ] ) {
					continue;
				}

				$controlName = $tabName . '_' . $field['name'];
				$this->add_control(
					$controlName,
					[
						'label'        => __( $field['label'], 'elebee' ),
						'type'         => Controls_Manager::SWITCHER,
						'default'      => 'yes',
						'label_on'     => __( 'Show', 'elementor' ),
						'label_off'    => __( 'Hide', 'elementor' ),
						'condition'    => [
							'custom_field_suite' => $tabName,
						],
					]
				);
			}
		}

		$this->end_controls_section();
		
		$this->start_controls_section(
			'cfs_more_button_section_style',
			[
				'label' => __( 'Button', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'cfs_more_button_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button',
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'cfs_more_button_tab_normal',
			[
				'label' => __( 'Normal', 'elementor' ),
			]
		);

		$this->add_control(
			'cfs_more_button_text_color',
			[
				'label' => __( 'Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'cfs_more_button_background_color',
			[
				'label' => __( 'Background Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'cfs_more_button_tab_hover',
			[
				'label' => __( 'Hover', 'elementor' ),
			]
		);

		$this->add_control(
			'cfs_more_button_hover_color',
			[
				'label' => __( 'Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'cfs_more_button_background_hover_color',
			[
				'label' => __( 'Background Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'cfs_more_button_hover_border_color',
			[
				'label' => __( 'Border Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'cfs_more_button_hover_animation',
			[
				'label' => __( 'Hover Animation', 'elementor' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'cfs_more_button_border',
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .elementor-button',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'cfs_more_button_border_radius',
			[
				'label' => __( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		$this->add_responsive_control(
			'cfs_more_button_text_padding',
			[
				'label' => __( 'Padding', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Groups the settings by tab name and assigns the label.
	 * All settings without tab are in the group 'default'.
	 *
	 * @since 0.1.0
	 *
	 * @return array Associative array with tab names and labels.
	 */
	protected function getGroups() {

		$options = [];
		$fields = CFS()->find_fields();

		foreach ( $fields as $key => $field ) {
			if ( !isset( $field[ 'type' ] ) ) {
				continue;
			}

			if ( 'tab' === $field[ 'type' ] ) {
				$options[ $field[ 'name' ] ] = $field[ 'label' ];
			}
			else if ( empty( $options ) ) {
				$options[ 'default' ] = __( 'Default'. 'elementor' );
			}
		}

		return $options;
	}

	/**
	 * Groups the fields by tab name and assigns every tab the label. There is at least tab 'default'.
	 * If optional parameter is set and created array contains the associated key,
	 * then return named fields. Else return empty array.
	 *
	 * @since 0.1.0
	 *
	 * @param string $tabName
	 *
	 * @return array Associative array with fields settings or empty.
	 */
	protected function getFields( $tabName = '' ) {

		$tabs = [];
		$currentTab = 'default';
		$fields = CFS()->find_fields();

		foreach ( $fields as $key => $field ) {
			if ( !isset( $field[ 'type' ] ) ) {
				continue;
			}

			if ( 'tab' === $field[ 'type' ] ) {
				$currentTab = $field[ 'name' ];
			}
			else {
				$tabs[ $currentTab ][] = $field;
			}
		}

		if ( !empty( $tabName ) ) {
			return isset( $tabs[ $tabName ] ) ?  $tabs[ $tabName ] : [];
		}

		return $tabs;
	}

	/**
	 * Render custom field suite widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings();
		$cfsName = $settings['custom_field_suite'];
		$tableClass = str_replace( '_', '-', $cfsName );

		$tab = $this->getFields( $cfsName );
		$tags = 'div' === $settings[ 'output_format' ] ? $this->divTag : $this->tableTag;
		$headingAlign = ! empty( $settings['heading_align'] ) ? 'elementor-align-' . $settings['heading_align'] : '';
		
		# get values of the loop
		$countValues = $this->hasValues( $tab );
		if ( false === $countValues ) {
			return;
		}

		if ( !empty( $settings[ 'cfs_title' ] ) ) {
			$titles = $this->getGroups();
			$title = isset( $titles[ $cfsName ] ) ? $titles[ $cfsName ] : '';
			$tag = $settings[ 'cfs_title_size' ];

			$template = new Template( __DIR__ . '/partials/table-title.php', [
				'tag'          => $tag,
				'title'        => $title,
				'titleAlign' => ! empty( $settings['title_align'] ) ? 'elementor-align-' . $settings['title_align'] : '',
			] );

			echo $template->getRendered();
		}

		if ( $cfsName !== 'showreel' ) {
			echo '<' . $tags['table'] . ' class="cfs-table table-' . $tableClass . '">';
		}
		if ( isset( $tab[ 0 ][ 'type' ] ) && 'loop' === $tab[ 0 ][ 'type' ] ) {
			$this->renderLoop( $tab, $tags, $headingAlign, $settings );
		}
		else {
			$this->renderTab( $tab, $tags, $headingAlign, $settings );
		}
		if ( $cfsName !== 'showreel' ) {
			echo '</' . $tags['table'] . '>';
		}
		
		$this->renderButton( $settings, $countValues );
	}

	/**
	 * Render loop
	 *
	 * @since 0.1.0
	 *
	 * @param $tab array
	 * @param $tags array
	 * @param $headingAlign string Elementor CSS-Class.
	 * @param $settings array
	 */
	protected function renderLoop( $tab, $tags, $headingAlign, $settings ) {

		$cfsName = $settings['custom_field_suite'];

		# get values of the loop
		$loopName = $tab[ 0 ][ 'name' ];
		$valuesArray = CFS()->get( $loopName );

		if ( empty( $valuesArray ) ) {
			return;
		}

		array_shift( $tab );
		$valuesRemaining = [];
		$maxEntries = (int)$settings[ 'cfs_max_entries' ];
		$showHeading = ! empty( $settings[ 'cfs_heading' ] );

		if ( $showHeading ) {
			# loop header
			echo '<' . $tags[ 'tr' ] .' class="cfs-table-tr">';
			foreach ( $tab as $key => $tabElement ) {
				$hideField = empty( $settings[ $cfsName . '_' . $tabElement['name'] ] );
				if ( $hideField ) {
					continue;
				}

				$template = new Template( __DIR__ . '/partials/table-heading.php', [
					'tags'         => $tags,
					'content'      => $tabElement['label'],
					'headingAlign' => $headingAlign,
				] );

				echo $template->getRendered();
			}
			echo '</' . $tags[ 'tr' ] .'>';
		}

		
		if ( !empty( $maxEntries ) && is_int( $maxEntries ) ) {
			if (  $settings[ 'cfs_more_button' ] === 'yes' ) {
				$length = count( $valuesArray ) - $maxEntries;
				$valuesRemaining = array_slice( $valuesArray, $maxEntries, $length, true );
			}
			
			$valuesArray = array_slice( $valuesArray, 0, $maxEntries, true );
		}

		# loop body
		foreach ( $valuesArray as $values ) {
			$template = new Template( __DIR__ . '/partials/table-body.php', [
				'tags'     => $tags,
				'values'   => $values,
				'cfsName'  => $cfsName,
				'settings' => $settings,
			] );

			echo $template->getRendered();
		}
		
		if ( !empty( $valuesRemaining ) ) {
			# loop body
			$tags['class'] = 'elementor-hidden';
			foreach ( $valuesRemaining as $values ) {
				$template = new Template( __DIR__ . '/partials/table-body.php', [
					'tags'     => $tags,
					'values'   => $values,
					'cfsName'  => $cfsName,
					'settings' => $settings,
				] );

				echo $template->getRendered();
			}
		}
	}

	/**
	 * Render tabs.
	 *
	 * @since 0.1.0
	 *
	 * @param $tab array
	 * @param $tags array
	 * @param $headingAlign string Elementor CSS-Class.
	 * @param $settings array
	 */
	protected function renderTab( $tab, $tags, $headingAlign, $settings ) {

		$cfsName = $settings[ 'custom_field_suite' ];

		$showHeading = !empty( $settings[ 'cfs_heading' ] );

		foreach ( $tab as $key => $field ) {
			$heading = $field['label'];
			$content  = CFS()->get( $field['name'] );
			
			$hideField = empty( $settings[ $cfsName . '_' . $field['name'] ] );
			$hideEmptyFields = empty( $settings[ 'cfs_empty_values' ] ) && empty( $content );
			if ( $hideField || $hideEmptyFields ) {
				continue;
			}
			
			$args = [
				'tags'         => $tags,
				'content'      => $content,
				'heading'      => $heading,
				'showHeading'  => $showHeading,
				'headingAlign' => $headingAlign,
			];
			
			if ( stripos( $field[ 'name' ], 'video' ) !== false ) {
				$templateName = 'video';
			}
			else if ( stripos( $field[ 'name' ], 'youtube' ) !== false ) {
				$args[ 'videoUrl' ] = CFS()->get( $field['name'] );
				if ( empty( $args[ 'videoUrl' ] ) ) {
					continue;
				}
				$templateName = 'youtube';
			}
			else if ( stripos( $field[ 'name' ], 'vimeo' ) !== false ) {
				$args[ 'videoUrl' ] = CFS()->get( $field['name'] );
				if ( empty( $args[ 'videoUrl' ] ) ) {
				    continue;
                }
				$templateName = 'vimeo';
			}
			else {
				$templateName = 'tab';
			}

			$template = new Template( __DIR__ . '/partials/' . $templateName .'.php', $args );

			echo $template->getRendered();
		}

	}
	
	protected function renderButton( $settings, $countValues ) {
		if ( (int)$settings[ 'cfs_max_entries' ] < $countValues && !empty( $settings['cfs_more_button'] ) ) {
			$cfsName = $settings['custom_field_suite'];
			$tableClass = str_replace( '_', '-', $cfsName );
		
			$this->add_render_attribute( 'wrapper', 'class', 'elementor-button-wrapper' );
			$this->add_render_attribute( 'button', 'class', 'elementor-button' );
			$this->add_render_attribute( 'button', 'role', 'button' );

			if ( ! empty( $settings['cfs_more_button_size'] ) ) {
				$this->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['cfs_more_button_size'] );
			}

			if ( $settings['cfs_more_button_hover_animation'] ) {
				$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['cfs_more_button_hover_animation'] );
			}

			?>
			<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
				<a <?php echo $this->get_render_attribute_string( 'button' ); ?> data-table-class="<?php echo $tableClass; ?>">
					<?php $this->render_text(); ?>
				</a>
			</div>
			<script>
				jQuery( '.elementor-button' ).on( 'click', function() {
					var data = jQuery( this ).data( 'table-class' );
					if ( typeof( data ) !== undefined && data.length > 0 ) {
						jQuery( '.table-' + data + ' .cfs-table-tr').removeClass( 'elementor-hidden');
					}
					jQuery( this ).remove();
				});
			</script>
			<?php
		}
	}
	
	protected function hasValues( $tab ) {
		$values = false;
		foreach ( $tab as $key => $field ) {
			$fieldValues = CFS()->get( $field['name'] );
			if ( !empty( $fieldValues ) ) {
				$values = count( $fieldValues );
				break;
			}
		}
		
		return $values;
	}
	

	/**
	 * Render button widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _content_template() {
		?>
		<#
		view.addRenderAttribute( 'text', 'class', 'elementor-button-text' );

		view.addInlineEditingAttributes( 'text', 'none' );
		#>
		<div class="elementor-button-wrapper">
			<a class="elementor-button elementor-size-{{ settings.cfs_more_button_size }} elementor-animation-{{ settings.cfs_more_button_hover_animation }}" href="#" role="button">
				<span class="elementor-button-content-wrapper">
					<# if ( settings.cfs_more_button_icon ) { #>
					<span class="elementor-button-icon elementor-align-icon-{{ settings.cfs_more_button_icon_align }}">
						<i class="{{ settings.cfs_more_button_icon }}" aria-hidden="true"></i>
					</span>
					<# } #>
					<span {{{ view.getRenderAttributeString( 'text' ) }}}>{{{ settings.cfs_more_button_text }}}</span>
				</span>
			</a>
		</div>
		<?php
	}

	/**
	 * Render button text.
	 *
	 * Render button widget text.
	 *
	 * @since 1.5.0
	 * @access protected
	 */
	protected function render_text() {
		$settings = $this->get_settings();

		$this->add_render_attribute( [
			'content-wrapper' => [
				'class' => 'elementor-button-content-wrapper',
			],
			'icon-align' => [
				'class' => [
					'elementor-button-icon',
					'elementor-align-icon-' . $settings['cfs_more_button_icon_align'],
				],
			],
			'text' => [
				'class' => 'elementor-button-text',
			],
		] );

		$this->add_inline_editing_attributes( 'text', 'none' );
		?>
		<span <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
			<?php if ( ! empty( $settings['cfs_more_button_icon'] ) ) : ?>
			<span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
				<i class="<?php echo esc_attr( $settings['cfs_more_button_icon'] ); ?>" aria-hidden="true"></i>
			</span>
			<?php endif; ?>
			<span <?php echo $this->get_render_attribute_string( 'text' ); ?>><?php echo $settings['cfs_more_button_text']; ?></span>
		</span>
		<?php
	}
}
