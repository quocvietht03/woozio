<?php

namespace WoozioElementorWidgets\Widgets\ProductCategoryLoopItem;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Widget_ProductCategoryLoopItem extends Widget_Base
{

    public function get_name()
    {
        return 'bt-product-category-loop-item';
    }

    public function get_title()
    {
        return __('Product Cat Item', 'woozio');
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

    protected function register_layout_section_controls()
    {
        $this->start_controls_section(
            'section_layout',
            [
                'label' => __('Layout', 'woozio'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'layout_style',
            [
                'label' => __('Layout Style', 'woozio'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => __('Default', 'woozio'),
                    'style-1' => __('Style 1', 'woozio'),
                    'style-2' => __('Style 2', 'woozio'),
                    'style-3' => __('Style 3', 'woozio'),
                    'style-4' => __('Style 4', 'woozio'),
                    'style-5' => __('Style 5', 'woozio'),
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'label' => __('Image Size', 'woozio'),
                'show_label' => true,
                'default' => 'medium',
                'exclude' => ['custom'],
            ]
        );

        $this->add_responsive_control(
            'image_ratio',
            [
                'label' => __('Image Ratio', 'woozio'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'min' => 0.3,
                        'max' => 2,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-product-category--thumb .bt-cover-image' => 'padding-bottom: calc( {{SIZE}} * 100% );',
                ],
            ]
        );

        $this->add_control(
            'show_count',
            [
                'label' => __('Show Product Count', 'woozio'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'woozio'),
                'label_off' => __('Hide', 'woozio'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->end_controls_section();
    }

    protected function register_style_section_controls()
    {
        // Image Style Section
        $this->start_controls_section(
            'section_style_image',
            [
                'label' => esc_html__('Image', 'woozio'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'img_border_radius',
            [
                'label' => __('Border Radius', 'woozio'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .bt-product-category--thumb .bt-cover-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .bt-product-category--thumb .bt-cover-image',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'image_background',
            [
                'label' => __('Background Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-product-category--thumb .bt-cover-image' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->start_controls_tabs('thumbnail_effects_tabs');

        $this->start_controls_tab(
            'thumbnail_tab_normal',
            [
                'label' => __('Normal', 'woozio'),
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'thumbnail_filters',
                'selector' => '{{WRAPPER}} .bt-product-category--thumb img',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'thumbnail_tab_hover',
            [
                'label' => __('Hover', 'woozio'),
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'thumbnail_hover_filters',
                'selector' => '{{WRAPPER}} .bt-product-category--item:hover .bt-product-category--thumb img',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // Content Style Section
        $this->start_controls_section(
            'section_style_content',
            [
                'label' => esc_html__('Content', 'woozio'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'content_background',
            [
                'label' => __('Background', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .bt-product-category--content' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'layout_style' => ['style-5'],
                ],
            ]
        );
        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __('Padding', 'woozio'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .bt-product-category--content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .bt-product-category--content svg' => 'right: {{LEFT}}{{UNIT}}; top: calc({{TOP}}{{UNIT}} + 10px);',
                ],
                'condition' => [
                    'layout_style' => ['style-5'],
                ],
            ]
        );
        $this->add_responsive_control(
            'content_spacing',
            [
                'label' => __('Spacing', 'woozio'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .bt-product-category--content' => 'left: {{LEFT}}{{UNIT}};right: {{RIGHT}}{{UNIT}};top: {{TOP}}{{UNIT}};bottom: {{BOTTOM}}{{UNIT}};',
                ],
                'condition' => [
                    'layout_style' => ['style-1'],
                ],
            ]
        );
        $this->add_responsive_control(
			'content_text_align',
			[
				'label' => esc_html__('Alignment', 'woozio'),
				'type'  => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__('Left', 'woozio'),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'woozio'),
						'icon'  => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__('Right', 'woozio'),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default' => 'left',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .bt-product-category--content' => 'justify-content: {{VALUE}};text-align: {{VALUE}};',
				],
				'condition' => [
					'layout_style' => ['style-1'],
				],
			]
		);
        $this->add_control(
            'name_cat_style',
            [
                'label' => __('Category Name', 'woozio'),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->add_control(
            'name_cat_color',
            [
                'label' => __('Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .bt-product-category--name' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bt-product-category--content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'name_cat_color_hover',
            [
                'label' => __('Color Hover', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .bt-product-category--item:hover .bt-product-category--name' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bt-product-category--item:hover .bt-product-category--content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'name_cat_background',
            [
                'label' => __('Background', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .bt-product-category--content' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'layout_style' => ['default', 'style-1'],
                ],
            ]
        );

        $this->add_control(
            'name_cat_background_hover',
            [
                'label' => __('Background Hover', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .bt-product-category--item:hover .bt-product-category--content' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'layout_style' => ['default', 'style-1'],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'name_cat_typography',
                'label' => __('Typography', 'woozio'),
                'default' => '',
                'selector' => '{{WRAPPER}} .bt-product-category--content, {{WRAPPER}} .bt-product-category--name'
            ]
        );

        $this->add_control(
            'count_style',
            [
                'label' => __('Product Count', 'woozio'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_count' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'count_color',
            [
                'label' => __('Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .bt-product-category--count' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_count' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'count_typography',
                'label' => __('Typography', 'woozio'),
                'default' => '',
                'selector' => '{{WRAPPER}} .bt-product-category--count',
                'condition' => [
                    'show_count' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'button_style',
            [
                'label' => __('Button', 'woozio'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'layout_style' => ['style-5'],
                ],
            ]
        );
        $this->add_control(
            'button_width',
            [
                'label' => __('Width', 'woozio'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-product-category--content svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'layout_style' => ['style-5'],
                ],
            ]
        );

        $this->add_control(
            'button_height',
            [
                'label' => __('Height', 'woozio'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-product-category--content svg' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'layout_style' => ['style-5'],
                ],
            ]
        );

        $this->add_control(
            'button_padding',
            [
                'label' => __('Padding', 'woozio'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .bt-product-category--content svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'layout_style' => ['style-5'],
                ],
            ]
        );
        $this->start_controls_tabs('button_style_tabs');

        $this->start_controls_tab(
            'button_style_normal',
            [
                'label' => __('Normal', 'woozio'),
                'condition' => [
                    'layout_style' => ['style-5'],
                ],
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label' => __('Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-product-category--content svg' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'layout_style' => ['style-5'],
                ],
            ]
        );

        $this->add_control(
            'button_background',
            [
                'label' => __('Background Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-product-category--content svg' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'layout_style' => ['style-5'],
                ],
            ]
        );

        $this->add_control(
            'button_border_color',
            [
                'label' => __('Border Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-product-category--content svg' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'layout_style' => ['style-5'],
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_style_hover',
            [
                'label' => __('Hover', 'woozio'),
                'condition' => [
                    'layout_style' => ['style-5'],
                ],
            ]
        );

        $this->add_control(
            'button_hover_color',
            [
                'label' => __('Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-product-category--inner:hover .bt-product-category--content svg' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'layout_style' => ['style-5'],
                ],
            ]
        );

        $this->add_control(
            'button_hover_background',
            [
                'label' => __('Background Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-product-category--inner:hover .bt-product-category--content svg' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'layout_style' => ['style-5'],
                ],
            ]
        );

        $this->add_control(
            'button_hover_border_color',
            [
                'label' => __('Border Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-product-category--inner:hover .bt-product-category--content svg' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'layout_style' => ['style-5'],
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function register_controls()
    {
        $this->register_layout_section_controls();
        $this->register_style_section_controls();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        global $wp_query;
        if (!isset($wp_query->loop_term) || !is_a($wp_query->loop_term, 'WP_Term')) {
            return;
        }
?>
        <div class="bt-elwg-product-category-loop-item bt-elwg-product-category-loop--<?php echo esc_attr($settings['layout_style']); ?>">
            <?php
            get_template_part('framework/templates/product-cat', 'style', array(
                'image-size' => $settings['thumbnail_size'],
                'layout' => 'default',
                'show_count' => $settings['show_count'],
                'category' => $wp_query->loop_term,
            ));
            ?>

        </div>
<?php
    }

    protected function content_template() {}
}
