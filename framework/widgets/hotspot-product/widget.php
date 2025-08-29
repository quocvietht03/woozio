<?php

namespace WoozioElementorWidgets\Widgets\HotspotProduct;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_BBorder;
use Elementor\Group_Control_Box_Shadow;

class Widget_HotspotProduct extends Widget_Base
{

    public function get_name()
    {
        return 'bt-hotspot-product';
    }

    public function get_title()
    {
        return __('Hotspot Product (slider)', 'woozio');
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
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_type',
                    'field'    => 'slug',
                    'terms'    => 'simple',
                ),
            ),
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
                'label' => __('Hotspot', 'woozio'),
            ]
        );
        $this->add_control(
            'hotspot_image',
            [
                'label' => __('Image', 'woozio'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
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
            'image_width',
            [
                'label' => __('Image Width', 'woozio'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 100,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-hotspot-image img' => 'width: {{SIZE}}{{UNIT}}; margin-left:calc(-1 * ({{SIZE}}{{UNIT}} - 100%) / 2);',
                ],
            ]
        );
        $this->add_control(
            'tooltip_layout',
            [
                'label' => __('Layout Tooltip', 'woozio'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => __('Default', 'woozio'),
                    'style1' => __('Style 1', 'woozio'),
                ],
            ]
        );

        $repeater = new Repeater();
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
            'hotspot_position_x',
            [
                'label' => __('X Position', 'woozio'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.bt-hotspot-point' => 'left: {{SIZE}}{{UNIT}}; --hotspot-translate-x: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $repeater->add_control(
            'hotspot_position_y',
            [
                'label' => __('Y Position', 'woozio'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.bt-hotspot-point' => 'top: {{SIZE}}{{UNIT}}; --hotspot-translate-y: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'hotspot_items',
            [
                'label' => __('Hotspot', 'woozio'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'id_product' => '',
                        'hotspot_position_x' => [
                            'unit' => '%',
                            'size' => 10,
                        ],
                        'hotspot_position_y' => [
                            'unit' => '%',
                            'size' => 10,
                        ]
                    ],
                    [
                        'id_product' => '',
                        'hotspot_position_x' => [
                            'unit' => '%',
                            'size' => 70,
                        ],
                        'hotspot_position_y' => [
                            'unit' => '%',
                            'size' => 30,
                        ]
                    ],
                    [
                        'id_product' => '',
                        'hotspot_position_x' => [
                            'unit' => '%',
                            'size' => 50,
                        ],
                        'hotspot_position_y' => [
                            'unit' => '%',
                            'size' => 90,
                        ]
                    ],

                ],
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
            'show_slider',
            [
                'label' => __('Show Slider', 'woozio'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'woozio'),
                'label_off' => __('No', 'woozio'),
                'default' => 'yes',
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'slider_heading',
            [
                'label' => __('Heading', 'woozio'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Slider Heading', 'woozio'),
                'label_block' => true,
                'condition' => [
                    'show_slider' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'slider_description',
            [
                'label' => __('Description', 'woozio'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('Slider description text goes here', 'woozio'),
                'label_block' => true,
                'condition' => [
                    'show_slider' => 'yes',
                ],
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
                'condition' => [
                    'show_slider' => 'yes',
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
                'condition' => [
                    'show_slider' => 'yes',
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
                'condition' => [
                    'show_slider' => 'yes',
                ],
            ]
        );
        $this->add_responsive_control(
            'slider_spacebetween',
            [
                'label' => __('Slider SpaceBetween', 'woozio'),
                'type' => Controls_Manager::NUMBER,
                'default' => 20,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'devices' => ['desktop', 'tablet', 'mobile'],
                'desktop_default' => 30,
                'tablet_default' => 20,
                'mobile_default' => 20,
                'condition' => [
                    'show_slider' => 'yes',
                ],
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
                'condition' => [
                    'show_slider' => 'yes',
                ],
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
            'show_add_to_cart',
            [
                'label' => __('Show Set to Cart', 'woozio'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'woozio'),
                'label_off' => __('No', 'woozio'),
                'default' => 'yes',
                'condition' => [
                    'show_slider' => 'yes',
                ],
            ]
        );
        $this->end_controls_section();
    }

    protected function register_style_section_controls()
    {
        $this->start_controls_section(
            'section_style_slider_content',
            [
                'label' => __('Slider Content', 'woozio'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_slider' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'heading_style',
            [
                'label' => __('Heading', 'woozio'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => __('Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-hotspot-slider--heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'selector' => '{{WRAPPER}} .bt-hotspot-slider--heading',
            ]
        );

        $this->add_control(
            'description_style',
            [
                'label' => __('Description', 'woozio'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => __('Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-hotspot-slider--description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .bt-hotspot-slider--description',
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
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $attachment = wp_get_attachment_image_src($settings['hotspot_image']['id'], $settings['thumbnail_size']);
?>
        <div class="bt-elwg-hotspot-product--default <?php echo esc_attr(($settings['show_slider'] !== 'yes') ? 'bt-no-slider' : ''); ?>">
            <div class="bt-hotspot-product bt-tooltip-<?php echo esc_attr($settings['tooltip_layout']); ?>">
                <div class="bt-hotspot-product--image">
                    <?php if (!empty($settings['hotspot_image']['url'])) : ?>
                        <div class="bt-hotspot-image">
                            <?php
                            $attachment = wp_get_attachment_image_src($settings['hotspot_image']['id'], $settings['thumbnail_size']);
                            if ($attachment) {
                                echo '<img src="' . esc_url($attachment[0]) . '" alt="">';
                            } else {
                                echo '<img src="' . esc_url($settings['hotspot_image']['url']) . '" alt="">';
                            }
                            ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($settings['hotspot_items'])) : ?>
                        <div class="bt-hotspot-points">
                            <?php foreach ($settings['hotspot_items'] as $item) : ?>
                                <?php
                                $product = wc_get_product($item['id_product']);
                                if ($product) :
                                ?>
                                    <div class="bt-hotspot-point elementor-repeater-item-<?php echo esc_attr($item['_id']); ?>"
                                        data-product-id="<?php echo esc_attr($item['id_product']); ?>">
                                        <div class="bt-hotspot-marker"></div>
                                        <div class="bt-hotspot-product-info">
                                            <a class="bt-hotspot-product-thumbnail" href="<?php echo esc_url($product->get_permalink()); ?>"><?php echo get_the_post_thumbnail($item['id_product'], 'medium'); ?></a>
                                            <div class="bt-product-content">
                                                <h4><a href="<?php echo esc_url($product->get_permalink()); ?>"><?php echo esc_html($product->get_name()); ?></a></h4>
                                                <?php echo '<p class="bt-price">' . $product->get_price_html() . '</p>'; ?>
                                                <a class="btn bt-product-quick-view-btn" href="#" data-id="<?php echo esc_attr($item['id_product']); ?>">
                                                    <?php esc_html_e('Quick View', 'woozio'); ?>
                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if ($settings['show_slider'] === 'yes') : ?>
                    <div class="bt-hotspot-product--slider">
                        <?php
                        $slider_settings = [
                            'autoplay' => isset($settings['slider_autoplay']) && $settings['slider_autoplay'] === 'yes',
                            'speed' => isset($settings['slider_speed']) ? $settings['slider_speed'] : 500,
                            'loop' => isset($settings['slider_loop']) && $settings['slider_loop'] === 'yes',
                            'spaceBetween' => [
                                'desktop' => isset($settings['slider_spacebetween']) ? $settings['slider_spacebetween'] : 30,
                                'tablet' => isset($settings['slider_spacebetween_tablet']) ? $settings['slider_spacebetween_tablet'] : 20,
                                'mobile' => isset($settings['slider_spacebetween_mobile']) ? $settings['slider_spacebetween_mobile'] : 10
                            ],
                        ];
                        $classes = ['bt-hotspot-slider--inner', 'swiper'];
                        if ($settings['slider_arrows_hidden_mobile'] === 'yes') {
                            $classes[] = 'bt-hidden-arrow-mobile';
                        }
                        if ($settings['slider_dots_only_mobile'] === 'yes') {
                            $classes[] = 'bt-only-dot-mobile';
                        }
                        ?>
                        <div class="bt-hotspot-slider" data-slider-settings='<?php echo json_encode($slider_settings); ?>'>
                            <?php if (!empty($settings['slider_heading'])) : ?>
                                <h3 class="bt-hotspot-slider--heading"><?php echo esc_html($settings['slider_heading']); ?></h3>
                            <?php endif; ?>
                            <?php if (!empty($settings['slider_description'])) : ?>
                                <p class="bt-hotspot-slider--description"><?php echo esc_html($settings['slider_description']); ?></p>
                            <?php endif; ?>
                            <div class="<?php echo esc_attr(implode(' ', $classes)); ?>">
                                <div class="bt-hotspot-slider--wrap swiper-wrapper">
                                    <?php foreach ($settings['hotspot_items'] as $item) : ?>
                                        <?php
                                        $product = wc_get_product($item['id_product']);
                                        if ($product) :
                                        ?>
                                            <div class="bt-slider-item swiper-slide">
                                                <?php
                                                $post_object = get_post($item['id_product']);
                                                setup_postdata($GLOBALS['post'] = &$post_object);
                                                wc_get_template_part('content', 'product');
                                                wp_reset_postdata();
                                                ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                                <?php if ($settings['slider_arrows'] === 'yes') : ?>
                                    <div class="bt-swiper-navigation">
                                        <div class="bt-nav bt-button-prev">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M15.5307 18.9698C15.6004 19.0395 15.6557 19.1222 15.6934 19.2132C15.7311 19.3043 15.7505 19.4019 15.7505 19.5004C15.7505 19.599 15.7311 19.6965 15.6934 19.7876C15.6557 19.8786 15.6004 19.9614 15.5307 20.031C15.461 20.1007 15.3783 20.156 15.2873 20.1937C15.1962 20.2314 15.0986 20.2508 15.0001 20.2508C14.9016 20.2508 14.804 20.2314 14.7129 20.1937C14.6219 20.156 14.5392 20.1007 14.4695 20.031L6.96948 12.531C6.89974 12.4614 6.84443 12.3787 6.80668 12.2876C6.76894 12.1966 6.74951 12.099 6.74951 12.0004C6.74951 11.9019 6.76894 11.8043 6.80668 11.7132C6.84443 11.6222 6.89974 11.5394 6.96948 11.4698L14.4695 3.96979C14.6102 3.82906 14.8011 3.75 15.0001 3.75C15.1991 3.75 15.39 3.82906 15.5307 3.96979C15.6715 4.11052 15.7505 4.30139 15.7505 4.50042C15.7505 4.69944 15.6715 4.89031 15.5307 5.03104L8.56041 12.0004L15.5307 18.9698Z" fill="currentColor" />
                                            </svg>
                                        </div>
                                        <div class="bt-nav bt-button-next">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M17.0306 12.531L9.53055 20.031C9.46087 20.1007 9.37815 20.156 9.2871 20.1937C9.19606 20.2314 9.09847 20.2508 8.99993 20.2508C8.90138 20.2508 8.8038 20.2314 8.71276 20.1937C8.62171 20.156 8.53899 20.1007 8.4693 20.031C8.39962 19.9614 8.34435 19.8786 8.30663 19.7876C8.26892 19.6965 8.24951 19.599 8.24951 19.5004C8.24951 19.4019 8.26892 19.3043 8.30663 19.2132C8.34435 19.1222 8.39962 19.0395 8.4693 18.9698L15.4396 12.0004L8.4693 5.03104C8.32857 4.89031 8.24951 4.69944 8.24951 4.50042C8.24951 4.30139 8.32857 4.11052 8.4693 3.96979C8.61003 3.82906 8.80091 3.75 8.99993 3.75C9.19895 3.75 9.38982 3.82906 9.53055 3.96979L17.0306 11.4698C17.1003 11.5394 17.1556 11.6222 17.1933 11.7132C17.2311 11.8043 17.2505 11.9019 17.2505 12.0004C17.2505 12.099 17.2311 12.1966 17.1933 12.2876C17.1556 12.3787 17.1003 12.4614 17.0306 12.531Z" fill="currentColor" />
                                            </svg>
                                        </div>
                                    </div>
                                <?php endif;
                                // pagination
                                if ($settings['slider_dots'] === 'yes') {
                                    echo '<div class="bt-swiper-pagination swiper-pagination"></div>';
                                }
                                ?>

                            </div>
                            <?php if ($settings['show_add_to_cart'] === 'yes') : ?>
                                <div class="bt-add-to-cart-wrapper">
                                    <button type="button" class="bt-add-to-cart-btn bt-button-hover">
                                        <span class="bt-btn-text"><?php echo esc_html__('Add set to cart - ', 'woozio'); ?></span>
                                        <?php
                                        $total_price = 0;
                                        $total_regular_price = 0;
                                        $product_ids = [];
                                        foreach ($settings['hotspot_items'] as $item) {
                                            $product = wc_get_product($item['id_product']);
                                            if ($product) {
                                                $product_ids[] = $item['id_product'];
                                                if ($product->is_on_sale()) {
                                                    $total_regular_price += (float)$product->get_regular_price();
                                                    $total_price += (float)$product->get_sale_price();
                                                } else {
                                                    $total_price += (float)$product->get_regular_price();
                                                    $total_regular_price += (float)$product->get_regular_price();
                                                }
                                            }
                                        }
                                        ?>
                                        <span class="bt-btn-price" data-ids="<?php echo esc_attr(json_encode($product_ids)); ?>"><?php echo wc_price($total_price); ?></span>
                                        <?php if ($total_regular_price > 0 && $total_regular_price != $total_price) : ?>
                                            <span class="bt-btn-regular-price"><?php echo wc_price($total_regular_price); ?></span>
                                        <?php endif; ?>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
<?php
    }



    protected function content_template() {}
}
