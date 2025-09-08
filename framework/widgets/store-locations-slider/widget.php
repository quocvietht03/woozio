<?php

namespace WoozioElementorWidgets\Widgets\StoreLocationsSlider;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Plugin;
use ElementorPro\Base\Base_Carousel_Trait;


class Widget_StoreLocationsSlider extends Widget_Base
{
    use Base_Carousel_Trait;

    public function get_name()
    {
        return 'bt-store-locations-slider';
    }

    public function get_title()
    {
        return __('Store Locations Slider', 'woozio');
    }

    public function get_icon()
    {
        return 'eicon-slider-push';
    }

    public function get_categories()
    {
        return ['woozio'];
    }

    public function get_script_depends()
    {
        return ['swiper-slider', 'elementor-widgets'];
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
            'image_store',
            [
                'label' => __('Image Store', 'woozio'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );
        $repeater->add_control(
            'title_store',
            [
                'label' => __('Name Store', 'woozio'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Name Store', 'woozio'),
            ]
        );
        $repeater->add_control(
            'address_store',
            [
                'label' => __('Address Store', 'woozio'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Address Store', 'woozio'),
            ]
        );
        $repeater->add_control(
            'phone_store',
            [
                'label' => __('Phone Store', 'woozio'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Phone Store', 'woozio'),
            ]
        );
        $repeater->add_control(
            'email_store',
            [
                'label' => __('Email Store', 'woozio'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Email Store', 'woozio'),
            ]
        );

        $repeater->add_control(
            'maps_link',
            [
                'label' => __('Google Maps Link', 'woozio'),
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://maps.google.com/?q=...', 'woozio'),
                'show_external' => true,
                'default' => [
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true,
                ],
                'description' => __('Enter the Google Maps link for this store location', 'woozio'),
            ]
        );

        $this->add_control(
            'store_locations',
            [
                'label' => __('Store Locations', 'woozio'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'image_store' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'title_store' => __('Store Name', 'woozio'),
                        'address_store' => __('123 Store Address', 'woozio'),
                        'phone_store' => __('(123) 456-7890', 'woozio'),
                        'email_store' => __('store@example.com', 'woozio'),
                        'product' => '',
                        'maps_link' => [
                            'url' => 'https://maps.google.com',
                            'is_external' => true,
                            'nofollow' => true,
                        ],
                    ],
                    [
                        'image_store' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'title_store' => __('Store Name', 'woozio'),
                        'address_store' => __('123 Store Address', 'woozio'),
                        'phone_store' => __('(123) 456-7890', 'woozio'),
                        'email_store' => __('store@example.com', 'woozio'),
                        'product' => '',
                        'maps_link' => [
                            'url' => 'https://maps.google.com',
                            'is_external' => true,
                            'nofollow' => true,
                        ],
                    ],
                    [
                        'image_store' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'title_store' => __('Store Name', 'woozio'),
                        'address_store' => __('123 Store Address', 'woozio'),
                        'phone_store' => __('(123) 456-7890', 'woozio'),
                        'email_store' => __('store@example.com', 'woozio'),
                        'product' => '',
                        'maps_link' => [
                            'url' => 'https://maps.google.com',
                            'is_external' => true,
                            'nofollow' => true,
                        ],
                    ],
                ],
                'title_field' => '{{{ title_store }}}',
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
                    '{{WRAPPER}} .bt-store-locations-slider--image .bt-cover-image' => 'padding-bottom: calc( {{SIZE}} * 100% );',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_slider',
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
            'slider_autoplay_delay',
            [
                'label' => __('Autoplay Delay', 'woozio'),
                'type' => Controls_Manager::NUMBER,
                'default' => 3000,
                'min' => 1000,
                'max' => 10000,
                'step' => 500,
                'condition' => [
                    'slider_autoplay' => 'yes',
                ],
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
        $this->add_carousel_layout_controls([
            'css_prefix' => '',
            'slides_to_show_custom_settings' => [
                'default' => '3',
                'tablet_default' => '2',
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
        ]);

        $this->add_responsive_control(
            'slider_spacebetween',
            [
                'label' => __('Space Between', 'woozio'),
                'type' => Controls_Manager::NUMBER,
                'default' => 30,
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
                'default' => 3000,
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
                'default' => 'no',
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

        $this->add_control(
            'slider_offset_sides',
            [
                'label' => __('Offset Sides', 'woozio'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => __('None', 'woozio'),
                    'both' => __('Both', 'woozio'),
                    'left' => __('Left', 'woozio'),
                    'right' => __('Right', 'woozio'),
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_offset_width',
            [
                'label' => __('Offset Width', 'woozio'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
                    'size' => 80,
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-elwg-store-locations-slider' => '--slider-offset-width: {{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'ui',
                'condition' => [
                    'slider_offset_sides!' => 'none',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function register_style_content_section_controls()
    {
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
                    'size' => 20,
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
        $this->register_content_section_controls();
        $this->register_style_content_section_controls();
    }


    protected function render()
    {
        $settings = $this->get_settings_for_display();
        if (empty($settings['store_locations'])) {
            return;
        }

        $classes = ['bt-elwg-store-locations-slider'];
        if ($settings['slider_arrows_hidden_mobile'] === 'yes') {
            $classes[] = 'bt-hidden-arrow-mobile';
        }
        if ($settings['slider_dots_only_mobile'] === 'yes') {
            $classes[] = 'bt-only-dot-mobile';
        }
        $breakpoints = Plugin::$instance->breakpoints->get_active_breakpoints();
        $slider_settings = bt_elwg_get_slider_settings($settings, $breakpoints);
?>
        <div class="<?php echo esc_attr(implode(' ', $classes)); ?> bt-slider-offset-sides-<?php echo esc_attr($settings['slider_offset_sides']); ?>" data-slider-settings="<?php echo esc_attr(json_encode($slider_settings)); ?>">
            <div class="swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($settings['store_locations'] as $item) : ?>
                        <div class="swiper-slide">
                            <div class="bt-store-locations-slider--item">
                                <div class="bt-store-locations-slider--image">
                                    <div class="bt-cover-image">
                                        <?php
                                        if (!empty($item['image_store']['id'])) {
                                            echo wp_get_attachment_image($item['image_store']['id'], $settings['thumbnail_size']);
                                        } else {
                                            if (!empty($item['image_store']['url'])) {
                                                echo '<img src="' . esc_url($item['image_store']['url']) . '" alt="' . esc_html__('Awaiting product image', 'woozio') . '">';
                                            } else {
                                                echo '<img src="' . esc_url(Utils::get_placeholder_image_src()) . '" alt="' . esc_html__('Awaiting product image', 'woozio') . '">';
                                            }
                                        } ?>
                                    </div>
                                </div>
                                <div class="bt-store-locations-slider--content">
                                    <h3 class="bt-store-locations-slider--title"><?php echo esc_html($item['title_store']); ?></h3>
                                    <div class="bt-store-locations-slider--address"><?php echo esc_html($item['address_store']); ?></div>
                                    <div class="bt-store-locations-slider--maps">
                                        <a href="<?php echo esc_url($item['maps_link']['url']); ?>" <?php echo esc_attr($item['maps_link']['is_external'] ? 'target="_blank"' : ''); ?>><?php echo esc_html__('View On Map', 'woozio'); ?></a>
                                    </div>
                                    <div class="bt-store-locations-slider--phone"><?php echo esc_html__('Phone: ', 'woozio'); ?><a href="tel:<?php echo esc_attr(str_replace(' ', '', $item['phone_store'])); ?>"><?php echo esc_html($item['phone_store']); ?></a></div>
                                    <div class="bt-store-locations-slider--email"><?php echo esc_html__('Email: ', 'woozio'); ?><a href="mailto:<?php echo esc_attr($item['email_store']); ?>"><?php echo esc_html($item['email_store']); ?></a></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($settings['slider_arrows'] === 'yes') : ?>
                    <div class="bt-swiper-navigation">
                        <div class="bt-nav bt-button-prev">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 16 14" fill="none">
                                <path d="M15.4995 7.00035C15.4995 7.16611 15.4337 7.32508 15.3165 7.44229C15.1992 7.5595 15.0403 7.62535 14.8745 7.62535H2.63311L7.1917 12.1832C7.24977 12.2412 7.29583 12.3102 7.32726 12.386C7.35869 12.4619 7.37486 12.5432 7.37486 12.6253C7.37486 12.7075 7.35869 12.7888 7.32726 12.8647C7.29583 12.9405 7.24977 13.0095 7.1917 13.0675C7.13363 13.1256 7.0647 13.1717 6.98882 13.2031C6.91295 13.2345 6.83164 13.2507 6.74951 13.2507C6.66739 13.2507 6.58607 13.2345 6.5102 13.2031C6.43433 13.1717 6.3654 13.1256 6.30733 13.0675L0.682328 7.44254C0.624217 7.38449 0.578118 7.31556 0.546665 7.23969C0.515213 7.16381 0.499023 7.08248 0.499023 7.00035C0.499023 6.91821 0.515213 6.83688 0.546665 6.76101C0.578118 6.68514 0.624217 6.61621 0.682328 6.55816L6.30733 0.93316C6.4246 0.815885 6.58366 0.75 6.74951 0.75C6.91537 0.75 7.07443 0.815885 7.1917 0.93316C7.30898 1.05044 7.37486 1.2095 7.37486 1.37535C7.37486 1.5412 7.30898 1.70026 7.1917 1.81753L2.63311 6.37535H14.8745C15.0403 6.37535 15.1992 6.4412 15.3165 6.55841C15.4337 6.67562 15.4995 6.83459 15.4995 7.00035Z" fill="currentColor" />
                            </svg>
                        </div>
                        <div class="bt-nav bt-button-next">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M17.3172 10.4425L11.6922 16.0675C11.5749 16.1848 11.4159 16.2507 11.25 16.2507C11.0841 16.2507 10.9251 16.1848 10.8078 16.0675C10.6905 15.9503 10.6247 15.7912 10.6247 15.6253C10.6247 15.4595 10.6905 15.3004 10.8078 15.1832L15.3664 10.6253H3.125C2.95924 10.6253 2.80027 10.5595 2.68306 10.4423C2.56585 10.3251 2.5 10.1661 2.5 10.0003C2.5 9.83459 2.56585 9.67562 2.68306 9.55841C2.80027 9.4412 2.95924 9.37535 3.125 9.37535H15.3664L10.8078 4.81753C10.6905 4.70026 10.6247 4.5412 10.6247 4.37535C10.6247 4.2095 10.6905 4.05044 10.8078 3.93316C10.9251 3.81588 11.0841 3.75 11.25 3.75C11.4159 3.75 11.5749 3.81588 11.6922 3.93316L17.3172 9.55816C17.3753 9.61621 17.4214 9.68514 17.4528 9.76101C17.4843 9.83688 17.5005 9.91821 17.5005 10.0003C17.5005 10.0825 17.4843 10.1638 17.4528 10.2397C17.4214 10.3156 17.3753 10.3845 17.3172 10.4425Z" fill="currentColor" />
                            </svg>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($settings['slider_dots'] === 'yes') : ?>
                    <div class="bt-swiper-pagination swiper-pagination"></div>
                <?php endif; ?>
            </div>
        </div>
<?php
    }

    protected function content_template() {}
}
