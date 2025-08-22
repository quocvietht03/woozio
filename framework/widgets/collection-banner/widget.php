<?php

namespace WoozioElementorWidgets\Widgets\CollectionBanner;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Utils;

class Widget_CollectionBanner extends Widget_Base
{

	public function get_name()
	{
		return 'bt-collection-banner';
	}

	public function get_title()
	{
		return __('Collection Banner', 'woozio');
	}

	public function get_icon()
	{
		return 'eicon-posts-ticker';
	}

	public function get_categories()
	{
		return ['woozio'];
	}

	public function get_script_depends()
	{
		return ['elementor-widgets'];
	}

	protected function register_content_section_controls()
	{
		$this->start_controls_section(
			'section_content',
			[
				'label' => __('Content', 'woozio'),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'image',
			[
				'label' => __('Image', 'woozio'),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'title',
			[
				'label' => __('Title', 'woozio'),
				'type' => Controls_Manager::TEXT,
				'default' => __('Collection Title', 'woozio'),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'description',
			[
				'label' => __('Description', 'woozio'),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __('Collection description text', 'woozio'),
				'rows' => 3,
			]
		);

		$repeater->add_control(
			'button_text',
			[
				'label' => __('Button Text', 'woozio'),
				'type' => Controls_Manager::TEXT,
				'default' => __('VIEW COLLECTION', 'woozio'),
			]
		);

		$repeater->add_control(
			'button_link',
			[
				'label' => __('Button Link', 'woozio'),
				'type' => Controls_Manager::URL,
				'placeholder' => __('https://your-link.com', 'woozio'),
				'default' => [
					'url' => '#',
				],
			]
		);

		$repeater->add_control(
			'is_default_active',
			[
				'label' => __('Default Active', 'woozio'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'woozio'),
				'label_off' => __('No', 'woozio'),
				'return_value' => 'yes',
				'default' => '',
				'description' => __('Make this item expanded by default', 'woozio'),
			]
		);

		$this->add_control(
			'collection_items',
			[
				'label' => __('Collection Items', 'woozio'),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'title' => __('Urban Grace', 'woozio'),
						'description' => __('Modern looks for city life.', 'woozio'),
						'button_text' => __('VIEW COLLECTION', 'woozio'),
					],
					[
						'title' => __('Weekend Mood', 'woozio'),
						'description' => __('Casual wear, effortless vibe.', 'woozio'),
						'button_text' => __('VIEW COLLECTION', 'woozio'),
						'is_default_active' => 'yes',
					],
					[
						'title' => __('Soft Edge', 'woozio'),
						'description' => __('Minimal with a bold twist.', 'woozio'),
						'button_text' => __('VIEW COLLECTION', 'woozio'),
					],
					[
						'title' => __('New Classics', 'woozio'),
						'description' => __('Timeless style, redefined fresh.', 'woozio'),
						'button_text' => __('VIEW COLLECTION', 'woozio'),
					],
				],
				'title_field' => '{{{ title }}}',
                'min_items' => 2,
                'max_items' => 4,
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_section_controls()
	{
		$this->start_controls_section(
			'section_style_general',
			[
				'label' => esc_html__('General', 'woozio'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'items_height',
			[
				'label' => __('Items Height', 'woozio'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'vh'],
				'range' => [
					'px' => [
						'min' => 200,
						'max' => 800,
						'step' => 10,
					],
					'vh' => [
						'min' => 20,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 600,
				],
				'selectors' => [
					'{{WRAPPER}} .bt-collection-banner .collection-item' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'items_gap',
			[
				'label' => __('Items Gap', 'woozio'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 30,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .bt-collection-banner' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__('Content', 'woozio'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_heading',
			[
				'label' => __('Title', 'woozio'),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __('Typography', 'woozio'),
				'selector' => '{{WRAPPER}} .bt-collection-banner .collection-content h3',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __('Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-collection-banner .collection-content h3' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'description_heading',
			[
				'label' => __('Description', 'woozio'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'label' => __('Typography', 'woozio'),
				'selector' => '{{WRAPPER}} .bt-collection-banner .collection-content p',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __('Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-collection-banner .collection-content p' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_heading',
			[
				'label' => __('Button', 'woozio'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'label' => __('Typography', 'woozio'),
				'selector' => '{{WRAPPER}} .bt-collection-banner .collection-button',
			]
		);

		$this->add_control(
			'button_color',
			[
				'label' => __('Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-collection-banner .collection-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background',
			[
				'label' => __('Background', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-collection-banner .collection-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => __('Hover Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-collection-banner .collection-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_background',
			[
				'label' => __('Hover Background', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-collection-banner .collection-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_controls()
	{
		$this->register_content_section_controls();
		$this->register_style_section_controls();
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();
		$collection_items = $settings['collection_items'];

		if (empty($collection_items)) {
			return;
		}
		
		// Get total number of items
		$total_items = count($collection_items);
?>
		<div class="bt-elwg-collection-banner--default">
			<div class="bt-collection-banner bt-items-<?php echo esc_attr($total_items); ?>" data-total-items="<?php echo esc_attr($total_items); ?>">
				<?php 
				$has_active_item = false; // Track if we already have an active item
				foreach ($collection_items as $index => $item) :
					$target = $item['button_link']['is_external'] ? ' target="_blank"' : '';
					$nofollow = $item['button_link']['nofollow'] ? ' rel="nofollow"' : '';
					
					// Only set active if this is the first item with is_default_active = yes
					$is_active = '';
					if ($item['is_default_active'] === 'yes' && !$has_active_item) {
						$is_active = 'active';
						$has_active_item = true;
					}
				?>
					<div class="collection-item <?php echo esc_attr($is_active); ?>" data-index="<?php echo esc_attr($index); ?>">
						<div class="collection-image">
							<?php if (!empty($item['image']['url'])) : ?>
								<img src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php echo esc_attr($item['title']); ?>">
							<?php endif; ?>
						</div>
						<div class="collection-content">
							<?php if (!empty($item['title'])) : ?>
								<h3><?php echo esc_html($item['title']); ?></h3>
							<?php endif; ?>
							<?php if (!empty($item['description'])) : ?>
								<p><?php echo esc_html($item['description']); ?></p>
							<?php endif; ?>
							<?php if (!empty($item['button_text']) && !empty($item['button_link']['url'])) : ?>
								<a href="<?php echo esc_url($item['button_link']['url']); ?>" class="collection-button"<?php echo $target . $nofollow; ?>>
									<?php echo esc_html($item['button_text']); ?>
								</a>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
<?php
	}

	protected function content_template() {}
}
