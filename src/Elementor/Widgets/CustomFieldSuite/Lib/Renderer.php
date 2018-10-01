<?php
/**
 * Renderer.php
 * Depends on Custom Field Suite Plugin
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Elementor\Widgets\CustomFieldSuite
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Elementor/Widgets/CustomFieldSuite/WidgetCustomFieldSuite.html
 */

namespace ElebeeCore\Elementor\Widgets\CustomFieldSuite\Lib;

use Elementor\Controls_Stack;

class Renderer extends Controls_Stack {

	/**
	 * Retrieve image widget name.
	 *
	 * @since 0.1.0
	 *
	 * @return string Widget name.
	 */
	public function get_name(): string {

		return 'custom_field_suite_renderer';

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

		echo '<' . $tags[ 'table' ] . ' class="cfs-table table-' . $tableClass .'">';
		if ( isset( $tab[ 0 ][ 'type' ] ) && 'loop' === $tab[ 0 ][ 'type' ] ) {
			$this->renderLoop( $tab, $tags, $headingAlign, $settings );
		}
		else {
			$this->renderTab( $tab, $tags, $headingAlign, $settings );
		}
		echo '</' . $tags[ 'table' ] . '>';
		
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
				$templateName = 'youtube';
			}
			else {
				$templateName = 'tab';
			}

			$template = new Template( __DIR__ . '/partials/' . $templateName .'.php', $args );

			echo $template->getRendered();
		}

	}
	
	protected function renderButton( $settings, $countValues ) {
		if ( $settings[ 'cfs_max_entries' ] < $countValues && !empty( $settings['cfs_more_button'] ) ) {
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
				<a <?php echo $this->get_render_attribute_string( 'button' ); ?>>
					<?php $this->render_text(); ?>
				</a>
			</div>
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