<?php

namespace WoozioElementorWidgets\Widgets\BundleSave;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;


class Widget_BundleSave extends Widget_Base
{

    public function get_name()
    {
        return 'bt-bundle-save';
    }

    public function get_title()
    {
        return __('Bundle Save', 'woozio');
    }

    public function get_icon()
    {
        return 'eicon-products';
    }

    public function get_categories()
    {
        return ['woozio'];
    }

    public function get_script_depends()
    {
        return ['elementor-widgets'];
    }

    public function get_supported_products()
    {
        $supported_products = [];

        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'post_status' => 'publish'
        );

        $products = get_posts($args);

        if (!empty($products)) {
            foreach ($products as $product) {
                $product_obj = wc_get_product($product->ID);

                if (!$product_obj) {
                    continue;
                }

                // If simple product, add it
                if ($product_obj->is_type('simple')) {
                    $supported_products[$product->ID] = $product->post_title;
                }

                // If variable product, only add variations (not parent)
                if ($product_obj->is_type('variable')) {
                    $variations = $product_obj->get_available_variations();
                    foreach ($variations as $variation) {
                        $variation_obj = wc_get_product($variation['variation_id']);
                        if ($variation_obj) {
                            $attributes = [];
                            foreach ($variation['attributes'] as $attr_name => $attr_value) {
                                $attr_label = str_replace('attribute_', '', $attr_name);
                                $attr_label = str_replace('pa_', '', $attr_label);
                                $attr_label = ucfirst($attr_label);

                                // Get term name if it's a taxonomy
                                if (taxonomy_exists($attr_name)) {
                                    $term = get_term_by('slug', $attr_value, $attr_name);
                                    $attr_value = $term ? $term->name : $attr_value;
                                }

                                $attributes[] = $attr_label . ': ' . ucfirst($attr_value);
                            }
                            $variation_name = $product->post_title . ' - ' . implode(', ', $attributes);
                            $supported_products[$variation['variation_id']] = $variation_name;
                        }
                    }
                }
            }
        }

        return $supported_products;
    }

    protected function register_content_section_controls()
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'woozio'),
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Title', 'woozio'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Bundle Save', 'woozio'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __('Description', 'woozio'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('Save more when you shop in bundles.', 'woozio'),
                'rows' => 3,
            ]
        );

        $this->add_control(
            'show_discount_bar',
            [
                'label' => __('Show Discount Progress Bar', 'woozio'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'woozio'),
                'label_off' => __('No', 'woozio'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'discount_text',
            [
                'label' => __('Discount Text', 'woozio'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Buy {count} products and save up to {discount}%', 'woozio'),
                'label_block' => true,
                'description' => __('Use {count} for product count and {discount} for percentage', 'woozio'),
                'condition' => [
                    'show_discount_bar' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'add_more_text',
            [
                'label' => __('Add More Button Text', 'woozio'),
                'type' => Controls_Manager::TEXT,
                'default' => __('+ Add More', 'woozio'),
                'label_block' => true,
            ]
        );
        $this->add_control(
            'button_text',
            [
                'label' => __('Add to Cart Button Text', 'woozio'),
                'type' => Controls_Manager::TEXT,
                'default' => __('ADD SET TO CART', 'woozio'),
                'label_block' => true,
            ]
        );
        $this->end_controls_section();
    }

    protected function register_products_section_controls()
    {
        $this->start_controls_section(
            'section_products',
            [
                'label' => __('Products', 'woozio'),
            ]
        );


        $repeater = new Repeater();

        $repeater->add_control(
            'product_id',
            [
                'label' => __('Select Product', 'woozio'),
                'type' => Controls_Manager::SELECT2,
                'options' => $this->get_supported_products(),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'products_items',
            [
                'label' => __('Products Items', 'woozio'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'product_id' => '',
                    ],
                    [
                        'product_id' => '',
                    ],
                    [
                        'product_id' => '',
                    ],
                ],
                'title_field' => '{{{ "Product Item" }}}',
            ]
        );
        $this->end_controls_section();
    }

    protected function register_style_section_controls()
    {
        // Container Style
        $this->start_controls_section(
            'section_style_container',
            [
                'label' => __('Container', 'woozio'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'container_max_width',
            [
                'label' => __('Max Width', 'woozio'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-bundle-save' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'selector' => '{{WRAPPER}} .bt-bundle-save',
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => __('Padding', 'woozio'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .bt-bundle-save' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_box_shadow',
                'selector' => '{{WRAPPER}} .bt-bundle-save',
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'section_style_content',
            [
                'label' => __('Content Style', 'woozio'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-bundle-save--title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .bt-bundle-save--title',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => __('Description Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-bundle-save--description' => 'color: {{VALUE}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .bt-bundle-save--description',
            ]
        );

        $this->add_control(
            'add_more_btn_color',
            [
                'label' => __('Add More Button Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-bundle-save--add-more-btn' => 'color: {{VALUE}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'add_more_btn_typography',
                'selector' => '{{WRAPPER}} .bt-bundle-save--add-more-btn',
            ]
        );

        $this->add_control(
            'discount_color',
            [
                'label' => __('Discount Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-bundle-save--discount-bar .bt-discount-text' => 'color: {{VALUE}};',
                ],
                'separator' => 'before'
            ]
        );
        $this->add_control(
            'discount_color_highlight',
            [
                'label' => __('Discount Color Highlight', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-bundle-save--discount-bar .bt-discount-text span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'discount_typography',
                'selector' => '{{WRAPPER}} .bt-bundle-save--discount-bar .bt-discount-text',
            ]
        );

        $this->add_control(
            'total_color',
            [
                'label' => __('Total Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-subtotal-label' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bt-subtotal-amount' => 'color: {{VALUE}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'total_typography',
                'selector' => '{{WRAPPER}} .bt-subtotal-label, {{WRAPPER}} .bt-subtotal-amount',
            ]
        );

        $this->end_controls_section();


        // Button Style
        $this->start_controls_section(
            'section_style_button',
            [
                'label' => __('Button', 'woozio'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('button_tabs');

        $this->start_controls_tab(
            'button_normal',
            [
                'label' => __('Normal', 'woozio'),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => __('Text Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .bt-bundle-save--add-cart-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_color',
            [
                'label' => __('Background Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .bt-bundle-save--add-cart-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_hover',
            [
                'label' => __('Hover', 'woozio'),
            ]
        );

        $this->add_control(
            'button_hover_text_color',
            [
                'label' => __('Text Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-bundle-save--add-cart-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_background_color',
            [
                'label' => __('Background Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-bundle-save--add-cart-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .bt-bundle-save--add-cart-btn',
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => __('Padding', 'woozio'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .bt-bundle-save--add-cart-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function register_controls()
    {
        $this->register_content_section_controls();
        $this->register_products_section_controls();
        $this->register_style_section_controls();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        if (!class_exists('WooCommerce')) {
            echo '<p>' . __('Please install WooCommerce plugin', 'woozio') . '</p>';
            return;
        }

        $products_items = !empty($settings['products_items']) ? $settings['products_items'] : [];

        if (empty($products_items)) {
            echo '<p>' . __('Please add products to this bundle', 'woozio') . '</p>';
            return;
        }

        // Extract product IDs from repeater
        $bundle_products = [];
        foreach ($products_items as $item) {
            if (!empty($item['product_id'])) {
                $bundle_products[] = $item['product_id'];
            }
        }

        if (empty($bundle_products)) {
            echo '<p>' . __('Please select valid products', 'woozio') . '</p>';
            return;
        }

        // Get first 3 products as default
        $default_products = array_slice($bundle_products, 0, 3);

        $widget_id = $this->get_id();
?>
        <div class="bt-elwg-bundle-save--default">
            <div class="bt-bundle-save" data-widget-id="<?php echo esc_attr($widget_id); ?>">
                <div class="bt-bundle-save--header">
                    <?php if (!empty($settings['title'])) : ?>
                        <h3 class="bt-bundle-save--title"><?php echo esc_html($settings['title']); ?></h3>
                    <?php endif; ?>

                    <?php if (!empty($settings['description'])) : ?>
                        <p class="bt-bundle-save--description"><?php echo esc_html($settings['description']); ?></p>
                    <?php endif; ?>

                    <?php if ($settings['show_discount_bar'] === 'yes') : ?>
                        <div class="bt-bundle-save--discount-bar">
                            <div class="bt-discount-progress">
                                <div class="bt-progress-fill" style="width: 0%"></div>
                            </div>
                            <div class="bt-discount-text" data-template="<?php echo esc_attr($settings['discount_text']); ?>">
                                <?php echo esc_html($settings['discount_text']); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <button class="bt-bundle-save--add-more-btn" data-bundle-products="<?php echo esc_attr(json_encode($bundle_products)); ?>">
                        <?php echo esc_html($settings['add_more_text']); ?>
                    </button>
                </div>

                <div class="bt-bundle-save--products"
                    data-default-products="<?php echo esc_attr(json_encode($default_products)); ?>"
                    data-bundle-products="<?php echo esc_attr(json_encode($bundle_products)); ?>">
                    <?php
                    // Display default products
                    if (!empty($default_products)) {
                        foreach ($default_products as $product_id) {
                            $this->render_product_item($product_id);
                        }
                    }
                    ?>
                </div>

                <div class="bt-bundle-save--footer">
                    <div class="bt-bundle-save--subtotal">
                        <span class="bt-subtotal-label"><?php esc_html_e('Subtotal', 'woozio'); ?></span>
                        <span class="bt-subtotal-amount"><?php echo wc_price(0); ?></span>
                    </div>
                    <button class="bt-bundle-save--add-cart-btn" data-ids="[]">
                        <?php echo esc_html($settings['button_text']); ?>
                    </button>
                </div>

                <!-- Modal for selecting products -->
                <div class="bt-bundle-save--modal" style="display: none;">
                    <div class="bt-modal-overlay"></div>
                    <div class="bt-modal-content">
                        <div class="bt-modal-header">
                            <h4><?php esc_html_e('Add Products to Bundle', 'woozio'); ?></h4>
                            <button class="bt-modal-close"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg></button>
                        </div>
                        <div class="bt-modal-body">
                            <!-- Available products will be loaded here via JS -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }

    protected function render_product_item($item_id)
    {
        $product = wc_get_product($item_id);
        if (!$product) {
            return;
        }

        $is_variation = $product->is_type('variation');
        $parent_id = $is_variation ? $product->get_parent_id() : $item_id;
        $variation_id = $is_variation ? $item_id : 0;

        // Get product link (use parent for variations)
        $product_link = get_permalink($parent_id);

        // Get product name
        $product_name = $product->get_name();

        // Get variation attributes if applicable
        $variation_text = '';
        if ($is_variation) {
            $attributes = $product->get_attributes();
            $attr_labels = [];
            foreach ($attributes as $attr_name => $attr_value) {
                // Get term name if taxonomy
                if (taxonomy_exists($attr_name)) {
                    $term = get_term_by('slug', $attr_value, $attr_name);
                    $attr_value = $term ? $term->name : $attr_value;
                }

                $attr_labels[] = ucfirst($attr_value);
            }
            if (!empty($attr_labels)) {
                $variation_text = implode('/', $attr_labels);
            }
        }

        $product_image = $product->get_image('thumbnail');
        $regular_price = $product->get_regular_price();
        $sale_price = $product->get_sale_price();
        $price = $product->get_price();
    ?>
        <div class="bt-bundle-product--item"
            data-product-id="<?php echo esc_attr($parent_id); ?>"
            data-variation-id="<?php echo esc_attr($variation_id); ?>"
            data-price="<?php echo esc_attr($price); ?>"
            data-regular-price="<?php echo esc_attr($regular_price ? $regular_price : $price); ?>">
            <div class="bt-product-thumb">
                <a href="<?php echo esc_url($product_link); ?>">
                    <?php echo $product_image; ?>
                </a>
            </div>
            <div class="bt-product-info">
                <h4 class="bt-product-name">
                    <a href="<?php echo esc_url($product_link); ?>"><?php echo esc_html($product_name); ?></a>
                </h4>
                <div class="bt-product-price">
                    <?php echo $product->get_price_html(); ?>
                </div>
                <?php if ($variation_text) : ?>
                    <div class="bt-product-variation"><?php echo esc_html($variation_text); ?></div>
                <?php endif; ?>
            </div>
            <div class="bt-product-actions">
                <button class="bt-product-remove" data-item-id="<?php echo esc_attr($item_id); ?>">
                    <span class="bt-remove-text"><?php esc_html_e('REMOVE', 'woozio'); ?></span>
                </button>
            </div>
        </div>
<?php
    }

    protected function content_template() {}
}
