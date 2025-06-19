<?php

namespace WoozioElementorWidgets\Widgets\ListFaq;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;


class Widget_ListFaq extends Widget_Base
{

    public function get_name()
    {
        return 'bt-list-faq';
    }

    public function get_title()
    {
        return __('List FAQ', 'woozio');
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
            'faq_title',
            [
                'label' => __('Text', 'woozio'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => __('FAQ title', 'woozio'),
            ]
        );

        $repeater->add_control(
            'faq_content',
            [
                'label' => __('Content', 'woozio'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'default' => __('FAQ content', 'woozio'),
            ]
        );

        $this->add_control(
            'list',
            [
                'label' => __('List Faq', 'woozio'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'faq_title' => __('What is Woozio?', 'woozio'),
                        'faq_content' => __('Woozio is a powerful WordPress theme that helps you build beautiful websites quickly and easily.', 'woozio')
                    ],
                    [
                        'faq_title' => __('How do I get started with Woozio?', 'woozio'),
                        'faq_content' => __('Simply install the theme, import a demo, and customize it using our intuitive page builder.', 'woozio')
                    ],
                    [
                        'faq_title' => __('Do you offer support?', 'woozio'),
                        'faq_content' => __('Yes, we provide dedicated support through our help center and ticket system.', 'woozio')
                    ],
                ],
                'title_field' => '{{{ faq_title }}}',
            ]
        );


        $this->end_controls_section();
    }

    protected function register_style_section_controls()
    {
        $this->start_controls_section(
            'section_style_item',
            [
                'label' => esc_html__('General', 'woozio'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'list_border',
            [
                'label' => __('Border Width', 'woozio'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-elwg-list-faq--default .item-faq-inner' => 'border-bottom: {{SIZE}}{{UNIT}} solid #E9E9E9;',
                ],
            ]
        );
        $this->add_control(
            'list_border_color',
            [
                'label' => __('Border Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-elwg-list-faq--default .item-faq-inner' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'list_gap',
            [
                'label' => __('Space Between', 'woozio'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-elwg-list-faq--default .item-faq-inner' => 'padding-top: {{SIZE}}{{UNIT}};padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'list_gap_horizontal',
            [
                'label' => __('Horizontal Padding', 'woozio'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-elwg-list-faq--default .item-faq-inner' => 'padding-left: {{SIZE}}{{UNIT}};padding-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_content',
            [
                'label' => esc_html__('Content', 'woozio'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'title_style',
            [
                'label' => esc_html__('Title', 'woozio'),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->add_control(
            'list_title_color',
            [
                'label' => __('Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .bt-item-title h3' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'list_title_hover_color',
            [
                'label' => __('Color Hover/Active', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .bt-item-title:hover h3' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bt-item-title.active h3' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'list_title_typography',
                'label' => __('Typography', 'woozio'),
                'default' => '',
                'selector' => '{{WRAPPER}} .bt-item-title h3 ',
            ]
        );
        $this->add_control(
            'content_style',
            [
                'label' => esc_html__('content', 'woozio'),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->add_control(
            'list_content_color',
            [
                'label' => __('Color', 'woozio'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .bt-item-content' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'list_content_typography',
                'label' => __('Typography', 'woozio'),
                'default' => '',
                'selector' => '{{WRAPPER}} .bt-item-content',
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

        if (empty($settings['list'])) {
            return;
        }

?>
        <div class="bt-elwg-list-faq--default">
            <div class="bt-elwg-list-faq-inner">
                <?php foreach ($settings['list'] as $index => $item): ?>
                    <div class="item-faq">
                        <div class="item-faq-inner">
                            <div class="bt-item-title">
                                <?php if (!empty($item['faq_title'])): ?>
                                    <h3> <?php echo esc_html($item['faq_title']) ?> </h3>
                                <?php endif; ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="plus" width="18" height="18" viewBox="0 0 160 160">
                                    <rect class="vertical-line" x="70" width="15" height="160" rx="7" ry="7" />
                                    <rect class="horizontal-line" y="70" width="160" height="15" rx="7" ry="7" />
                                </svg>
                            </div>
                            <?php if (!empty($item['faq_content'])): ?>
                                <div class="bt-item-content">
                                    <?php echo esc_html($item['faq_content']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
<?php }

    protected function content_template() {}
}
