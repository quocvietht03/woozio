<?php

namespace WoozioElementorWidgets\Widgets\HotspotProductNormal;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

class Widget_HotspotProductNormal extends Widget_Base
{

    public function get_name()
    {
        return 'bt-hotspot-product-normal';
    }

    public function get_title()
    {
        return __('Hotspot Product (Normal)', 'woozio');
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

        wp_reset_postdata();

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
            'sub_heading',
            [
                'label' => __('Sub Heading', 'woozio'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __('Enter sub heading', 'woozio'),
                'label_block' => true,
            ]
        );
        $this->add_control(
            'heading',
            [
                'label' => __('Heading', 'woozio'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __('Enter heading', 'woozio'),
                'label_block' => true,
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
            'image_position',
            [
                'label' => __('Image Position', 'woozio'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'row-reverse' => [
                        'title' => __('Left', 'woozio'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'row' => [
                        'title' => __('Right', 'woozio'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'row',
                'toggle' => false,
                'label_block' => false,
                'selectors' => [
                    '{{WRAPPER}} .bt-hotspot-product-normal' => 'flex-direction: {{VALUE}};',
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
                    '{{WRAPPER}} {{CURRENT_ITEM}}.bt-hotspot-point' => 'left: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} {{CURRENT_ITEM}}.bt-hotspot-point' => 'top: {{SIZE}}{{UNIT}};',
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
    }
    protected function register_style_content_section_controls()
    {
        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Style', 'woozio'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'content_position',
            [
                'label' => __('Content Position', 'woozio'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'woozio'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'woozio'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'woozio'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => false,
                'selectors' => [
                    '{{WRAPPER}} .bt-hotspot-product-normal__list-products' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'sub_heading_color',
            [
                'label' => __('Sub Heading Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-hotspot-product-normal__list-products .bt-list-header .bt-sub-heading' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
			[
				'name'     => 'sub_heading_typography',
				'label'    => __('Typography', 'woozio'),
				'default'  => '',
                'selector' => '{{WRAPPER}} .bt-hotspot-product-normal__list-products .bt-list-header .bt-sub-heading',
            ]
        );
        $this->add_control(
            'heading_color',
            [
                'label' => __('Heading Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-hotspot-product-normal__list-products .bt-list-header .bt-heading' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'label' => __('Typography', 'woozio'),
                'default'  => '',
                'selector' => '{{WRAPPER}} .bt-hotspot-product-normal__list-products .bt-list-header .bt-heading',
            ]
        );
        $this->end_controls_section();

    }

    protected function register_controls()
    {
        $this->register_layout_section_controls();
        $this->register_style_content_section_controls();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $attachment = wp_get_attachment_image_src($settings['hotspot_image']['id'], $settings['thumbnail_size']);
        global $product;
?>
        <div class="bt-elwg-hotspot-product-normal--default">
            <div class="bt-hotspot-product-normal">
                <div class="bt-hotspot-product-normal__list-products">
                    <div class="bt-list-header">
                        <?php if (!empty($settings['sub_heading'])) : ?>
                            <p class="bt-sub-heading"><?php echo esc_html($settings['sub_heading']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($settings['heading'])) : ?>
                            <h2 class="bt-heading"><?php echo esc_html($settings['heading']); ?></h2>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($settings['hotspot_items'])) : ?>
                        <ul class="bt-hotspot-product-list">
                            <?php foreach ($settings['hotspot_items'] as $index => $item) :
                                $product = wc_get_product($item['id_product']);
                                if ($product) : ?>
                                    <li class="bt-hotspot-product-list__item" data-product-id="<?php echo esc_attr($item['id_product']); ?>">
                                        <div class="bt-number-product">
                                            <?php echo $index + 1; ?>
                                        </div>
                                        <a class="bt-hotspot-product-thumbnail" href="<?php echo esc_url($product->get_permalink()); ?>">
                                            <?php echo get_the_post_thumbnail($item['id_product'], 'thumbnail'); ?>
                                        </a>
                                        <div class="bt-product-content">
                                            <div class="bt-product-content__inner">
                                                <h4 class="bt-product-name">
                                                    <a href="<?php echo esc_url($product->get_permalink()); ?>">
                                                        <?php echo esc_html($product->get_name()); ?>
                                                    </a>
                                                </h4>
                                                <?php
                                                if ($product->is_type('variable')) {
                                                    do_action('woozio_woocommerce_template_single_add_to_cart');
                                                }
                                                ?>
                                            </div>

                                            <p class="bt-price"><?php echo $product->get_price_html(); ?></p>
                                        </div>
                                    </li>
                            <?php endif;
                            endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    <div class="bt-button-wrapper">
                        <?php
                        // Build $product_ids array with proper structure
                        $product_ids = [];
                        if (!empty($settings['hotspot_items'])) {
                            foreach ($settings['hotspot_items'] as $item) {
                                $product_ids[] = [
                                    'product_id'   => $item['id_product'],
                                    'variation_id' => 0,
                                ];
                            }
                        }
                        ?>
                        <a class="bt-button bt-button-add-set-to-cart" data-ids="<?php echo esc_attr(json_encode($product_ids)); ?>" href="#">
                            <?php esc_html_e('Add set to cart', 'woozio'); ?>
                        </a>
                    </div>
                </div>
                <div class="bt-hotspot-product-normal__image">
                    <?php if (!empty($settings['hotspot_image']['url'])) : ?>
                        <div class="bt-hotspot-image" style="position: relative;">
                            <?php
                            $attachment = wp_get_attachment_image_src($settings['hotspot_image']['id'], $settings['thumbnail_size']);
                            if ($attachment) {
                                echo '<img src="' . esc_url($attachment[0]) . '" alt="">';
                            } else {
                                echo '<img src="' . esc_url($settings['hotspot_image']['url']) . '" alt="">';
                            }
                            ?>
                            <?php if (!empty($settings['hotspot_items'])) : ?>
                                <div class="bt-hotspot-points">
                                    <?php foreach ($settings['hotspot_items'] as $index => $item) :
                                        $product = wc_get_product($item['id_product']);
                                        if ($product) :

                                    ?>
                                            <div class="bt-hotspot-point elementor-repeater-item-<?php echo esc_attr($item['_id']); ?>"
                                                data-product-id="<?php echo esc_attr($item['id_product']); ?>">
                                                <div class="bt-hotspot-marker"> <?php echo $index + 1; ?>
                                                </div>
                                            </div>
                                    <?php endif;
                                    endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
<?php
    }

    protected function content_template() {}
}
