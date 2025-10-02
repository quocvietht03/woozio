<?php

namespace WoozioElementorWidgets\Widgets\TitleNavWithSlider;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Widget_TitleNavWithSlider extends Widget_Base
{

    public function get_name()
    {
        return 'bt-title-nav-with-slider';
    }

    public function get_title()
    {
        return __('Title Nav With Slider', 'woozio');
    }

    public function get_icon()
    {
        return 'eicon-navigation-horizontal';
    }

    public function get_categories()
    {
        return ['woozio'];
    }

    public function get_script_depends()
    {
        return ['swiper-slider', 'elementor-widgets'];
    }


    protected function register_layout_section_controls()
    {

        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content Settings', 'woozio'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'nav_title',
            [
                'label' => __('Navigation Title', 'woozio'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => __('Gym Essentials', 'woozio'),
                'placeholder' => __('Enter navigation title', 'woozio'),
            ]
        );

        $repeater->add_control(
            'content_image',
            [
                'label' => __('Content Image', 'woozio'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'description' => __('Choose image to display for this navigation item', 'woozio'),
            ]
        );

        $repeater->add_control(
            'content_heading',
            [
                'label' => __('Content Heading', 'woozio'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => __('Seamless High-Performance Set', 'woozio'),
                'placeholder' => __('Enter content heading', 'woozio'),
            ]
        );

        $repeater->add_control(
            'content_description',
            [
                'label' => __('Content Description', 'woozio'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 4,
                'default' => __('Built for movement by day, this set offers breathable stretch performance. From spirited workouts to casual looks, stay dry and comfortable with sweat-wicking technology.', 'woozio'),
                'placeholder' => __('Enter content description', 'woozio'),
            ]
        );

        $repeater->add_control(
            'content_button_text',
            [
                'label' => __('Button Text', 'woozio'),
                'type' => Controls_Manager::TEXT,
                'default' => __('SHOP NOW', 'woozio'),
                'placeholder' => __('Enter button text', 'woozio'),
            ]
        );

        $repeater->add_control(
            'content_button_link',
            [
                'label' => __('Button Link', 'woozio'),
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'woozio'),
                'default' => [
                    'url' => '#',
                    'is_external' => true,
                    'nofollow' => true,
                ],
            ]
        );

        $this->add_control(
            'nav_items',
            [
                'label' => __('Navigation Items', 'woozio'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'nav_title' => __('Gym Essentials', 'woozio'),
                    ],
                    [
                        'nav_title' => __('Beach Activewear', 'woozio'),
                    ],
                    [
                        'nav_title' => __('Yoga Comfort', 'woozio'),
                    ],
                    [
                        'nav_title' => __('Sport Basics', 'woozio'),
                    ],
                ],
                'title_field' => '{{{ nav_title }}}',
                'min_items' => 2,
                'max_items' => 8,
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
            'slider_offset_height',
            [
                'label' => __('Offset Height', 'woozio'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 80,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-elwg-title-nav-with-slider--default' => '--offset-slider: {{SIZE}}{{UNIT}};',
                ],
                'devices' => [ 'desktop', 'laptop' , 'tablet_extra'],
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
                    '{{WRAPPER}} .bt-elwg-title-nav-with-slider--default' => '--slider-offset-width: {{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'ui',
                
                'devices' => [ 'tablet', 'mobile_extra' , 'mobile'],
            ]
        );
        $this->add_responsive_control(
            'slider_height',
            [
                'label' => __('Slider Height', 'woozio'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 700,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-elwg-title-nav-with-slider--default' => '--height-slider: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'slider_spacing',
            [
                'label' => __('Spacing', 'woozio'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 80,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-elwg-title-nav-with-slider--default' => '--spacing: {{SIZE}}{{UNIT}};',
                ],
              
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_slider_settings',
            [
                'label' => __('Product Slider Settings', 'woozio'),
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


        $this->end_controls_section();
    }

    protected function register_style_section_controls()
    {
        // Navigation Style Section
        $this->start_controls_section(
            'section_nav_style',
            [
                'label' => __('Navigation Style', 'woozio'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'nav_title_color',
            [
                'label' => __('Navigation Title Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-title-nav-with-slider .bt-nav-left .bt-nav-item .bt-nav-title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bt-title-nav-with-slider .bt-content-slide .bt-content-info .bt-nav-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_title_active_color',
            [
                'label' => __('Active Title Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-title-nav-with-slider .bt-nav-left .bt-nav-item.active .bt-nav-title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bt-title-nav-with-slider .bt-content-slide .bt-content-info .bt-nav-title' => 'color: {{VALUE}};',
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'nav_title_typography',
                'selector' => '{{WRAPPER}} .bt-title-nav-with-slider .bt-nav-left .bt-nav-item .bt-nav-title, {{WRAPPER}} .bt-title-nav-with-slider .bt-content-slide .bt-content-info .bt-nav-title',
            ]
        );
        $this->add_responsive_control(
            'nav_title_gap',
            [
                'label' => __('Navigation Gap', 'woozio'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-title-nav-with-slider .bt-nav-left .bt-nav-item' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .bt-elwg-title-nav-with-slider--default' => '--gap-nav-item: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Content Style Section
        $this->start_controls_section(
            'section_content_style',
            [
                'label' => __('Content Style', 'woozio'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'content_heading_style',
            [
                'label' => __('Content Heading', 'woozio'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'content_heading_color',
            [
                'label' => __('Heading Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-title-nav-with-slider .bt-content-slide .bt-content-info .bt-content-heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_heading_typography',
                'selector' => '{{WRAPPER}} .bt-title-nav-with-slider .bt-content-slide .bt-content-info .bt-content-heading',
            ]
        );

        $this->add_control(
            'content_description_style',
            [
                'label' => __('Content Description', 'woozio'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'content_description_color',
            [
                'label' => __('Description Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-title-nav-with-slider .bt-content-slide .bt-content-info .bt-content-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_description_typography',
                'selector' => '{{WRAPPER}} .bt-title-nav-with-slider .bt-content-slide .bt-content-info .bt-content-description',
            ]
        );

        $this->add_control(
            'content_button_style',
            [
                'label' => __('Button Style', 'woozio'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs('content_button_style_tabs');

        $this->start_controls_tab(
            'content_button_normal_tab',
            [
                'label' => __('Normal', 'woozio'),
            ]
        );

        $this->add_control(
            'content_button_color',
            [
                'label' => __('Text Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-title-nav-with-slider .bt-content-slide .bt-content-info .bt-content-button .bt-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_button_bg_color',
            [
                'label' => __('Background Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-title-nav-with-slider .bt-content-slide .bt-content-info .bt-content-button .bt-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'content_button_hover_tab',
            [
                'label' => __('Hover', 'woozio'),
            ]
        );

        $this->add_control(
            'content_button_hover_color',
            [
                'label' => __('Text Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-title-nav-with-slider .bt-content-slide .bt-content-info .bt-content-button .bt-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_button_hover_bg_color',
            [
                'label' => __('Background Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-title-nav-with-slider .bt-content-slide .bt-content-info .bt-content-button .bt-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_button_typography',
                'selector' => '{{WRAPPER}} .bt-title-nav-with-slider .bt-content-slide .bt-content-info .bt-content-button .bt-button',
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
            'speed' => isset($settings['slider_speed']) ? $settings['slider_speed'] : 1000,
            'autoplay_delay' => isset($settings['slider_autoplay_delay']) ? $settings['slider_autoplay_delay'] : 3000,
        ];

        if (empty($settings['nav_items'])) {
            return;
        }
?>
        <div class="bt-elwg-title-nav-with-slider--default" data-slider-settings='<?php echo json_encode($slider_settings); ?>'>
            <div class="bt-title-nav-with-slider">
                <div class="bt-nav-left">
                    <div class="bt-nav-list">
                        <?php foreach ($settings['nav_items'] as $index => $item) : ?>
                            <div class="bt-nav-item <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>">
                                <h3 class="bt-nav-title"><?php echo esc_html($item['nav_title']); ?></h3>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="bt-content-right">
                    <div class="swiper bt-content-slider js-title-nav-content">
                        <div class="swiper-wrapper">
                            <?php foreach ($settings['nav_items'] as $nav_index => $nav_item) : ?>
                                <div class="swiper-slide">
                                    <div class="bt-content-slide">
                                        <?php if (!empty($nav_item['content_image']['url'])) : ?>
                                            <div class="bt-content-image">
                                                <?php if (!empty($nav_item['content_image']['id'])) : ?>
                                                    <?php echo wp_get_attachment_image($nav_item['content_image']['id'], $settings['thumbnail_size']); ?>
                                                <?php else : ?>
                                                    <img src="<?php echo esc_url(Utils::get_placeholder_image_src()); ?>" alt="<?php echo esc_attr__('Awaiting image', 'woozio'); ?>">
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>

                                        <div class="bt-content-info">
                                            <?php if (!empty($nav_item['nav_title'])) : ?>
                                                <h3 class="bt-nav-title"><?php echo esc_html($nav_item['nav_title']); ?></h3>
                                            <?php endif; ?>
                                            <?php if (!empty($nav_item['content_heading'])) : ?>
                                                <h2 class="bt-content-heading"><?php echo esc_html($nav_item['content_heading']); ?></h2>
                                            <?php endif; ?>

                                            <?php if (!empty($nav_item['content_description'])) : ?>
                                                <p class="bt-content-description"><?php echo esc_html($nav_item['content_description']); ?></p>
                                            <?php endif; ?>

                                            <?php if (!empty($nav_item['content_button_text']) && !empty($nav_item['content_button_link']['url'])) : ?>
                                                <div class="bt-content-button">
                                                    <a href="<?php echo esc_url($nav_item['content_button_link']['url']); ?>"
                                                        class="bt-button"
                                                        <?php echo !empty($nav_item['content_button_link']['is_external']) ? 'target="_blank"' : ''; ?>
                                                        <?php echo !empty($nav_item['content_button_link']['nofollow']) ? 'rel="nofollow"' : ''; ?>>
                                                        <?php echo esc_html($nav_item['content_button_text']); ?>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
    }

    protected function content_template() {}
}
