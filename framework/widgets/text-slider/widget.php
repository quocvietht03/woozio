<?php

namespace WoozioElementorWidgets\Widgets\TextSlider;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Woozio_TextSlider extends Widget_Base
{

    public function get_name()
    {
        return 'bt-text-slider';
    }

    public function get_title()
    {
        return __('Text Slider', 'woozio');
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
            'text_item',
            [
                'label' => esc_html__('Text Item', 'woozio'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => __('This is text', 'woozio'),
            ]
        );


        $this->add_control(
            'list',
            [
                'label' => __('List Texts', 'woozio'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'text_item' => __('Lifetime Updated', 'woozio'),
                    ],
                    [
                        'text_item' => __('Free Support', 'woozio'),
                    ],
                    [
                        'text_item' => __('Premium Plugins', 'woozio'),
                    ],
                ],
            ]
        );
        $this->add_control(
            'spacing_image',
            [
                'label' => __('Spacing Image', 'woozio'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
                'description' => __('Select image to display between text items', 'woozio'),
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
            'slider_direction_rlt',
            [
                'label' => __('Slider Direction RTL', 'woozio'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'woozio'),
                'label_off' => __('No', 'woozio'),
                'default' => 'no',
            ]
        );
        $this->add_control(
            'slider_speed',
            [
                'label' => __('Slider Speed', 'woozio'),
                'type' => Controls_Manager::NUMBER,
                'default' => 10000,
            ]
        );
        $this->add_control(
            'slider_spacebetween',
            [
                'label' => __('Slider SpaceBetween', 'woozio'),
                'type' => Controls_Manager::NUMBER,
                'default' => 30,
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'section_text_style',
            [
                'label' => esc_html__('Text Style', 'woozio'),
            ]
        );
        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '#111111',
                'selectors' => [
                    '{{WRAPPER}} .bt-text--item span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'date_typography',
                'label' => __('Typography', 'woozio'),
                'default' => '',
                'selector' => '{{WRAPPER}} .bt-text--item span',
            ]
        );
        $this->end_controls_section();
    }

    protected function register_controls()
    {
        $this->register_layout_section_controls();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        if (empty($settings['list'])) {
            return;
        }

        if ($settings['slider_direction_rlt'] === 'yes') {
            $slider_direction = 'rtl';
        } else {
            $slider_direction = 'ltr';
        }

        $slider_speed = $settings['slider_speed'];
        $slider_space_between = $settings['slider_spacebetween'];
?>
        <div class="bt-elwg-text-slider--default swiper" data-direction="<?php echo esc_attr($slider_direction) ?>" data-speed="<?php echo esc_attr($slider_speed) ?>" data-spacebetween="<?php echo esc_attr($slider_space_between) ?>">
            <ul class="bt-text-slider swiper-wrapper">
                <?php foreach ($settings['list'] as $index => $item) { ?>
                    <li class="bt-text--item swiper-slide">
                        <?php if (!empty($settings['spacing_image'])) { ?>
                            <img src="<?php echo esc_url($settings['spacing_image']['url']); ?>" alt="" />
                        <?php } ?>
                        <?php echo '<span>' . $item['text_item'] . '</span>'; ?>
                    </li>
                <?php } ?>

            </ul>
        </div>
<?php
    }

    protected function content_template() {}
}
