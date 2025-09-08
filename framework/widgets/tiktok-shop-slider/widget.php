<?php

namespace WoozioElementorWidgets\Widgets\TikTokShopSlider;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Plugin;
use ElementorPro\Base\Base_Carousel_Trait;


class Widget_TikTokShopSlider extends Widget_Base
{
    use Base_Carousel_Trait;
    public function get_name()
    {
        return 'bt-tiktok-shop-slider';
    }

    public function get_title()
    {
        return __('TikTok Shop Slider', 'woozio');
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
        return ['magnific-popup', 'swiper-slider', 'elementor-widgets'];
    }
    protected function get_supported_ids()
    {
        $supported_ids = [];

        $wp_query = new \WP_Query(array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => -1
        ));

        if ($wp_query->have_posts()) {
            while ($wp_query->have_posts()) {
                $wp_query->the_post();
                $supported_ids[get_the_ID()] = get_the_title();
            }
        }

        return $supported_ids;
    }
    protected function register_layout_section_controls()
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'woozio'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'tiktok_image',
            [
                'label' => __('Image', 'woozio'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );
        $repeater->add_control(
            'id_product',
            [
                'label' => __('Select Product', 'woozio'),
                'type' => Controls_Manager::SELECT2,
                'options' => $this->get_supported_ids(),
                'label_block' => true,
                'multiple' => false,
            ]
        );
        $repeater->add_control(
            'video_type',
            [
                'label' => esc_html__('Video Type', 'woozio'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'upload' => esc_html__('Upload Video', 'woozio'),
                    'iframe' => esc_html__('TikTok Video', 'woozio'),
                ],
                'default' => 'url',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'video_upload',
            [
                'label' => esc_html__('Upload Video', 'woozio'),
                'type' => Controls_Manager::MEDIA,
                'media_type' => 'video',
                'condition' => [
                    'video_type' => 'upload',
                ],
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'video_iframe',
            [
                'label' => esc_html__('TikTok Video ID', 'woozio'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Enter TikTok video ID', 'woozio'),
                'description' => esc_html__('Example: 7449001150021471495', 'woozio'),
                'condition' => [
                    'video_type' => 'iframe',
                ],
                'label_block' => true,
            ]
        );
        $this->add_control(
            'list',
            [
                'label' => __('List Products', 'woozio'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'tiktok_image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                    ],
                    [
                        'tiktok_image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                    ],
                    [
                        'tiktok_image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'label' => __('Image Size', 'woozio'),
                'show_label' => true,
                'default' => 'medium_large',
                'exclude' => ['custom'],
            ]
        );

        $this->add_responsive_control(
            'image_ratio',
            [
                'label' => __('Image Ratio', 'woozio'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1.3,
                ],
                'range' => [
                    'px' => [
                        'min' => 0.3,
                        'max' => 2,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-tiktok-shop-slider .bt-cover-image' => 'padding-bottom: calc( {{SIZE}} * 100% );',
                ],
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'section_style_slider',
            [
                'label' => esc_html__('Slider', 'woozio'),
            ]
        );
        $this->add_control(
            'slider_autoplay',
            [
                'label' => __('Slider Autoplay', 'woozio'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'woozio'),
                'label_off' => __('No', 'woozio'),
                'default' => 'no',
            ]
        );
        $this->add_control(
            'slider_loop',
            [
                'label' => __('Infinite Loop', 'woozio'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'woozio'),
                'label_off' => __('No', 'woozio'),
                'default' => 'yes',
                'description' => __('Enable continuous loop mode', 'woozio'),
            ]
        );
        $this->add_carousel_layout_controls( [
			'css_prefix' => '',
			'slides_to_show_custom_settings' => [
				'default' => '5',
				'tablet_default' => '3',
				'mobile_default' => '1',
				'selectors' => [
					'{{WRAPPER}}' => '--swiper-slides-to-display: {{VALUE}}',
				],
			],
			'slides_to_scroll_custom_settings' => [
				'default' => '0',
                'condition' => [
                    'slides_to_show_custom_settings' => 100,
                ],
			],
			'equal_height_custom_settings' => [
				'selectors' => [
					'{{WRAPPER}} .swiper-slide > .elementor-element' => 'height: 100%',
				],
                'condition' => [
                    'slides_to_show_custom_settings' => 100,
                ],
			],
			'slides_on_display' => 5,
		] );
        $this->add_responsive_control(
            'slider_spacebetween',
            [
                'label' => __('Slider SpaceBetween', 'woozio'),
                'type' => Controls_Manager::NUMBER,
                'default' => 20,
                'tablet_default' => 20,
                'mobile_default' => 10,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'description' => __('Space between slides in pixels', 'woozio'),
            ]
        );
        $this->add_control(
            'slider_speed',
            [
                'label' => __('Slider Speed', 'woozio'),
                'type' => Controls_Manager::NUMBER,
                'default' => 1000,
                'min' => 100,
                'step' => 100,
            ]
        );
        $this->add_control(
            'slider_arrows',
            [
                'label' => __('Show Arrows', 'woozio'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'woozio'),
                'label_off' => __('No', 'woozio'),
            ]
        );
        $this->add_control(
            'slider_arrows_hidden_mobile',
            [
                'label' => __('Hidden Arrow Mobile', 'woozio'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'woozio'),
                'label_off' => __('No', 'woozio'),
                'default' => 'no',
                'condition' => [
                    'slider_arrows' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'slider_dots',
            [
                'label' => __('Show Dots', 'woozio'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'woozio'),
                'label_off' => __('No', 'woozio'),
                'default' => 'no',
            ]
        );
        $this->add_control(
            'slider_dots_only_mobile',
            [
                'label' => __('Mobile-Only Pagination', 'woozio'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'woozio'),
                'label_off' => __('No', 'woozio'),
                'default' => 'no',
                'condition' => [
                    'slider_dots' => 'yes',
                ],
            ]
        );
        $this->end_controls_section();
    }

    protected function register_style_section_controls()
    {

        $this->start_controls_section(
            'section_style_box',
            [
                'label' => esc_html__('Box Style', 'woozio'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'box_overflow',
            [
                'label' => __('Overflow', 'woozio'),
                'type' => Controls_Manager::SELECT,
                'default' => 'hidden',
                'options' => [
                    'visible' => __('Visible', 'woozio'),
                    'hidden' => __('Hidden', 'woozio'),
                    'scroll' => __('Scroll', 'woozio'),
                    'auto' => __('Auto', 'woozio'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-elwg-tiktok-shop-slider--default' => 'overflow: {{VALUE}}',
                ],
            ]
        );

        // Box Border
        $this->add_control(
            'box_border_color',
            [
                'label' => __('Border Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-tiktok-shop--wrap' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'box_border_width',
            [
                'label' => __('Border Width', 'woozio'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-tiktok-shop--wrap' => 'border-style: solid; border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'box_border_radius',
            [
                'label' => __('Border Radius', 'woozio'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .bt-tiktok-shop--wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .bt-tiktok-shop--wrap bt-tiktok-shop--product' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;',
                ],
            ]
        );

        // Box Shadow
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .bt-tiktok-shop--wrap',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_arrows',
            [
                'label' => esc_html__('Navigation Arrows', 'woozio'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'slider_arrows' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'arrows_size',
            [
                'label' => __('Size', 'woozio'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 24,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-nav svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('arrows_colors_tabs');

        // Normal state
        $this->start_controls_tab(
            'arrows_colors_normal',
            [
                'label' => __('Normal', 'woozio'),
            ]
        );
        $this->add_control(
            'arrows_color',
            [
                'label' => __('Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .bt-nav svg path' => 'fill: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'arrows_bg_color',
            [
                'label' => __('Background Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0.5)',
                'selectors' => [
                    '{{WRAPPER}} .bt-nav' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab();

        // Hover state
        $this->start_controls_tab(
            'arrows_colors_hover',
            [
                'label' => __('Hover', 'woozio'),
            ]
        );
        $this->add_control(
            'arrows_color_hover',
            [
                'label' => __('Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .bt-nav:hover svg path' => 'fill: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'arrows_bg_color_hover',
            [
                'label' => __('Background Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0.7)',
                'selectors' => [
                    '{{WRAPPER}} .bt-nav:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'arrows_border_radius',
            [
                'label' => __('Border Radius', 'woozio'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .bt-nav' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_style_dots',
            [
                'label' => esc_html__('Navigation Dots', 'woozio'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'slider_dots' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'dots_spacing',
            [
                'label' => __('Spacing Dots', 'woozio'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 30,
                    ],
                ],
                'default' => [
                    'size' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'margin: 0 {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'dots_size',
            [
                'label' => __('Size', 'woozio'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('dots_colors_tabs');

        // Normal state
        $this->start_controls_tab(
            'dots_colors_normal',
            [
                'label' => __('Normal', 'woozio'),
            ]
        );

        $this->add_control(
            'dots_color',
            [
                'label' => __('Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '#0C2C48',
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover state
        $this->start_controls_tab(
            'dots_colors_hover',
            [
                'label' => __('Hover', 'woozio'),
            ]
        );

        $this->add_control(
            'dots_color_hover',
            [
                'label' => __('Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet:hover' => 'background-color: {{VALUE}};opacity: 1;',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->add_control(
            'dots_spacing_slider',
            [
                'label' => __('Spacing', 'woozio'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function register_controls()
    {
        $this->register_layout_section_controls();
        $this->register_style_section_controls();
    }
    private function render_attributes($attrs)
    {
        $output = [];
        foreach ($attrs as $key => $value) {
            $output[] = $key . '="' . esc_attr($value) . '"';
        }
        return implode(' ', $output);
    }

    private function get_image_url($image, $size)
    {
        if (!empty($image['id'])) {
            $attachment = wp_get_attachment_image_src($image['id'], $size);
            return $attachment ? $attachment[0] : '';
        }
        return $image['url'] ?? '';
    }
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        // Early return if no items
        if (empty($settings['list'])) return;


        $slider_settings = [
            'autoplay' => $settings['slider_autoplay'] === 'yes',
            'loop' => $settings['slider_loop'] === 'yes',
            'speed' => (int)$settings['slider_speed'],
            'slidesPerView' => !empty($settings['slides_to_show_mobile']) ? (int)$settings['slides_to_show_mobile'] : 1,
            'spaceBetween' => !empty($settings['slider_spacebetween_mobile']) ? (int)$settings['slider_spacebetween_mobile'] : 20,
            'breakpoints' => []
        ];

        // Add responsive breakpoints
        $breakpoints = Plugin::$instance->breakpoints->get_active_breakpoints();
        foreach ($breakpoints as $key => $breakpoint) {
            // Get the next higher breakpoint key
            $next_key = $key;
            $breakpoint_keys = array_keys($breakpoints);
            $current_index = array_search($key, $breakpoint_keys);

            if ($current_index !== false) {
                $preferred_next = match ($key) {
                    'mobile' => 'mobile_extra',
                    'mobile_extra' => 'tablet',
                    'tablet' => 'tablet_extra',
                    'tablet_extra' => 'laptop',
                    'laptop' => 'desktop',
                    default => $key
                };

                // If preferred next breakpoint exists, use it
                if (isset($breakpoints[$preferred_next])) {
                    $next_key = $preferred_next;
                }
                // Otherwise find next available breakpoint
                else {
                    $found_next = false;
                    for ($i = $current_index + 1; $i < count($breakpoint_keys); $i++) {
                        if (isset($breakpoints[$breakpoint_keys[$i]]) && $breakpoint_keys[$i] !== 'widescreen') {
                            $next_key = $breakpoint_keys[$i];
                            $found_next = true;
                            break;
                        }
                    }
                    if (!$found_next) {
                        $next_key = 'desktop';
                    }
                }
            }

            $slider_settings['breakpoints'][$breakpoint->get_value()] = ($next_key == 'desktop') ? [
                'slidesPerView' => !empty($settings['slides_to_show']) ? (int)$settings['slides_to_show'] : 5,
                'spaceBetween' => !empty($settings['slider_spacebetween']) ? (int)$settings['slider_spacebetween'] : 20
            ] : [
                'slidesPerView' => !empty($settings["slides_to_show_{$next_key}"]) ? (int)$settings["slides_to_show_{$next_key}"] : (int)$settings['slides_to_show'],
                'spaceBetween' => !empty($settings["slider_spacebetween_{$next_key}"]) ? (int)$settings["slider_spacebetween_{$next_key}"] : (int)$settings['slider_spacebetween']
            ];
        }
        // Start slider container
        $classes = ['bt-elwg-tiktok-shop-slider--default', 'swiper'];
        if ($settings['slider_arrows_hidden_mobile'] === 'yes') {
            $classes[] = 'bt-hidden-arrow-mobile';
        }
        if ($settings['slider_dots_only_mobile'] === 'yes') {
            $classes[] = 'bt-only-dot-mobile';
        }
        echo '<div class="' . esc_attr(implode(' ', $classes)) . '" data-slider-settings="' . esc_attr(json_encode($slider_settings)) . '">';

        echo '<ul class="bt-tiktok-shop-slider swiper-wrapper">';

        // Loop through items
        foreach ($settings['list'] as $index => $item) {

            $image_url = $this->get_image_url($item['tiktok_image'], $settings['thumbnail_size']);
            $product = wc_get_product($item['id_product']);
            $has_video = ($item['video_type'] === 'upload' && !empty($item['video_upload']['url'])) ||
                ($item['video_type'] === 'iframe' && !empty($item['video_iframe']));
            echo '<li class="bt-tiktok-shop--item swiper-slide">';
            echo '<div class="bt-tiktok-shop--wrap">';

            // Product image
            echo '<div class="bt-cover-image">';
            if (!empty($item['tiktok_image']['id'])) {
                echo wp_get_attachment_image($item['tiktok_image']['id'], $settings['thumbnail_size']);
            } else {
                echo '<img src="' . esc_url(Utils::get_placeholder_image_src()) . '" alt="' . esc_html__('Awaiting TikTok image', 'woozio') . '">';
            }
            echo '</div>';
            if ($has_video) {
                echo '<a class="bt-play-video js-open-popup" href="#bt_play_video_' . $index . '" aria-label="' . esc_attr__('Play video', 'woozio') . '">';
                echo '<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">';
                echo '<path d="M6.75 3.78441V20.3069C6.75245 20.4388 6.78962 20.5676 6.85776 20.6805C6.9259 20.7934 7.0226 20.8864 7.13812 20.95C7.25364 21.0136 7.38388 21.0456 7.51572 21.0428C7.64756 21.04 7.77634 21.0025 7.88906 20.9341L21.3966 12.6728C21.5045 12.6075 21.5937 12.5155 21.6556 12.4056C21.7175 12.2958 21.7501 12.1718 21.7501 12.0457C21.7501 11.9195 21.7175 11.7956 21.6556 11.6857C21.5937 11.5758 21.5045 11.4838 21.3966 11.4185L7.88906 3.15722C7.77634 3.08879 7.64756 3.05129 7.51572 3.04851C7.38388 3.04572 7.25364 3.07774 7.13812 3.14135C7.0226 3.20495 6.9259 3.29789 6.85776 3.41079C6.78962 3.52369 6.75245 3.65256 6.75 3.78441Z" fill="currentColor"></path>';
                echo '</svg>';
                echo '</a>';
            }

            // Product info
            if ($product) {
                $product_image = $product->get_image('medium');
                $product_name = esc_html($product->get_name());
                $product_price = $product->get_price_html();

                echo '<a class="bt-tiktok-shop--product" href="' . esc_url($product->get_permalink()) . '">';
                if ($product_image) {
                    echo '<div class="bt-product-thumb">' . $product_image . '</div>';
                }
                echo '<div class="bt-product-info">'
                    . '<span class="bt-product-name">' . $product_name . '</span>'
                    . '<span class="bt-product-price' . (!$product->is_type('simple') ? ' bt-type-variable' : '') . '">' . $product_price . '</span>'
                    . '</div>'
                    . '</a>';
            }

            echo '</div>';


            if ($item['video_type'] === 'upload' && !empty($item['video_upload']['url'])) {
                echo '<div id="bt_play_video_' . $index . '" class="bt-video-popup mfp-hide bt-video-type-' . esc_attr($item['video_type']) . '">';
                echo '<div class="bt-video-wrap"><video controls>';
                echo '<source src="' . esc_url($item['video_upload']['url']) . '" type="video/mp4">';
                echo esc_html__('Your browser does not support the video tag.', 'woozio');
                echo '</video></div>';
                echo '</div>';
            } elseif ($item['video_type'] === 'iframe' && !empty($item['video_iframe'])) {
                echo '<div id="bt_play_video_' . $index . '" class="bt-video-popup mfp-hide bt-video-type-' . esc_attr($item['video_type']) . '">';
                echo '<div class="bt-video-wrap">
                  <iframe src="https://www.tiktok.com/embed/v2/' . esc_attr($item['video_iframe']) . '"></iframe>
                    </div>';
                echo '</div>';
            }
            echo '</li>';
        }
        // End slider container
        echo '</ul>';
?>
      <?php
        // Navigation arrows
        if ($settings['slider_arrows'] === 'yes') {
            echo '<div class="bt-swiper-navigation">';
            echo '<div class="bt-nav bt-button-prev">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">';
            echo '<path d="M15.5307 18.9698C15.6004 19.0395 15.6557 19.1222 15.6934 19.2132C15.7311 19.3043 15.7505 19.4019 15.7505 19.5004C15.7505 19.599 15.7311 19.6965 15.6934 19.7876C15.6557 19.8786 15.6004 19.9614 15.5307 20.031C15.461 20.1007 15.3783 20.156 15.2873 20.1937C15.1962 20.2314 15.0986 20.2508 15.0001 20.2508C14.9016 20.2508 14.804 20.2314 14.7129 20.1937C14.6219 20.156 14.5392 20.1007 14.4695 20.031L6.96948 12.531C6.89974 12.4614 6.84443 12.3787 6.80668 12.2876C6.76894 12.1966 6.74951 12.099 6.74951 12.0004C6.74951 11.9019 6.76894 11.8043 6.80668 11.7132C6.84443 11.6222 6.89974 11.5394 6.96948 11.4698L14.4695 3.96979C14.6102 3.82906 14.8011 3.75 15.0001 3.75C15.1991 3.75 15.39 3.82906 15.5307 3.96979C15.6715 4.11052 15.7505 4.30139 15.7505 4.50042C15.7505 4.69944 15.6715 4.89031 15.5307 5.03104L8.56041 12.0004L15.5307 18.9698Z" fill="currentColor"/>';
            echo '</svg>';
            echo '</div>';

            echo '<div class="bt-nav bt-button-next">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">';
            echo '<path d="M17.0306 12.531L9.53055 20.031C9.46087 20.1007 9.37815 20.156 9.2871 20.1937C9.19606 20.2314 9.09847 20.2508 8.99993 20.2508C8.90138 20.2508 8.8038 20.2314 8.71276 20.1937C8.62171 20.156 8.53899 20.1007 8.4693 20.031C8.39962 19.9614 8.34435 19.8786 8.30663 19.7876C8.26892 19.6965 8.24951 19.599 8.24951 19.5004C8.24951 19.4019 8.26892 19.3043 8.30663 19.2132C8.34435 19.1222 8.39962 19.0395 8.4693 18.9698L15.4396 12.0004L8.4693 5.03104C8.32857 4.89031 8.24951 4.69944 8.24951 4.50042C8.24951 4.30139 8.32857 4.11052 8.4693 3.96979C8.61003 3.82906 8.80091 3.75 8.99993 3.75C9.19895 3.75 9.38982 3.82906 9.53055 3.96979L17.0306 11.4698C17.1003 11.5394 17.1556 11.6222 17.1933 11.7132C17.2311 11.8043 17.2505 11.9019 17.2505 12.0004C17.2505 12.099 17.2311 12.1966 17.1933 12.2876C17.1556 12.3787 17.1003 12.4614 17.0306 12.531Z" fill="currentColor"/>';
            echo '</svg>';
            echo '</div>';
            echo '</div>';
        }
        // pagination
        if ($settings['slider_dots'] === 'yes') {
            echo '<div class="bt-swiper-pagination swiper-pagination"></div>';
        }
        echo '</div>';
    }



    protected function content_template() {}
}
