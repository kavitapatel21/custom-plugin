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

/**add_action('custom_new_hook', 'hostinger_custom_cron_func');
function hostinger_custom_cron_func()
{
    require_once(ABSPATH . 'test-cron.php');
}*/


// Scheduled Action Hook
function every_month_cron_hook() {
    require_once(ABSPATH . 'test-cron.php');
}

// Custom Cron Recurrences
function custom_cron_job_recurrence( $schedules ) {
    $schedules['every_minute'] = array(
        'display' => __( 'every_month_label', 'textdomain' ),
        'interval' => 60,
    );
    return $schedules;
}
add_filter( 'cron_schedules', 'custom_cron_job_recurrence' );

// Schedule Cron Job Event
function custom_cron_job() {
    if ( ! wp_next_scheduled( 'every_month_cron_hook' ) ) {
        wp_schedule_event( time(), 'every_minute', 'every_month_cron_hook' );
    }
}
add_action( 'wp', 'custom_cron_job' );