<?php

namespace WoozioElementorWidgets\Widgets\VerticalBannerSlider;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;

class Woozio_VerticalBannerSlider extends Widget_Base
{

    public function get_name()
    {
        return 'bt-vertical-banner-slider';
    }

    public function get_title()
    {
        return __('Vertical Banner Slider', 'woozio');
    }

    public function get_icon()
    {
        return 'bt-bears-icon eicon-slider-vertical';
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
            'section_content',
            [
                'label' => __('Content', 'woozio'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'banner_image',
            [
                'label' => __('Banner Image', 'woozio'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'banner_heading',
            [
                'label' => __('Heading', 'woozio'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Banner Heading', 'woozio'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'banner_link',
            [
                'label' => __('Link', 'woozio'),
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'woozio'),
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $this->add_control(
            'banner_list',
            [
                'label' => __('Banner Items', 'woozio'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'banner_heading' => __('Combo Meals', 'woozio'),
                        'banner_link' => ['url' => '#'],
                    ],
                    [
                        'banner_heading' => __('Burger Line', 'woozio'),
                        'banner_link' => ['url' => '#'],
                    ],
                    [
                        'banner_heading' => __('Fried Chicken', 'woozio'),
                        'banner_link' => ['url' => '#'],
                    ],
                    [
                        'banner_heading' => __('Pizza Line', 'woozio'),
                        'banner_link' => ['url' => '#'],
                    ],
                    [
                        'banner_heading' => __('Sides & Snacks', 'woozio'),
                        'banner_link' => ['url' => '#'],
                    ],
                ],
                'title_field' => '{{{ banner_heading }}}',
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

        $this->add_control(
            'autoplay',
            [
                'label' => __('Autoplay', 'woozio'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'woozio'),
                'label_off' => __('No', 'woozio'), 
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label' => __('Autoplay Speed', 'woozio'),
                'type' => Controls_Manager::NUMBER,
                'default' => 2000,
                'condition' => [
                    'autoplay' => 'yes'
                ],
            ]
        );
        $this->add_control(
            'autoplay_only_mobile',
            [
                'label' => __('Autoplay Only Mobile', 'woozio'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'woozio'),
                'label_off' => __('No', 'woozio'),
                'default' => 'no',
                'condition' => [
                    'autoplay' => 'yes'
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function register_style_section_controls()
    {
        // Container Styles
        $this->start_controls_section(
            'section_container_style',
            [
                'label' => __('Banner', 'woozio'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'banner_height',
            [
                'label' => __('Minimum Height', 'woozio'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 500,
                        'max' => 1000,
                        'step' => 10,
                    ],
                    'vh' => [
                        'min' => 50,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 800,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-vertical-banner-slider' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'overlay_background',
            [
                'label' => __('Overlay Background', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-banner-backgrounds::after' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'overlay_opacity',
            [
                'label' => __('Overlay Opacity', 'woozio'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-banner-backgrounds::after' => 'opacity: {{SIZE}}%;',
                ],
            ]
        );

        $this->end_controls_section();

        // Heading Styles
        $this->start_controls_section(
            'section_heading_style',
            [
                'label' => __('Headings', 'woozio'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'heading_gap',
            [
                'label' => __('Gap Between Headings', 'woozio'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
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
                    'rem' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-banner-headings' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'label' => __('Typography', 'woozio'),
                'selector' => '{{WRAPPER}} .bt-banner-heading',
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => __('Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .bt-banner-heading' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bt-banner-heading::before, {{WRAPPER}} .bt-banner-heading::after' => 'background: {{VALUE}};',
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

        if (empty($settings['banner_list'])) {
            return;
        }

?>
        <div class="bt-vertical-banner-slider" data-autoplay="<?php echo esc_attr($settings['autoplay']); ?>" data-autoplay-speed="<?php echo esc_attr($settings['autoplay_speed']); ?>" data-autoplay-only-mobile="<?php echo esc_attr($settings['autoplay_only_mobile']); ?>">
            <!-- Banner Backgrounds -->
            <div class="bt-banner-backgrounds">
                <?php foreach ($settings['banner_list'] as $index => $item): 
                        $class_active = $index === 0 ? 'active' : '';
                    ?>
                    <div class="bt-banner-background <?php echo esc_attr($class_active); ?>" data-index="<?php echo esc_attr($index); ?>">
                        <?php if (!empty($item['banner_image']['id'])) { ?>
                            <?php echo wp_get_attachment_image($item['banner_image']['id'], $settings['thumbnail_size']); ?>
                        <?php } else {
                            if (!empty($item['banner_image']['url'])) {
                                echo '<img src="' . esc_url($item['banner_image']['url']) . '" alt="' . esc_html__('Awaiting image', 'woozio') . '">';
                            } else {
                                echo '<img src="' . esc_url(Utils::get_placeholder_image_src()) . '" alt="' . esc_html__('Awaiting image', 'woozio') . '">';
                            }
                        } ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Heading List -->
            <div class="bt-banner-headings">
                <?php foreach ($settings['banner_list'] as $index => $item): ?>
                    <?php
                    $link_key = 'link_' . $index;
                    $this->add_link_attributes($link_key, $item['banner_link']);
                    $link_attributes = $this->get_render_attribute_string($link_key);
                    $class_active = $index === 0 ? 'active' : '';

                    ?>
                    <a <?php echo esc_url($link_attributes); ?> class="bt-banner-heading <?php echo esc_attr($class_active); ?>" data-index="<?php echo esc_attr($index); ?>">
                        <?php echo esc_html($item['banner_heading']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
<?php
    }

    protected function content_template() {}
}
