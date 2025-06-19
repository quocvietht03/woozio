<?php

namespace WoozioElementorWidgets\Widgets\SiteNotification;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_BBorder;
use Elementor\Group_Control_Box_Shadow;

class Widget_SiteNotification extends Widget_Base
{

    public function get_name()
    {
        return 'bt-site-notification';
    }

    public function get_title()
    {
        return __('Site Notification', 'woozio');
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
            'section_slider',
            [
                'label' => __('Notification', 'woozio'),
            ]
        );
        $this->add_control(
            'slider_autoplay',
            [
                'label' => __('Notification Autoplay', 'woozio'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'woozio'),
                'label_off' => __('No', 'woozio'),
                'default' => 'no',
            ]
        );
        $this->add_control(
            'slider_autoplay_delay',
            [
                'label' => __('Notification Delay', 'woozio'),
                'type' => Controls_Manager::NUMBER,
                'default' => 3000,
                'min' => 1000,
                'max' => 10000,
                'step' => 500,
                'description' => __('Delay between notification in milliseconds', 'woozio'),
                'condition' => [
                    'slider_autoplay' => 'yes',
                ],
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
            'text_color',
            [
                'label' => __('Text Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .bt-site-notification--text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_color',
            [
                'label' => __('Link Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .bt-site-notification--text a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_hover_color',
            [
                'label' => __('Link Hover Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .bt-site-notification--text a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'label' => __('Typography', 'woozio'),
                'selector' => '{{WRAPPER}} .bt-site-notification--text',
            ]
        );

        $this->add_responsive_control(
            'text_align',
            [
                'label' => __('Alignment', 'woozio'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'woozio'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'woozio'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'woozio'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .bt-site-notification--text' => 'text-align: {{VALUE}};',
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
            'autoplay' => $settings['slider_autoplay'] == 'yes' ? true : false,
            'autoplayDelay' => $settings['slider_autoplay_delay'],
            'speed' => 1000,
        ];
        if (function_exists('get_field')) {
            $site_infor = get_field('site_information', 'options') ?: '';
        } else {
            $site_infor = '';
        }
        if (!empty($site_infor) && isset($site_infor)) { ?>
            <div class="bt-elwg-site-notification--default " data-slider-settings='<?php echo json_encode($slider_settings); ?>'>
                <div class="bt-site-notification">
                    <div class="swiper bt-site-notification--content js-notification-content">
                        <div class="swiper-wrapper">
                            <?php foreach ($site_infor['site_notification'] as $item) : ?>
                                <div class="swiper-slide">
                                    <div class="bt-site-notification--item">
                                        <?php if (!empty($item['text_notification'])) : 
                                           echo  '<div class="bt-site-notification--text">'. $item['text_notification'] . '</div>';
                                        endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
<?php
        }
    }

    protected function content_template() {}
}
