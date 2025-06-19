<?php

namespace WoozioElementorWidgets\Widgets\ProductTestimonial;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_BBorder;
use Elementor\Group_Control_Box_Shadow;

class Widget_ProductTestimonial extends Widget_Base
{

    public function get_name()
    {
        return 'bt-product-testimonial';
    }

    public function get_title()
    {
        return __('Product Testimonial', 'woozio');
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
        return ['swiper-slider', 'elementor-widgets'];
    }
    protected function get_supported_ids()
    {
        $supported_ids = [];

        $wp_query = new \WP_Query(array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => -1,
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
                'label' => __('Testimonial', 'woozio'),
            ]
        );
        $this->add_control(
            'testimonial_label',
            [
                'label' => __('Testimonial Label', 'woozio'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Customer Say!', 'woozio'),
                'placeholder' => __('Enter testimonial label', 'woozio'),
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

        $repeater = new Repeater();
        $repeater->add_control(
            'testimonial_text',
            [
                'label' => __('Testimonial Text', 'woozio'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 5,
                'default' => __('Enter testimonial text here', 'woozio'),
                'placeholder' => __('Type your testimonial text', 'woozio'),
            ]
        );
        $repeater->add_control(
            'testimonial_rating',
            [
                'label' => __('Rating', 'woozio'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '1' => '1 Star',
                    '2' => '2 Stars',
                    '3' => '3 Stars',
                    '4' => '4 Stars',
                    '5' => '5 Stars',
                ],
                'default' => '5',
            ]
        );

        $repeater->add_control(
            'testimonial_author',
            [
                'label' => __('Author', 'woozio'),
                'type' => Controls_Manager::TEXT,
                'default' => __('John Doe', 'woozio'),
                'placeholder' => __('Enter author name', 'woozio'),
            ]
        );
        $repeater->add_control(
            'testimonial_image',
            [
                'label' => __('Image Banner', 'woozio'),
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
        $this->add_control(
            'testimonial_items',
            [
                'label' => __('Testimonial', 'woozio'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'testimonial_text' => __('Great product! Highly recommend it.', 'woozio'),
                    ],
                    [
                        'testimonial_text' => __('Excellent quality and fast delivery.', 'woozio'),
                    ],
                    [
                        'testimonial_text' => __('Best purchase I made this year!', 'woozio'),
                    ],
                ],
                'title_field' => '{{{ testimonial_text }}}',
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_slider',
            [
                'label' => __('Slider', 'woozio'),
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
                'description' => __('Delay between slides in milliseconds', 'woozio'),
                'condition' => [
                    'slider_autoplay' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'slider_speed',
            [
                'label' => __('Slider Speed', 'woozio'),
                'type' => Controls_Manager::NUMBER,
                'default' => 1000,
                'min' => 100,
                'max' => 5000,
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
                'default' => 'yes',
            ]
        );
        $this->end_controls_section();
    }

    protected function register_style_section_controls()
    {
        // Content Style Section
        $this->start_controls_section(
            'section_content_style',
            [
                'label' => __('Content Style', 'woozio'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'label_heading',
            [
                'label' => __('Label', 'woozio'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => __('Label Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '#0C2C48',
                'selectors' => [
                    '{{WRAPPER}} .bt-product-testimonial--label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'selector' => '{{WRAPPER}} .bt-product-testimonial--label',
            ]
        );
        $this->add_control(
            'quote_icon_heading',
            [
                'label' => __('Quote Icon', 'woozio'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'quote_icon_color',
            [
                'label' => __('Quote Icon Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5A86A9',
                'selectors' => [
                    '{{WRAPPER}} .bt-product-testimonial--quote svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'quote_icon_size',
            [
                'label' => __('Quote Icon Size', 'woozio'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 60,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-product-testimonial--quote svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'text_heading',
            [
                'label' => __('Testimonial Text', 'woozio'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '#0C2C48',
                'selectors' => [
                    '{{WRAPPER}} .bt-product-testimonial--text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .bt-product-testimonial--text',
            ]
        );

        $this->add_responsive_control(
            'text_margin',
            [
                'label' => __('Text Margin', 'woozio'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .bt-product-testimonial--text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'author_heading',
            [
                'label' => __('Author', 'woozio'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'author_color',
            [
                'label' => __('Author Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '#0C2C48',
                'selectors' => [
                    '{{WRAPPER}} .bt-product-testimonial--author' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'author_typography',
                'selector' => '{{WRAPPER}} .bt-product-testimonial--author',
            ]
        );

        $this->add_control(
            'rating_heading',
            [
                'label' => __('Rating', 'woozio'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'rating_color',
            [
                'label' => __('Star Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5A86A9',
                'selectors' => [
                    '{{WRAPPER}} .bt-product-testimonial--rating .star.filled svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'rating_empty_color',
            [
                'label' => __('Empty Star Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E9E9E9',
                'selectors' => [
                    '{{WRAPPER}} .bt-product-testimonial--rating .star svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'rating_size',
            [
                'label' => __('Star Size', 'woozio'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-product-testimonial--rating .star svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        // Navigation Style Section
        $this->start_controls_section(
            'section_navigation_style',
            [
                'label' => __('Navigation Style', 'woozio'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'slider_arrows' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => __('Arrow Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '#0C2C48',
                'selectors' => [
                    '{{WRAPPER}} .bt-nav svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_background_color',
            [
                'label' => __('Arrow Background', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .bt-nav' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_border_color',
            [
                'label' => __('Arrow Border Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5A86A9',
                'selectors' => [
                    '{{WRAPPER}} .bt-nav' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_hover_color',
            [
                'label' => __('Arrow Hover Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .bt-nav:hover svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_hover_background_color',
            [
                'label' => __('Arrow Hover Background', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5A86A9',
                'selectors' => [
                    '{{WRAPPER}} .bt-nav:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_hover_border_color',
            [
                'label' => __('Arrow Hover Border Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5A86A9',
                'selectors' => [
                    '{{WRAPPER}} .bt-nav:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_size',
            [
                'label' => __('Arrow Size', 'woozio'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 24,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-nav svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_padding',
            [
                'label' => __('Arrow Padding', 'woozio'),
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
                    'unit' => 'px',
                    'size' => 11,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-nav' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_border_radius',
            [
                'label' => __('Border Radius', 'woozio'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '50',
                    'right' => '50',
                    'bottom' => '50',
                    'left' => '50',
                    'unit' => '%',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-nav' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $slider_settings = [
            'autoplay' => isset($settings['slider_autoplay']) && $settings['slider_autoplay'] === 'yes',
            'speed' => isset($settings['slider_speed']) ? $settings['slider_speed'] : 500,
            'autoplay_delay' => isset($settings['slider_autoplay_delay']) ? $settings['slider_autoplay_delay'] : 3000,
        ];
?>
        <div class="bt-elwg-product-testimonial--default" data-slider-settings='<?php echo json_encode($slider_settings); ?>'>
            <div class="bt-product-testimonial">
                <div class="bt-product-testimonial--content">
                    <div class="swiper js-testimonial-content">
                    <div class="bt-product-testimonial--quote"><svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 60 60" fill="none">
                            <path d="M27.1875 16.875V37.5C27.1844 40.4827 25.9981 43.3424 23.889 45.4515C21.7799 47.5606 18.9202 48.7469 15.9375 48.75C15.4402 48.75 14.9633 48.5525 14.6117 48.2008C14.26 47.8492 14.0625 47.3723 14.0625 46.875C14.0625 46.3777 14.26 45.9008 14.6117 45.5492C14.9633 45.1975 15.4402 45 15.9375 45C17.9266 45 19.8343 44.2098 21.2408 42.8033C22.6473 41.3968 23.4375 39.4891 23.4375 37.5V35.625H9.375C8.38044 35.625 7.42661 35.2299 6.72335 34.5266C6.02009 33.8234 5.625 32.8696 5.625 31.875V16.875C5.625 15.8804 6.02009 14.9266 6.72335 14.2233C7.42661 13.5201 8.38044 13.125 9.375 13.125H23.4375C24.4321 13.125 25.3859 13.5201 26.0891 14.2233C26.7924 14.9266 27.1875 15.8804 27.1875 16.875ZM50.625 13.125H36.5625C35.5679 13.125 34.6141 13.5201 33.9109 14.2233C33.2076 14.9266 32.8125 15.8804 32.8125 16.875V31.875C32.8125 32.8696 33.2076 33.8234 33.9109 34.5266C34.6141 35.2299 35.5679 35.625 36.5625 35.625H50.625V37.5C50.625 39.4891 49.8348 41.3968 48.4283 42.8033C47.0218 44.2098 45.1141 45 43.125 45C42.6277 45 42.1508 45.1975 41.7992 45.5492C41.4475 45.9008 41.25 46.3777 41.25 46.875C41.25 47.3723 41.4475 47.8492 41.7992 48.2008C42.1508 48.5525 42.6277 48.75 43.125 48.75C46.1077 48.7469 48.9674 47.5606 51.0765 45.4515C53.1856 43.3424 54.3719 40.4827 54.375 37.5V16.875C54.375 15.8804 53.9799 14.9266 53.2766 14.2233C52.5734 13.5201 51.6196 13.125 50.625 13.125Z" fill="#5A86A9" />
                        </svg></div>
                    <?php if (!empty($settings['testimonial_label'])) : ?>
                        <div class="bt-product-testimonial--label"><?php echo esc_html($settings['testimonial_label']); ?></div>
                    <?php endif; ?>
                    <div class="swiper-wrapper">
                        <?php if (!empty($settings['testimonial_items'])) : ?>
                            <?php foreach ($settings['testimonial_items'] as $item) : ?>
                                <div class="swiper-slide">
                                    <div class="bt-product-testimonial--item">
                                        <?php if (!empty($item['testimonial_text'])) : ?>
                                            <div class="bt-product-testimonial--text"><?php echo esc_html($item['testimonial_text']); ?></div>
                                        <?php endif; ?>

                                        <?php if (!empty($item['testimonial_rating'])) : ?>
                                            <div class="bt-product-testimonial--rating">
                                                <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                    <?php if ($i <= $item['testimonial_rating']) : ?>
                                                        <span class="star filled"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                                <path d="M18.3039 8.97177L14.7882 12.0054L15.8593 16.5421C15.9184 16.7884 15.9032 17.0466 15.8156 17.2843C15.728 17.5219 15.5719 17.7282 15.3671 17.8772C15.1623 18.0262 14.9179 18.1111 14.6648 18.1213C14.4118 18.1314 14.1614 18.0664 13.9453 17.9343L9.99995 15.5061L6.0523 17.9343C5.83622 18.0656 5.58613 18.13 5.3335 18.1195C5.08087 18.109 4.837 18.0239 4.63261 17.8751C4.42822 17.7262 4.27243 17.5202 4.18488 17.283C4.09732 17.0458 4.08191 16.788 4.14058 16.5421L5.21558 12.0054L1.69995 8.97177C1.50878 8.80654 1.37052 8.58866 1.30244 8.34532C1.23436 8.10198 1.23947 7.84398 1.31715 7.60354C1.39483 7.3631 1.54162 7.15086 1.73919 6.99335C1.93677 6.83583 2.17637 6.74001 2.42808 6.71786L7.03745 6.34599L8.81558 2.04286C8.91182 1.80834 9.07563 1.60774 9.28618 1.46656C9.49673 1.32538 9.7445 1.25 9.998 1.25C10.2515 1.25 10.4993 1.32538 10.7098 1.46656C10.9204 1.60774 11.0842 1.80834 11.1804 2.04286L12.9578 6.34599L17.5671 6.71786C17.8193 6.73919 18.0596 6.83447 18.2579 6.99177C18.4562 7.14907 18.6037 7.36139 18.6819 7.60212C18.76 7.84286 18.7654 8.10131 18.6973 8.34509C18.6292 8.58887 18.4907 8.80714 18.2992 8.97255L18.3039 8.97177Z" fill="#5A86A9" />
                                                            </svg></span>
                                                    <?php else : ?>
                                                        <span class="star"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                                <path d="M18.3039 8.97177L14.7882 12.0054L15.8593 16.5421C15.9184 16.7884 15.9032 17.0466 15.8156 17.2843C15.728 17.5219 15.5719 17.7282 15.3671 17.8772C15.1623 18.0262 14.9179 18.1111 14.6648 18.1213C14.4118 18.1314 14.1614 18.0664 13.9453 17.9343L9.99995 15.5061L6.0523 17.9343C5.83622 18.0656 5.58613 18.13 5.3335 18.1195C5.08087 18.109 4.837 18.0239 4.63261 17.8751C4.42822 17.7262 4.27243 17.5202 4.18488 17.283C4.09732 17.0458 4.08191 16.788 4.14058 16.5421L5.21558 12.0054L1.69995 8.97177C1.50878 8.80654 1.37052 8.58866 1.30244 8.34532C1.23436 8.10198 1.23947 7.84398 1.31715 7.60354C1.39483 7.3631 1.54162 7.15086 1.73919 6.99335C1.93677 6.83583 2.17637 6.74001 2.42808 6.71786L7.03745 6.34599L8.81558 2.04286C8.91182 1.80834 9.07563 1.60774 9.28618 1.46656C9.49673 1.32538 9.7445 1.25 9.998 1.25C10.2515 1.25 10.4993 1.32538 10.7098 1.46656C10.9204 1.60774 11.0842 1.80834 11.1804 2.04286L12.9578 6.34599L17.5671 6.71786C17.8193 6.73919 18.0596 6.83447 18.2579 6.99177C18.4562 7.14907 18.6037 7.36139 18.6819 7.60212C18.76 7.84286 18.7654 8.10131 18.6973 8.34509C18.6292 8.58887 18.4907 8.80714 18.2992 8.97255L18.3039 8.97177Z" fill="#E9E9E9" />
                                                            </svg></span>
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($item['testimonial_author'])) : ?>
                                            <div class="bt-product-testimonial--author"><?php echo esc_html($item['testimonial_author']); ?><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                    <g clip-path="url(#clip0_2071_927)">
                                                        <path d="M6.875 10.625L8.75 12.5L13.125 8.125" stroke="#3DAB25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M10 17.5C14.1421 17.5 17.5 14.1421 17.5 10C17.5 5.85786 14.1421 2.5 10 2.5C5.85786 2.5 2.5 5.85786 2.5 10C2.5 14.1421 5.85786 17.5 10 17.5Z" stroke="#3DAB25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_2071_927">
                                                            <rect width="20" height="20" fill="white" />
                                                        </clipPath>
                                                    </defs>
                                                </svg></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                    </div>
                    <?php if ($settings['slider_arrows'] === 'yes') : ?>
                        <div class="bt-swiper-navigation">

                            <div class="bt-nav bt-button-prev"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M15.5307 18.9698C15.6004 19.0395 15.6557 19.1222 15.6934 19.2132C15.7311 19.3043 15.7505 19.4019 15.7505 19.5004C15.7505 19.599 15.7311 19.6965 15.6934 19.7876C15.6557 19.8786 15.6004 19.9614 15.5307 20.031C15.461 20.1007 15.3783 20.156 15.2873 20.1937C15.1962 20.2314 15.0986 20.2508 15.0001 20.2508C14.9016 20.2508 14.804 20.2314 14.7129 20.1937C14.6219 20.156 14.5392 20.1007 14.4695 20.031L6.96948 12.531C6.89974 12.4614 6.84443 12.3787 6.80668 12.2876C6.76894 12.1966 6.74951 12.099 6.74951 12.0004C6.74951 11.9019 6.76894 11.8043 6.80668 11.7132C6.84443 11.6222 6.89974 11.5394 6.96948 11.4698L14.4695 3.96979C14.6102 3.82906 14.8011 3.75 15.0001 3.75C15.1991 3.75 15.39 3.82906 15.5307 3.96979C15.6715 4.11052 15.7505 4.30139 15.7505 4.50042C15.7505 4.69944 15.6715 4.89031 15.5307 5.03104L8.56041 12.0004L15.5307 18.9698Z" fill="currentColor" />
                                </svg></div>
                            <div class="bt-nav bt-button-next"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M17.0306 12.531L9.53055 20.031C9.46087 20.1007 9.37815 20.156 9.2871 20.1937C9.19606 20.2314 9.09847 20.2508 8.99993 20.2508C8.90138 20.2508 8.8038 20.2314 8.71276 20.1937C8.62171 20.156 8.53899 20.1007 8.4693 20.031C8.39962 19.9614 8.34435 19.8786 8.30663 19.7876C8.26892 19.6965 8.24951 19.599 8.24951 19.5004C8.24951 19.4019 8.26892 19.3043 8.30663 19.2132C8.34435 19.1222 8.39962 19.0395 8.4693 18.9698L15.4396 12.0004L8.4693 5.03104C8.32857 4.89031 8.24951 4.69944 8.24951 4.50042C8.24951 4.30139 8.32857 4.11052 8.4693 3.96979C8.61003 3.82906 8.80091 3.75 8.99993 3.75C9.19895 3.75 9.38982 3.82906 9.53055 3.96979L17.0306 11.4698C17.1003 11.5394 17.1556 11.6222 17.1933 11.7132C17.2311 11.8043 17.2505 11.9019 17.2505 12.0004C17.2505 12.099 17.2311 12.1966 17.1933 12.2876C17.1556 12.3787 17.1003 12.4614 17.0306 12.531Z" fill="currentColor" />
                                </svg></div>
                        </div>
                    <?php endif; ?>
                    </div>
                </div>
                <div class="swiper bt-product-testimonial--images js-testimonial-images">
                    <div class="swiper-wrapper">
                        <?php if (!empty($settings['testimonial_items'])) : ?>
                            <?php foreach ($settings['testimonial_items'] as $item) : ?>
                                <div class="bt-product-testimonial--product swiper-slide">
                                    <?php if (!empty($item['testimonial_image']['url'])) :
                                        $attachment = wp_get_attachment_image_src($item['testimonial_image']['id'], $settings['thumbnail_size']);
                                        $image_url = $attachment ? $attachment[0] : $item['testimonial_image']['url'];
                                    ?>
                                        <div class="bt-image-cover">
                                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($item['testimonial_author']); ?>">
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($item['id_product'])) :
                                        $product = wc_get_product($item['id_product']);
                                        if ($product) : ?>
                                            <a href="<?php echo esc_url($product->get_permalink()); ?>" class="product-info">
                                                <div class="product-img">
                                                    <?php echo wp_kses_post($product->get_image()); ?>
                                                </div>
                                                <div class="product-content">
                                                    <h3 class="product-title"><?php echo esc_html($product->get_name()); ?></h3>
                                                    <?php echo '<div class="product-price">' . $product->get_price_html() . '</div>'; ?>
                                                </div>
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
<?php
    }



    protected function content_template() {}
}
