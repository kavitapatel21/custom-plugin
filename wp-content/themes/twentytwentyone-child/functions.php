<?php
/* Child theme generated with WPS Child Theme Generator */

if (!function_exists('b7ectg_theme_enqueue_styles')) {
    add_action('wp_enqueue_scripts', 'b7ectg_theme_enqueue_styles');

    function b7ectg_theme_enqueue_styles()
    {
        wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
        wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style'));
    }
}

//cron file
/**add_filter('cron_schedules', 'example_add_cron_interval');

function example_add_cron_interval($schedules)
{
    $schedules['every_one_minute'] = array(
        'interval' => 60, //[interval] => 86400 once a daily
        'display' => esc_html__('Every one minute'),
    );

    return $schedules;
}


//Schedule the event
add_action('wp', 'launch_the_action');
function launch_the_action()
{
    if (!wp_next_scheduled("custom__cron")) {
        wp_schedule_event(time(), "every_one_minute", "custom__cron");
    }
}**/

//action for cron event
add_action('custom__cron', 'create_post');
function create_post()
{
    require_once(ABSPATH . 'test-cron.php');
}
