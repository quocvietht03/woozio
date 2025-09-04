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
                    'size' => 0.67,
                ],
                'range' => [
                    'px' => [
                        'min' => 0.3,
                        'max' => 2,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-product-testimonial--images .bt-image-cover' => 'padding-bottom: calc( {{SIZE}} * 100% );',
                ],
            ]
        );
        $this->add_control(
            'image_position',
            [
                'label' => __('Image Position', 'woozio'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'row-reverse' => __('Left', 'woozio'),
                    'row' => __('Right', 'woozio'),
                ],
                'default' => 'row-reverse',
                'selectors' => [
                    '{{WRAPPER}} .bt-product-testimonial' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'gap',
            [
                'label' => __('Gap', 'woozio'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-product-testimonial' => '--column-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'background_color',
            [
                'label' => __('Background Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .bt-product-testimonial' => 'background-color: {{VALUE}};',
                ],
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
            'slider_pagination',
            [
                'label' => __('Show Pagination', 'woozio'),
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
            'content_background_color',
            [
                'label' => __('Content Background Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .bt-product-testimonial--content' => 'background-color: {{VALUE}};',
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

        // Pagination Style Section
        $this->start_controls_section(
            'section_pagination_style',
            [
                'label' => __('Pagination Style', 'woozio'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'slider_pagination' => 'yes',
                ],
            ]
        );
        $this->add_responsive_control(
            'pagination_margin_top',
            [
                'label' => __('Pagination Margin Top', 'woozio'),
                'type' => Controls_Manager::NUMBER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .bt-swiper-pagination' => 'margin-top: {{VALUE}}px;',
                ],
            ]
        );
        $this->add_control(
            'pagination_color',
            [
                'label' => __('Pagination Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-swiper-pagination .swiper-pagination-bullet' => 'background: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'pagination_active_color',
            [
                'label' => __('Pagination Active Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-swiper-pagination .swiper-pagination-bullet-active' => 'background: {{VALUE}};',
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
                        <div class="swiper-wrapper">
                            <?php if (!empty($settings['testimonial_items'])) : ?>
                                <?php foreach ($settings['testimonial_items'] as $item) : ?>
                                    <div class="swiper-slide">
                                        <div class="bt-product-testimonial--item">
                                            <?php if (!empty($item['testimonial_rating'])) : ?>
                                                <div class="bt-product-testimonial--rating">
                                                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                        <?php if ($i <= $item['testimonial_rating']) : ?>
                                                            <span class="star filled"><svg xmlns="http://www.w3.org/2000/svg" width="26" height="25" viewBox="0 0 26 25" fill="none">
                                                                    <path d="M24.6254 11.5605L19.7035 15.8075L21.2031 22.1589C21.2858 22.5037 21.2645 22.8653 21.1418 23.198C21.0192 23.5306 20.8007 23.8195 20.5139 24.0281C20.2272 24.2366 19.885 24.3555 19.5308 24.3698C19.1765 24.384 18.8259 24.2929 18.5234 24.108L12.9999 20.7086L7.47321 24.108C7.17071 24.2918 6.82058 24.3821 6.4669 24.3673C6.11322 24.3526 5.7718 24.2335 5.48565 24.0251C5.1995 23.8167 4.98141 23.5283 4.85883 23.1963C4.73625 22.8642 4.71467 22.5032 4.79681 22.1589L6.30181 15.8075L1.37993 11.5605C1.11229 11.3292 0.918725 11.0241 0.823413 10.6834C0.728101 10.3428 0.735265 9.98158 0.844011 9.64495C0.952757 9.30833 1.15826 9.01121 1.43487 8.79069C1.71147 8.57016 2.04692 8.43602 2.39931 8.405L8.85243 7.88438L11.3418 1.86C11.4765 1.53168 11.7059 1.25084 12.0006 1.05319C12.2954 0.855535 12.6423 0.75 12.9972 0.75C13.3521 0.75 13.699 0.855535 13.9937 1.05319C14.2885 1.25084 14.5178 1.53168 14.6526 1.86L17.1409 7.88438L23.594 8.405C23.9471 8.43487 24.2835 8.56826 24.5611 8.78848C24.8387 9.0087 25.0452 9.30594 25.1546 9.64297C25.264 9.98 25.2716 10.3418 25.1762 10.6831C25.0809 11.0244 24.887 11.33 24.6188 11.5616L24.6254 11.5605Z" fill="#181818" />
                                                                </svg></span>
                                                        <?php else : ?>
                                                            <span class="star">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="25" viewBox="0 0 26 25" fill="none">
                                                                    <path d="M24.6254 11.5605L19.7035 15.8075L21.2031 22.1589C21.2858 22.5037 21.2645 22.8653 21.1418 23.198C21.0192 23.5306 20.8007 23.8195 20.5139 24.0281C20.2272 24.2366 19.885 24.3555 19.5308 24.3698C19.1765 24.384 18.8259 24.2929 18.5234 24.108L12.9999 20.7086L7.47321 24.108C7.17071 24.2918 6.82058 24.3821 6.4669 24.3673C6.11322 24.3526 5.7718 24.2335 5.48565 24.0251C5.1995 23.8167 4.98141 23.5283 4.85883 23.1963C4.73625 22.8642 4.71467 22.5032 4.79681 22.1589L6.30181 15.8075L1.37993 11.5605C1.11229 11.3292 0.918725 11.0241 0.823413 10.6834C0.728101 10.3428 0.735265 9.98158 0.844011 9.64495C0.952757 9.30833 1.15826 9.01121 1.43487 8.79069C1.71147 8.57016 2.04692 8.43602 2.39931 8.405L8.85243 7.88438L11.3418 1.86C11.4765 1.53168 11.7059 1.25084 12.0006 1.05319C12.2954 0.855535 12.6423 0.75 12.9972 0.75C13.3521 0.75 13.699 0.855535 13.9937 1.05319C14.2885 1.25084 14.5178 1.53168 14.6526 1.86L17.1409 7.88438L23.594 8.405C23.9471 8.43487 24.2835 8.56826 24.5611 8.78848C24.8387 9.0087 25.0452 9.30594 25.1546 9.64297C25.264 9.98 25.2716 10.3418 25.1762 10.6831C25.0809 11.0244 24.887 11.33 24.6188 11.5616L24.6254 11.5605Z" fill="#E9E9E9" />
                                                                </svg></span>
                                                        <?php endif; ?>
                                                    <?php endfor; ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($item['testimonial_text'])) : ?>
                                                <div class="bt-product-testimonial--text"><?php echo esc_html($item['testimonial_text']); ?></div>
                                            <?php endif; ?>
                                            <?php if (!empty($item['testimonial_author'])) : ?>
                                                <div class="bt-product-testimonial--author"><?php echo esc_html($item['testimonial_author']); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>

                        </div>
                        <?php if ($settings['slider_pagination'] === 'yes') : ?>
                            <div class="bt-swiper-pagination"></div>
                        <?php endif; ?>

                    </div>
                </div>
                <div class="swiper bt-product-testimonial--images js-testimonial-images">
                    <div class="swiper-wrapper">
                        <?php if (!empty($settings['testimonial_items'])) : ?>
                            <?php foreach ($settings['testimonial_items'] as $item) : ?>
                                <div class="bt-product-testimonial--product swiper-slide">
                                    <div class="bt-image-cover">
                                        <?php
                                        if (!empty($item['testimonial_image']['id'])) {
                                            echo wp_get_attachment_image($item['testimonial_image']['id'], $settings['thumbnail_size']);
                                        } else {
                                            if (!empty($item['testimonial_image']['url'])) {
                                                echo '<img src="' . esc_url($item['testimonial_image']['url']) . '" alt="' . esc_html__('Awaiting testimonial image', 'woozio') . '">';
                                            } else {
                                                echo '<img src="' . esc_url(Utils::get_placeholder_image_src()) . '" alt="' . esc_html__('Awaiting testimonial image', 'woozio') . '">';
                                            }
                                        }
                                        ?>
                                    </div>
                                    <?php if (!empty($item['id_product'])) :
                                        $product = wc_get_product($item['id_product']);
                                        if ($product) : ?>
                                            <div class="bt-product-item-minimal active"
                                                data-product-id="<?php echo esc_attr($item['id_product']); ?>">
                                                <div class="bt-product-thumbnail">
                                                    <a href="<?php echo esc_url($product->get_permalink()); ?>">
                                                        <?php
                                                        if (has_post_thumbnail($item['id_product'])) {
                                                            echo get_the_post_thumbnail($item['id_product'], 'thumbnail');
                                                        } else {
                                                            echo '<img src="' . esc_url(wc_placeholder_img_src('woocommerce_thumbnail')) . '" alt="' . esc_html__('Awaiting product image', 'woozio') . '" class="wp-post-image" />';
                                                        }
                                                        ?>
                                                    </a>
                                                </div>
                                                <div class="bt-product-content">
                                                    <h4 class="bt-product-title"><a href="<?php echo esc_url($product->get_permalink()); ?>" class="bt-product-link"><?php echo esc_html($product->get_name()); ?></a></h4>
                                                    <div class="bt-product-price"><?php echo wp_kses_post($product->get_price_html()); ?></div>
                                                    <div class="bt-product-add-to-cart">
                                                        <?php if ($product->is_type('simple') && $product->is_purchasable() && $product->is_in_stock()) : ?>
                                                            <a href="?add-to-cart=<?php echo esc_attr($product->get_id()); ?>" aria-describedby="woocommerce_loop_add_to_cart_link_describedby_<?php echo esc_attr($product->get_id()); ?>" data-quantity="1" class="bt-button product_type_simple add_to_cart_button ajax_add_to_cart bt-button-hover" data-product_id="<?php echo esc_attr($product->get_id()); ?>" data-product_sku="" rel="nofollow"><?php echo esc_html__('Add to cart', 'woozio') ?></a>
                                                        <?php else : ?>
                                                            <a href="<?php echo esc_url($product->get_permalink()); ?>" class="bt-button bt-view-product"><?php echo esc_html__('View Product', 'woozio'); ?></a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
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
